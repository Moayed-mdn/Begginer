<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<table style="border: 1px solid">
    <tr>
        @foreach ($ordersDetails[0]->getAttributes() as $key=>$val )
            <td>{{$key}}</td>
        @endforeach
    </tr>
    @foreach ($ordersDetails as $orderDetails )
        <tr>
            @foreach ($orderDetails->getAttributes() as $key=>$val )
                <td style="border: 1px solid #0000005F">{{$val}}</td>
            @endforeach
        </tr>
    @endforeach
</table>



</body>
</html>



