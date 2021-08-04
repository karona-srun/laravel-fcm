@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <center><h2>Laravel Firebase Push Notification</h2></center>
        <div class="col-md-8">            
             <center>
                <button id="btn-nft-enable" onclick="initFirebaseMessagingRegistration()" class="btn btn-danger btn-xs btn-flat">Allow for Notification</button>
            </center><br>
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert"  alert-dismissable>
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action="{{ url('send-notification') }}" method="POST">

                        @csrf

                        <div class="form-group">

                            <label>Title</label>

                            <input type="text" class="form-control" name="title">

                        </div>

                        <div class="form-group">

                            <label>Body</label>

                            <textarea class="form-control" name="body"></textarea>

                          </div>

                        <button type="submit" class="btn btn-primary">Send Notification</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script>
    // Your web app's Firebase configuration
    var firebaseConfig = {
        apiKey: "AIzaSyAaJeWfsPvI3sIRbxlkwAGhTKsqKD47KLo",
        authDomain: "laravelfcm-6b248.firebaseapp.com",
        databaseURL: "https://laravelfcm-6b248-default-rtdb.firebaseio.com",//"https://laravelfcm-6b248.firebaseio.com",
        projectId: "laravelfcm-6b248",
        storageBucket: "laravelfcm-6b248.appspot.com",
        messagingSenderId: "298372672236",
        appId: "1:298372672236:web:c7797beed3621f68ac53c9",
        measurementId: "G-E2WFPS2E9W",
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    firebase.analytics();
    const messaging = firebase.messaging();


    function initFirebaseMessagingRegistration() {
            messaging
            .requestPermission()
            .then(function () {
                return messaging.getToken()
            })
            .then(function(token) {
                console.log(token);
   
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
  
                $.ajax({
                    url: '{{ url("save-push-notification-token") }}',
                    type: 'POST',
                    data: {
                        token: token
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        alert('Token saved successfully.');
                    },
                    error: function (err) {
                        console.log('User Token Error'+ err);
                    },
                });
  
            }).catch(function (err) {
                console.log('User Chat Token Error'+ err);
            });
        }



    messaging
        .requestPermission()
        .then(function() {
            // MsgElem.innerHTML = "Notification permission granted." 
            console.log("Notification permission granted.");

            // get the token in the form of promise
            return messaging.getToken()
        })
        .then(function(token) {
            // print the token on the HTML page     
            console.log(token);
        })
        .catch(function(err) {
            console.log("Unable to get permission to notify.", err);
        });

    messaging.onMessage(function(payload) {
        console.log(payload);
        var notify;
        notify = new Notification(payload.notification.title, {
            body: payload.notification.body,
            icon: payload.notification.icon,
            // tag: "Dummy" // display only one notification
        });
        console.log(payload.notification);
    });

    //firebase.initializeApp(config);
    var database = firebase.database().ref().child("/users/");

    database.on('value', function(snapshot) {
        renderUI(snapshot.val());
    });

    // On child added to db
    database.on('child_added', function(data) {
        console.log("Comming");
        if (Notification.permission !== 'default') {
            var notify;

            notify = new Notification('CodeWife - ' + data.val().username, {
                'body': data.val().message,
                'icon': 'bell.png',
                'tag': data.getKey()
            });
            notify.onclick = function() {
                alert(this.tag);
            }
        } else {
            alert('Please allow the notification first');
        }
    });

    self.addEventListener('notificationclick', function(event) {
        event.notification.close();
    });
</script>
@endsection
