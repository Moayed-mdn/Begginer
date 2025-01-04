<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Document</title>
</head>
<body>

    <div>
        <h1>Medications</h1>
        <a href="/pharmacy/orders" >go to my orders</a>
        <a href="/pharmacy/favorites">go to my favorites</a>
    </div>
    <div id="error-container"></div>

    @if ($medicationsByClassification->count()>0)
    <div id="pharmacy-orders">
        <form id="orders-form" method="post" action="/pharmacy/orders">
            <input type="text" name="name" value="def">
            <button type="submit">submit</button>
        </form>
        </div>
    @foreach ($medicationsByClassification as $classification=>$medications )
    <strong> {{$classification}}</strong>
    <div class="medications">
            @foreach ($medications as $medication )
                <div class='medication' data-id="{{$medication->id}}">
                        <input type="number" max="{{$medication->quantity}}" name="quantity"   min=1 class="order-quantity" id="q-{{$medication->id}}">
                    @foreach ($medication->getFilteredAttributes() as  $key=>$val)
                        @if ($key!='id')
                            <li>{{$key}} : {{$val}}</li>            
                        @endif
                    @endforeach
                    <div>
                        <form action="/pharmacy/add-favorite" method="POST">
                            @csrf
                            <input type="text" hidden name="medication_id" value="{{$medication->id}}">
                            <button class="{{$medication->checkFav(Auth::guard("pharmacy")->user()->id)}}">favorite</button>
                        </form>
                    </div>
                </div>
            @endforeach
    </div>
    @endforeach
    @else
        <p>no medications found</p>

    @endif
    
    



</body>
</html>


<style>
    .exists{
        background: gold;
    }

    .medications{
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    }
    .medication{
        border:1px solid;
        padding: 10px;
        margin: 10px;
        width:250px;
        height: 185px;
        position: relative;
    }
    .medication.selected::before{
        content: "";
        position: absolute;
        width: 20px;
        height: 20px;
        border: 1px rgb(0,0,0,0.3);
        border-radius: 50%;
        background: orange;
        right:5px;
        top: 5px;
    }
    .medication li{
        list-style: none;
    }
    .order-quantity{
        display:none;
        width: 50px;

    }
    #pharmacy-orders{
        position: fixed;
        width: 100px;
        top: 30px;
        right: 30px;

    }
</style>

<script>
    /// alert for creation
    message=@json(@session('message')??"");
    if(message)
        alert(message)


    medicationsByClassification= @json($medicationsByClassification);

    /// functions
    getMedicationAndQuantityById=(id)=>{
        keys=Object.keys(medicationsByClassification);
       
        for(let i=0;i<keys.length;++i){
            medication=  medicationsByClassification[keys[i]].find(e=>e.id ==+id )
            if(medication){
                inputQuantity=document.getElementById(`q-${medication.id}`);
                quantity=inputQuantity.value;
                if(quantity>0)
                    return {
                        "medicationId":medication.id,
                        "quantity":quantity
                    }          
                  console.log(inputQuantity)  
                alert(`Enter the  required quantity for ${medication.trade_name} .`);
                inputQuantity.focus();
                return null;    
            }
        }
            

    }
    setAllUnselected=()=>{
        
        document.querySelectorAll('.medication').forEach(e=>{
            medicationId=e.dataset.id;
            document.getElementById(`q-${medicationId}`).style.display="none";
            e.classList.remove("selected")
        })


    }
    setSelectedOperations=(element)=>{
                element.classList.add('selected');
                inputQuantity.style.display="block";
                inputQuantity.focus();
    }
    setUnselectedOperations=(element,inputQuantity)=>{
                inputQuantity.style.display="none";
                element.classList.remove('selected');
    }
    /// end functions      
    medicationContainers=document.querySelectorAll('.medication');

    medicationContainers.forEach(element => {
        element.addEventListener('click',e=>{
            medicationId=e.currentTarget.dataset.id;

            

            if(e.target.tagName!="INPUT"&&e.target.tagName!='BUTTON'){
                inputQuantity=document.querySelector(`#q-${medicationId}`);

            if(e.currentTarget.classList.contains("selected")){
                setUnselectedOperations(e.currentTarget,inputQuantity);

            }
            else{   
                setSelectedOperations(e.currentTarget);
            }
            // toggleMedicationByIdToOrdres(medicationId,);
            }
        })
    });

    document.querySelectorAll('input.order-quantity').forEach(input=>{
        input.addEventListener("keydown",e=>{
            maxVal=e.target.attributes.max.value;
            keyVal=e.key;
            console.log(maxVal);
            willEnterVal=e.target.value + keyVal;
            regNumber=/^\d$/;
            if(!regNumber.test(keyVal)&&!['Backspace',"ArrowUp","ArrowDown",'ArrowLeft',"ArrowRight"].includes(keyVal)){
                e.preventDefault();
                return;
            }
            if(regNumber.test(keyVal)&&Number(willEnterVal)>Number(maxVal)){
                //add  an error class
                e.preventDefault();
                return;
            }
            


        })


    })

    document.getElementById('orders-form').addEventListener('submit', function(event) {
        event.preventDefault(); 
        orders=[];
        medicationSelected= document.querySelectorAll(".medication.selected");
        for(let i=0;i<medicationSelected.length;++i){
            
            orderDOM=medicationSelected[i];

            const medicationAndQuantity=getMedicationAndQuantityById(orderDOM.dataset.id);
            if(!medicationAndQuantity){
                console.log("medication's id is not present");
                return ;

            }
            orders.push(medicationAndQuantity)
        }
        

        if (orders.length === 0) {
            alert("No medications selected.");
            return; 
        }

        fetch('/pharmacy/orders', {
            method: 'POST',
            body: JSON.stringify(orders),
            headers: {
                'X-CSRF-TOKEN': @json(csrf_token()),// OR form Meta if you handle it  :  document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            console.log(response.status)
            if(!response.ok) {
            return  response.json().then(data=>{
                alert(data.message)
                throw new Error("Error, status " + data.message);// go to catch
            })
            }
            return response.json();
        })
        .then(data=>{
            console.log("data: ",data);
            setAllUnselected();
            orders=[]
            alert(data.message);    

        })
        .catch(e=>{
            console.log(e);
        })
    });
</script>   

<script type="module">

    import { initializeApp } from "https://www.gstatic.com/firebasejs/11.1.0/firebase-app.js";
    import { getMessaging, onMessage } from "https://www.gstatic.com/firebasejs/11.1.0/firebase-messaging.js";

    const firebaseConfig = {
    apiKey: "AIzaSyAUaDfzpzEH3eEhrezk1M3whb8YLDryScg",
    authDomain: "medicationswarehouse.firebaseapp.com",
    projectId: "medicationswarehouse",
    storageBucket: "medicationswarehouse.firebasestorage.app",
    messagingSenderId: "810092722594",
    appId: "1:810092722594:web:a916d9bc7e2eec32815afb",
    measurementId: "G-HY4W4GRG4Z"
    };

    navigator.serviceWorker.register("firebase-messaging-pharmacy-sw.js")
        .then((registration)=>{console.log("service worker registered",registration)})
        .catch((e)=>{console.log("service worker registration failed",e)});
    
    const app=initializeApp(firebaseConfig);
    const messaging=getMessaging(app);

    onMessage(messaging,(payload)=>{ 
        console.log("Message received in foreground ",payload)
        if(Notification.permission=="granted")
            new Notification("Pharmacy (foreground)",{body:"you have a new change in your orders"});
        else
            console.log("Notification denied");
    }) 

    Notification.requestPermission();
    
</script>