<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h2>Dashbord</h2>
  
    <form action="/logout" method="POST">
        @csrf
        <button>logout</button>
    </form>

    <a href="/medications">medication</a>
    <a href="/orders">orders</a>
    
   

    
    {{--    <x-create></x-create> --}}
    {{-- <x-warehouseOwner.medications.index :medications="$medications"/> --}}
</body>
</html>
