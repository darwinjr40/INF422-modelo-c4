{{-- @extends('layouts.app')

@section('content')
    @livewire('home')
@endsection --}}


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{-- <link rel="stylesheet"  type="text/css" href="{{ asset('css/layaout1.css') }}"> --}}
    <style>
        body {
            /* background: url(../../public/img/fondo.jpg); */
            background: url({{asset('img/fondo.jpg')}});
            /* background: url(https://s3service12.s3.amazonaws.com/pattern.png); */
            margin: 0;
            padding: 0;
            /* background-image: url(../../../public/img/fondo.jpg); */
            /* background-repeat: no-repeat; */
            /* background-position: center center; */
            height: 120%;
            /* background-size: cover; */
        }
    </style>
    @livewireStyles

</head>

<body>
    @livewire('home')

    @livewireScripts

</body>

</html>
