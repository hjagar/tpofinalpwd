@extends('layouts.main')

@section('title')
Contacto
@endsection

@section('content')
<h1 class="mb-4 text-center">Contacto</h1>
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('contact.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre:</label>
                        <input type="text" id="name" name="name" class="form-control" autocomplete="off" required>
                        <div class="invalid-feedback" data-type="valueMissing">
                            Por favor ingrese su nombre.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico:</label>
                        <input type="email" id="email" name="email" class="form-control" autocomplete="off" required>
                        <div class="invalid-feedback" data-type="valueMissing">
                            Por favor ingrese un correo electrónico.
                        </div>
                        <div class="invalid-feedback" data-type="typeMismatch">
                            Por favor ingrese un correo válido.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Mensaje:</label>
                        <textarea id="message" name="message" class="form-control" rows="4" autocomplete="off" required></textarea>
                        <div class="invalid-feedback" data-type="valueMissing">
                            Por favor ingrese su mensaje.
                        </div>
                    </div>
                    <div class="d-flex justify-content-start gap-2">
                        <button type="submit" class="btn btn-primary">Enviar</button>
                        <a href="{{ route('home.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection