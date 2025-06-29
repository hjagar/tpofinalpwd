@extends('layouts.admin')
@section('title')
Editar Venta
@endsection
@section('content')
<h1 class="mb-4 text-center">Editar Venta</h1>
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">

                <form action="{{ route('admin.sales.update', [$sale->id]) }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Producto</label>
                        <select name="product_id" id="product_id" class="form-select" required>
                            <option value="" disabled selected>Seleccione un producto</option>
                            @foreach ($products as $product)
                            <option value="{{ $product->id }}" {{ $sale->product_id == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} - ${{ number_format($product->price, 2) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Cliente</label>
                        <select name="customer_id" id="customer_id" class="form-select" required>
                            <option value="" disabled selected>Seleccione un cliente</option>
                            @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" {{ $sale->customer_id == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} - {{ $customer->email }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Cantidad</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="{{ $sale->quantity }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Estado</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="Pendiente" {{ $sale->status == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="Completada" {{ $sale->status == 'Completada' ? 'selected' : '' }}>Completada</option>
                            <option value="Cancelada" {{ $sale->status == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="total" class="form-label">Total</label>
                        <input type="number" name="total" id="total" class="form-control" value="{{ $sale->total }}" step="0.01" required>
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