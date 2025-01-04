<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @if(count($favoriteMedications)>0)
        <div class="medications">
            @foreach ($favoriteMedications as $favoriteMedication )
                <div class="medication">
                    @foreach ($favoriteMedication->getAttributes() as  $key=>$val )
                        <div>
                            {{$key}}: {{$val}}
                        </div>
                    @endforeach 
                    <form action="/pharmacy/add-favorite" method="POST">
                        @csrf 
                        <input type="hidden" name="medication_id" value="{{$favoriteMedication->id}}"  >
                        <button type="submit">remove from favorite</button>
                    </form>
                    @if ($errors->any())
                        @foreach ($errors->all() as $err )
                            <p>{{$err}}</p>
                        @endforeach
                    @endif
                </div>

            @endforeach
        </div>
    @else
        <h2>no favorite medications yet!</h2>
    @endif
</body>
</html>

<style>
    .medications{
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    }
    .medication{
        border:1px solid;
        padding: 10px;
        margin: 10px;
        width:250px;
        position: relative;
    }

</style>