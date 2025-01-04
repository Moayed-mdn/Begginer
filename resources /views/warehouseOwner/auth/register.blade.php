<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Document</title>
  </head>
  <body>
    
    <form action="/register" method='POST' id="register" >
        @csrf
        <input type="text" name='username' placeholder="username">
        <input type="text" name="password" placeholder="password">
        <button>submit</button>
    </form>

    @if (session('message'))
        <p>{{session('message')}}</p>
    @endif

    {{-- 
    // in Ajax case , this does not work
    @if ($errors->any())
      <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
      </ul>
    @endif
     --}}

  </body>
</html>

<script type="module">

    import { initializeApp } from "https://www.gstatic.com/firebasejs/11.1.0/firebase-app.js";

    import { getMessaging,getToken} from "https://www.gstatic.com/firebasejs/11.1.0/firebase-messaging.js";


    const firebaseConfig = { 
      apiKey: "AIzaSyAUaDfzpzEH3eEhrezk1M3whb8YLDryScg",
      authDomain: "medicationswarehouse.firebaseapp.com",
      projectId: "medicationswarehouse",
      storageBucket: "medicationswarehouse.firebasestorage.app",
      messagingSenderId: "810092722594",
      appId: "1:810092722594:web:7aa67189e5637aa4815afb",
      measurementId: "G-YQ01F2NNZ2"
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
    const vapidkey="BPBZD6v3M42rXxo2uMHx1cmB34ovbUJl_a6OoihYigeBim-dqbdoH7IGSZUnSAO4r4PGJBR0BCwF44eBNGFeOik";
  
    const formRegister=document.querySelector("#register")   
    
    formRegister.addEventListener("submit",async (e)=>{
      e.preventDefault();

      if ('serviceWorker' in navigator) { 
        navigator.serviceWorker.register('/firebase-messaging-warehouseOwner-sw.js', {scope:'/'}) 
          .then(registration=>{
              console.log("service worker registered",registration)
          })
          .catch(error=>{
              console.log("service worker registration failed",error)
          })
      }

      let fcm_token=await getToken(messaging,{vapidkey:vapidkey});
      const formData = new FormData(formRegister);
      formData.append("fcm_token", fcm_token);
      let data={
        "username":formData.get("username"),
        "password":formData.get('password'),
        "fcm_token":formData.get("fcm_token")
      }
      fetch('/register',{
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

