<html>
    <head>
        <title>Inicio</title>
    </head>
    <body>
        <h1>Hello World</h1>
        <h2>{{ $variable }}</h2>

        @foreach($array as $item)
        {{ $item }}
        @endforeach

        @foreach($arrayOfObjects as $object)
        {{ $object->prop1 }}
        @endforeach
    </body>
</html>