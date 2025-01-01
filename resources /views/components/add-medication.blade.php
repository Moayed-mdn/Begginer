<form method="POST" action="/medications">
    @csrf
    <input type="text" name="scientific_name" placeholder="scientific name" required value="def">
    <input type="text" name="trade_name" placeholder="trade name" required value="def">
    <input type="text" name="classification" placeholder="classification" required value="def">
    <input type="text" name="quantity" placeholder="classification" required value="300">
    <input type="text" name="manufacturer" placeholder="manufacturer" required value="def">
    <input type="text" name="price" placeholder="price" required value="59">
    <input type="date" name="expiration_date" placeholder="expiration_date" required value="2025-09-12">

    <button >submit</button>
    @php
        $errorKeys=['scientific_name','trade_name','classification',
                    'manufacturer','price','expiration_date'];
    @endphp
    
    @foreach ($errorKeys as $errorKey )
        @error($errorKey)
                <p>{{$message}}</p>
        @enderror
    @endforeach

    @if (session('status'))
            <p>{{session('status')}}</p>
    @endif



</form>
