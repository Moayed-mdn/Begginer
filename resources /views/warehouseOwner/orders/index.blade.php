<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    <div class="message" id="message"></div>

    @if (count($orders)==0)
            <p><strong>No Orders Yet</strong></p>
    @endif
    
    @foreach ( $orders as $order )
        @foreach ($order->getAttributes() as $key=>$val )
            @if ($key=="id")
                <a href="/orders/{{$order->id}}">{{$key}}: {{$val}} </a>
            @else
            {{$key}}: {{$val }}
            @endif
            <br>
        @endforeach    

    @endforeach

    

</body>
</html>


<script>
      message=  @json(@session('message')??"");
    if(message)
        alert(message)

</script>
<script type="module"> 

    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.13.2/firebase-app.js";
    import { getMessaging, onMessage } from "https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging.js";


    const firebaseConfig = {
    apiKey: "AIzaSyAQiK_LkmHXmb-tc8BHEsqIvPyQhhCAy2c",
    authDomain: "medicationswarehouse.firebaseapp.com",
    projectId: "medicationswarehouse",
    storageBucket: "medicationswarehouse.firebasestorage.app",
    messagingSenderId: "810092722594",
    appId: "1:810092722594:web:7aa67189e5637aa4815afb",
    measurementId: "G-YQ01F2NNZ2"
    };


    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const messaging = getMessaging(app);

    // Register the service worker
    navigator.serviceWorker.register('firebase-messaging-sw.js')
        .then((registration) => {console.log('Service Worker registered:', registration);})
        .catch((error) => {console.error('Service Worker registration failed:', error);});

 
    let b=(payload)=>{
    let not=document.createElement("span");
        not.innerText=payload.notification.body;
        document.querySelector("#message").appendChild(not)
    }
    // Listen for messages in the foreground
    onMessage(messaging, (payload) => {
        console.log('Message received in foreground:', payload);
        if (Notification.permission === "granted") {
            let notifi=  new Notification(" warehouse (foreground)");
            notifi.addEventListener("click",(e)=>{
                e.preventDefault();
                location.reload();
            });
        }
        else 
            alert("permission denied");
        
    });

    Notification.requestPermission();
</script>


