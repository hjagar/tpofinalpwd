@extends('layouts.admin')
@section('title')
  Editar Venta
@endsection
@section('content')
  <h1 class="mb-4 text-center">Editar Venta</h1>
  <div class="row justify-content-center">
    <div class="col-4 col-md-4 col-lg-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <form action="{{ route('admin.sales.update', [$sale->idcompra]) }}" method="POST">
            @csrf
            <div class="mb-3">
              <label class="form-label">Detalle</label>
              <div class="bg-light rounded p-3 mb-2 border">
                <div><span class="fw-bold">Id:</span> <span class="text-muted">{{ $sale->idcompra }}</span></div>
                <div><span class="fw-bold">Usuario:</span> <span>{{ $sale->usuario }}</span></div>
                <div><span class="fw-bold">Email:</span> <span>{{ $sale->email }}</span></div>
                <div><span class="fw-bold">Productos:</span> <span>{{ $sale->productos }}</span></div>
                <div><span class="fw-bold">Total:</span> <span class="text-success">${{ number_format($sale->total, 2, ',', '.') }}</span></div>
              </div>
            </div>
            <div class="mb-3">
              <label for="status" class="form-label">Estado</label>
              <select name="status" id="status" class="form-select" required>
                @foreach($statuses as $status)
                  <option value="{{ $status->idcompraestadotipo }}" @selected($sale->idcompraestadotipo == $status->idcompraestadotipo)>
                    {{ ucfirst($status->nombre) }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="d-flex justify-content-start gap-2">
              <button type="submit" class="btn btn-primary">Actualizar</button>
              <a href="{{ route('admin.sales.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection