importScripts('https://www.gstatic.com/firebasejs/8.8.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.8.1/firebase-messaging.js');

// Initialize the Firebase app in the service worker by passing in the
// messagingSenderId.

firebase.initializeApp({
  apiKey: "AIzaSyAaJeWfsPvI3sIRbxlkwAGhTKsqKD47KLo",
  authDomain: "laravelfcm-6b248.firebaseapp.com",
  databaseURL: "https://laravelfcm-6b248-default-rtdb.firebaseio.com",//"https://laravelfcm-6b248.firebaseio.com",
  projectId: "laravelfcm-6b248",
  storageBucket: "laravelfcm-6b248.appspot.com",
  messagingSenderId: "298372672236",
  appId: "1:298372672236:web:c7797beed3621f68ac53c9",
  measurementId: "G-E2WFPS2E9W",
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function (payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  // Customize notification here
  const notificationTitle = 'Background Message Title';
  const notificationOptions = {
    body: 'Background Message body.',
    icon: 'https://images.theconversation.com/files/93616/original/image-20150902-6700-t2axrz.jpg' //your logo here
  };

  return self.registration.showNotification(notificationTitle,
    notificationOptions);
});