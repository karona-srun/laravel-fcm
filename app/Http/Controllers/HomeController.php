<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hamcrest\Core\HasToString;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function savePushNotificationToken(Request $request)
    {
        Auth::user()->update(['device_token'=>$request->token]);
        return response()->json(['token saved successfully.']);
    }
    
    public function sendPushNotification(Request $request)
    {
        $token = Auth::user()->device_token;
        $SERVER_KEY = "AAAARXhlquw:APA91bG4sQpL5KSHR8nymI3MDuBnGnQ0iBekWmIVUDQt-bFULl81DccdbI7JIuKtQe4g6ALmK5Fv_pYURIb3SqNQoi6-k_31sJz1YoWfCGOaLlnjV1lOve1aRjMbnDUKd0gSYwAL5khV";
        $msg = array(
            'body'  => $request->body ?? 'Body',
            'title' => $request->title ?? 'Title',
            'icon'  => "https://image.flaticon.com/icons/png/512/1827/1827370.png",/*Default Icon*/
            'sound' => "https://notificationsounds.com/storage/sounds/file-sounds-1127-beyond-doubt.ogg",/*Default sound*/

        );

        $fields = array(
            'to'        => $token,
            'notification'  => $msg,
            "content_available" => true,
            "priority" => "high",
        );

        $headers = array(
            'Authorization: key=' . $SERVER_KEY,
            'Content-Type: application/json'
        );
        //#Send Reponse To FireBase Server 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $reponse = curl_exec($ch);
        // dd($reponse);
        curl_close($ch);
        $data = json_decode(json_encode($reponse));
        return redirect('/home')->with('status', 'Notification sent!'.$data);
    }
}
