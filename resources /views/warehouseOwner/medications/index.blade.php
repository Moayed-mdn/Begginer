@props(['medications'=>[]])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    



<div class='search-box'>
    <input type="text" name='sr_classification' id='sr_classification' placeholder="specific classification">
    <input type="text" name='sr_name' id='sr_name' placeholder="specific name">

</div>

<div style="border: 1px solid; margin:20px 0; padding:20px;">
    <h4>add medication</h4>
    <x-add-medication :medications="$medications"/>
</div>
<div class="medications-container" id='medications-container'>

    {{-- @foreach ($medications as $medication)
        <ul>
            <li>
                <a href="/medications/{{$medication['id']}}">
                    {{$medication->id}}
                </a>
            </li>
            @foreach ($medication->getFilteredAttributes()  as $key=> $val )
                
            <li>
                {{$key }}: {{$val}}
            </li>
            @endforeach
        </ul>
    @endforeach --}}

</div>


</body>
</html>

<style>
.medications-container{
    margin: 30px auto;
    padding: 20px;
    border: 1px solid rgb(0,0,0,0.2);
}

</style>





<script>

  let inputSearchClassification=document.getElementById("sr_classification");
  let inputSearchName=document.getElementById("sr_name");


  let medicationsContainer=document.getElementById('medications-container');
  
  var filteredMedications = @json($medications->map(function ($medication) {
        return $medication->getFilteredAttributes();
    }));
   let renderMedications=(medications,term="",Key="classification")=>{

    let content=document.createElement("ul");

        medications.forEach(medication => {
            for(let key in medication){
                let li=document.createElement('li');
                
                if(key=="id"){
                    let a=document.createElement('a');
                    a.href=`/medications/${medication[key]}`;
                    a.innerText='id ' + medication[key]
                    li.appendChild(a);
                }
                else if(key==Key){
                    li.innerHTML=key;
                    let str=medication[key];
                   str= str.replace(term,`<span class='h'>${term}</span>`)
                    li.innerHTML= li.innerHTML +" "+ str;
                }
                else
                li.innerText=key + " " + medication[key];
                content.appendChild(li);
          
            }

        });

        medicationsContainer.innerHTML="";
        medicationsContainer.appendChild(content);

   }

   renderMedications(filteredMedications);


  let filterMedicationByKey=(term,key="classification")=>{

    let filteredMedications = @json($medications->map(function ($medication) {
        return $medication->getFilteredAttributes();
    }));

    let regex=RegExp(term,'i');

        filteredMedications= filteredMedications.map(medication=>{
            return  regex.test(medication[key]) ? medication :"";
        })


        renderMedications(filteredMedications,term,key);

   }




   
   inputSearchClassification.addEventListener('input',()=>{
    filterMedicationByKey(inputSearchClassification.value,"classification")

});          

    inputSearchName.addEventListener('input',()=>{
    filterMedicationByKey(inputSearchName.value,"trade_name")
});






</script>


<style>
.h{
    background: red;
}

</style>