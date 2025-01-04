<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    <table>
        @foreach ($orders as $order )
            <tr>
                @foreach ($order->getAttributes() as $key=>$val )
                    @if ($key=="id")
                        <td><a href="/pharmacy/orders/{{$val}}">{{$key}}</a>: {{$val}}</td>                
                    @else
                        <td>{{$key}}: {{$val}}</td>                
                    @endif    
                @endforeach
            </tr>
        @endforeach

      
    </table>


</body>
</html>