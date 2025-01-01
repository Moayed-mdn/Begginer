
importScripts('https://www.gstatic.com/firebasejs/11.1.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/11.1.0/firebase-messaging-compat.js');
firebase.initializeApp({
  apiKey: "AIzaSyAUaDfzpzEH3eEhrezk1M3whb8YLDryScg",
  authDomain: "medicationswarehouse.firebaseapp.com",
  projectId: "medicationswarehouse",
  storageBucket: "medicationswarehouse.firebasestorage.app",
  messagingSenderId: "810092722594",
  appId: "1:810092722594:web:7aa67189e5637aa4815afb",
  measurementId: "G-YQ01F2NNZ2"
});
const messaging = firebase.messaging();


messaging.onBackgroundMessage((payload) => {
    console.log(
      '[firebase-messaging-sw.js] Received background message ',
      payload
    );
    // Customize notification here
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
      body: payload.notification.body,
    };
    console.log(self)
    self.registration.showNotification("warehouseOwner", notificationOptions);
});


