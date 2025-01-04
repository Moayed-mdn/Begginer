<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    <form action="/login" method='POST'>
        @csrf

        <input type="text" placeholder="username" name='username'  value='username' >
        <input type="text" placeholder="password" name='password' value="console" required>

        <button>submit</button>

        @if(session('faild'))
            {{session('faild')}}
        @endif

    </form>



</body>
</html>
