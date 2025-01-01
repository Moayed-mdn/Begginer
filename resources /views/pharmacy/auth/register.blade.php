<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    
    <form action="/pharmacy/register" method="POST" id="register">
        @csrf
        
        <input type="text" name='phone_number' placeholder="phone number">
        <input type="password" name='password' placeholder="password">

        <button>Submit</button>
    </form>

    @if($errors->any())
        @foreach ($errors->all() as $error )
                <p> {{$error}}</p>
        @endforeach 
    @endif

</body>
</html>



<script type="module">

    import { initializeApp } from "https://www.gstatic.com/firebasejs/11.1.0/firebase-app.js";
    import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/11.1.0/firebase-messaging.js"; // Import firebase-messaging

    const formRegister=document.querySelector("#register");

    const firebaseConfig = {  
      apiKey: "AIzaSyAUaDfzpzEH3eEhrezk1M3whb8YLDryScg",
      authDomain: "medicationswarehouse.firebaseapp.com",
      projectId: "medicationswarehouse",
      storageBucket: "medicationswarehouse.firebasestorage.app",
      messagingSenderId: "810092722594",
      appId: "1:810092722594:web:a916d9bc7e2eec32815afb",
      measurementId: "G-HY4W4GRG4Z"
    };
    const validatePermission=()=>{
        Notification.requestPermission()
        .then((permission) => {
          if (permission === 'granted') {
            console.log('Notification permission granted.');
          } 
          else {
            console.log('Notification permission denied.');
            alert('Notification permission denied');
          }
       })
       .catch((error) => {
          console.error('Error requesting notification permission:', error);
        });
 
    };
    validatePermission();

    

    const app = initializeApp(firebaseConfig);
    const messaging = getMessaging(app);
    const vapidkey="BC43XPhdUcxeCfVgVXawTzd98R72Bk9YYUaVZsRw_9Sj1YS50euJWDRlPlAgpFtyyKc-86ieDbOfs-ZSsNLhnZY";

    if('serviceWorker' in navigator){
        navigator.serviceWorker.register("/firebase-messaging-pharmacy-sw.js")
          .then((registration)=>{
              console.log("service worker regisered",registration)
          })
          .catch(err=>{
              console.log("service worker registration");
          })
    }
    
    formRegister.addEventListener("submit",async (e)=>{
      e.preventDefault();

      let fcm_token=await getToken(messaging,{vapidkey:vapidkey});
      console.log("fcm_token",fcm_token);
      const formData = new FormData(formRegister);
      formData.append("fcm_token", fcm_token);
      let data={
        "phone_number":formData.get("phone_number"),
        "password":formData.get('password'),
        "fcm_token":formData.get("fcm_token")
      }
      fetch('/pharmacy/register',{
        method:'POST',
        body:JSON.stringify(data),
        headers:{ 
          'X-CSRF-TOKEN': @json(csrf_token()),
          'Content-Type':"application/json"
        }
      })
      .then(response => {
        console.log(response.status)
        if(!response.ok) 
           return  response.json().then(
              data=>{
                console.log(data.message)
                throw new Error(JSON.stringify(data.message));  
            })
        
        return response.json();
      })
      .then(data=>{
        window.location.href = data.view;
            })
      .catch(e => {
          console.log(e) 
      });

  }) 
    
</script>