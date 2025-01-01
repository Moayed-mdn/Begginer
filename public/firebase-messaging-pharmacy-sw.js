importScripts('https://www.gstatic.com/firebasejs/11.1.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/11.1.0/firebase-messaging-compat.js');

firebase.initializeApp({
  apiKey: "AIzaSyAUaDfzpzEH3eEhrezk1M3whb8YLDryScg",
  authDomain: "medicationswarehouse.firebaseapp.com",
  projectId: "medicationswarehouse",
  storageBucket: "medicationswarehouse.firebasestorage.app",
  messagingSenderId: "810092722594",
  appId: "1:810092722594:web:a916d9bc7e2eec32815afb",
  measurementId: "G-HY4W4GRG4Z"
});
const messaging = firebase.messaging();


messaging.onBackgroundMessage((payload) => {
  console.log(
      '[firebase-messaging-sw.js] Received background message ',
      payload
  );

  const notificationTitle = payload.notification.title;
  const notificationOptions = {
      body: payload.notification.body,
      tag: "pharmacy-notification" // This is our tag
  };
  console.log(self)
  self.registration.showNotification(notificationTitle, notificationOptions);
});




  
