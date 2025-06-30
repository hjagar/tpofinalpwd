<?php

namespace App\Controllers;

use App\Models\Compra;
use App\Models\CompraEstado;
use App\Models\CompraEstadoTipo;
use PhpMvc\Framework\Data\Constants\OrderDirectionType;
use PhpMvc\Framework\Http\Request;
use PhpMvc\Framework\Mail\EmailSender;
use PhpMvc\Framework\Mail\TemplateCompiler;

class SalesController
{
    private EmailSender $emailSender;

    public function __construct()
    {
        $this->emailSender = new EmailSender();
    }

    public function index()
    {
        $sales = Compra::rawQueryAll('sqlComprasAll');

        return view('admin.sales.index', compact('sales'));
    }

    public function edit(int $id)
    {
        $sale = Compra::rawQueryOne('sqlCompraOne', [$id]);
        $statuses = CompraEstadoTipo::all();

        return view('admin.sales.edit', compact('sale', 'statuses'));
    }

    public function update(Request $request, int $id)
    {
        $now = time();
        $todayData = date('Y-m-d', $now);
        $todayEmail = date('d/m/Y', $now);
        $sale = Compra::rawQueryOne('sqlCompraOne', [$id]);

        // Buscar el estado anterior para actualizar la fecha fin
        $compraEstadoAnterior = CompraEstado::where(['idcompra', $id])
            ->orderBy('fechainicio', OrderDirectionType::DESC)
            ->limit(1)
            ->first();
        $compraEstadoAnterior->fechafin = $todayData;
        $compraEstadoAnterior->update();

        // crear un nuevo estado
        $compraEstado = new CompraEstado();
        $compraEstado->idcompra = $id;
        $compraEstado->idcompraestadotipo = $request->idcompraestadotipo;
        $compraEstado->fechainicio = $todayData;
        $compraEstado->insert();

        $compraTipoEstado = CompraEstadoTipo::find($request->idcompraestadotipo);
        $estado = $compraEstado->nombre;

        // Enviar Email
        $template = "sales.templates.{$estado}";
        $templateCompiler = new TemplateCompiler($template);
        $data = [
            'nombre' => $sale->usuario,
            'estado' => $estado,
            'fecha' => $todayEmail
        ];
        $emailBody = $templateCompiler->render($data);
        $this->emailSender->send($sale->email, 'Actualización de estado de compra', $emailBody, true);
        redirect('admin.sales.index')->with('flash', 'Venta actualizada correctamente');
    }
}
