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
            <div>{{$key}}: {{$val}}</div>
        @endforeach

            <h2>Details</h2>

        @foreach ($orderDetails as $order)
            <div style="border: 1px solid; padding: 10px">
                @foreach ($order->getAttributes() as $key => $value)
                <div> {{ $key }}: {{ $value }}</div>
                @endforeach
            </div>
        @endforeach
    
    
</body>
</html>