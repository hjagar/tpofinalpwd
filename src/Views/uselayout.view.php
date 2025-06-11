@extends('layouts.main')

@section('title')
Hola mundo de los layouts
@endsection

@section('content')
<h1>Hello World</h1>
<h2>{{ $variable }}</h2>
<h2>{{ $variable2 }}</h2>
<p>{!! $variable3 !!}</p>

@if ($variable == "hola")
<p>hola</p>
@endif

@foreach($array as $i)
<p>{{ $i }}
@endforeach
@endsection