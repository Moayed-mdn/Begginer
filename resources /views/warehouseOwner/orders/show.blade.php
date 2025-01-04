<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @foreach ($order->getAttributes() as $key=>$val )
        @if ($key==="status")
        <form action="/orders/{{$order->id}}/status" method="POST">
                @csrf
                @method("PATCH")
            {{$key}}:
            <select name="status" id="status" > 
                <option value="in_preparing" {{$order->status==="in_preparing"?"selected":""}}>in preparing</option>
                <option value="sent" {{$order->status==="sent"?"selected":""}}>sent</option>
                <option value="receive"{{$order->status==="receive"?"selected":""}}>receive</option>
            </select>
            <button>submit</button>
        </form>
        @elseif ($key==="is_paid")
            <form action="/orders/{{$order->id}}/paid" method="POST">
                    @csrf
                    @method("PATCH")
                {{$key}}:
                <select name="is_paid" id="is_paid">
                    <option value="1" {{$order->is_paid?"selected" :""}}>yes</option>
                    <option value="0" {{$order->is_paid?"":"selected"}} >No</option>
                </select>
                <button >submit</button>
            </form>
            @else
            <p>{{$key}}: {{$val}}</p>
        @endif
        @endforeach 
        <p><a href="/orders/{{$order->id}}/details">Details</a></p>
        @if($errors->any())
            @foreach ($errors->all() as $val)
                <p>{{$val}}</p>
            @endforeach
        @endif
        @if (session('qunatity_error'))
            {{session('qunatity_error')}}
        @endif
        @if (session('error'))
        {{session('error')}}
    @endif

    </body>
</html>


<script>
    response=@json(session("message")??"");
    if(response)
        alert(response)



</script>