@extends('layouts.main')

@section('title')
    Contacto
@endsection

@section('content')
    <h1>Contacto</h1>
    <form action="{{ route('contact.store') }}" method="POST">
        @csrf
        <div>
            <label for="name">Nombre:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div>
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="message">Mensaje:</label>
            <textarea id="message" name="message" required></textarea>
        </div>
        <button type="submit">Enviar</button>
    </form>    
@endsection