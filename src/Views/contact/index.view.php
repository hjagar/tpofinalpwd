@extends('layouts.main')

@section('title')
    Contacto
@endsection

@section('javascript')
    @include('partials.form-validation')
@endsection

@section('content')
    <h1 class="mb-4">Contacto</h1>
    <form action="{{ route('contact.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nombre:</label>
            <input type="text" id="name" name="name" class="form-control" required>
            <div class="invalid-feedback">
                Por favor ingrese su nombre.
            </div>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico:</label>
            <input type="email" id="email" name="email" class="form-control" required>
            <div class="invalid-feedback">
                Por favor ingrese un correo electrónico válido.
            </div>
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Mensaje:</label>
            <textarea id="message" name="message" class="form-control" rows="4" required></textarea>
            <div class="invalid-feedback">
                Por favor ingrese su mensaje.
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
@endsection