<?php

namespace App\Controllers;

use App\Bussiness\Cart;
use App\Bussiness\Product;
use App\Models\Compra;
use App\Models\CompraEstado;
use App\Models\CompraEstadoTipo;
use App\Models\CompraItem;
use App\Models\Producto;
use PhpMvc\Framework\Http\JsonResult;
use PhpMvc\Framework\Http\Request;
use PhpMvc\Framework\Mail\EmailSender;
use PhpMvc\Framework\Mail\TemplateCompiler;

class CartController
{
    private EmailSender $emailSender;

    public function __construct()
    {
        $this->emailSender = new EmailSender();
    }

    public function index()
    {
        $cart = $this->getCart();
        $productIds = $cart->getProductIds();
        $productos = Producto::where(['idproducto' => $productIds])->get();

        return view('cart.index', compact('productos'));
    }

    public function indexAjax()
    {
        return view('cart.index-ajax');
    }

    public function indexSsr()
    {
        $cart = $this->getCart();
        $productIds = $cart->getProductIds();
        $productos = Producto::where(['idproducto' => $productIds])->get();
        $products = [];
        $itemCount = 0;
        $total = 0;

        foreach ($productos as $producto) {
            $quantity = $cart->itemCount($producto->idproducto);
            $product = new Product($producto->idproducto, $producto->nombre, $producto->precio, $quantity);
            $itemCount += $quantity;
            $total += $product->total();
            $products[] = $product;
        }

        $data = json_encode(['products' => $products, 'itemCount' => $itemCount, 'total' => $total]);

        return view('cart.index-ssr', compact('data'));
    }

    public function cartProducts()
    {
        $cart = $this->getCart();
        $productIds = $cart->getProductIds();
        $products = [];
        $itemCount = 0;
        $total = 0;

        if(!empty($productIds)) {
            $productos = Producto::where(['idproducto' => $productIds])->get();

            foreach ($productos as $producto) {
                $quantity = $cart->itemCount($producto->idproducto);
                $product = new Product($producto->idproducto, $producto->nombre, $producto->precio, $quantity);
                $itemCount += $quantity;
                $total += $product->total();
                $products[] = $product;
            }           
        }

        return json(compact('products', 'itemCount', 'total'));
    }

    public function add($id)
    {
        $producto = Producto::find($id);
        $returnValue = new JsonResult();

        if ($producto !== null) {
            $stock = intval($producto->stock);

            if ($stock) {
                $this->addToCart($id);
                $producto->stock = $stock - 1;
                $producto->save();
                $returnValue->message = "Producto {$producto->nombre} agregado con exito";
            } else {
                $returnValue->success = false;
                $returnValue->message = "Producto {$producto->nombre} no cuenta con stock";
            }
        } else {
            $returnValue->success = false;
            $returnValue->message = "Producto no encontrado";
        }

        return json($returnValue);
    }

    public function remove(Request $request)
    {
        $id = $request->id;
        $producto = Producto::find($id);
        $returnValue = new JsonResult();

        if ($producto !== null) {
            $stockRemoved = $this->removeFromCart($id);
            $stock = intval($producto->stock);
            $producto->stock = $stock + $stockRemoved;
            $producto->save();
            $returnValue->message = "Producto {$producto->nombre} removido con exito";
            $returnValue->countRemoved = $stockRemoved;
        } else {
            $returnValue->success = false;
            $returnValue->message = "Producto no encontrado";
        }

        return json($returnValue);
    }

    public function removeOne(Request $request)
    {
        $id = $request->id;
        $producto = Producto::find($id);
        $returnValue = new JsonResult();

        if ($producto !== null) {
            $stockRemoved = $this->removeOneFromCart($id);
            $stock = intval($producto->stock);
            $producto->stock = $stock + $stockRemoved;
            $producto->save();
            $returnValue->message = "Producto {$producto->nombre} removido con exito";
        } else {
            $returnValue->success = false;
            $returnValue->message = "Producto no encontrado";
        }

        return json($returnValue);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $producto = Producto::find($id);
        $returnValue = new JsonResult();

        if ($producto !== null) {
            $quantity = $request->quantity;
            $currentCount =  $this->updateOne($id, $quantity);
            $stock = intval($producto->stock);
            $producto->stock = $stock + $currentCount;
            $producto->save();
            $returnValue->message = "Producto {$producto->nombre} actualizado con exito";
        } else {
            $returnValue->success = false;
            $returnValue->message = "Producto no encontrado";
        }

        return json($returnValue);
    }

    public function buy(Request $request)
    {
        $now = time();
        $todayData = date('Y-m-d H:i:s', $now);
        $todayEmail = date('d/m/Y', $now);
        // Guardo la compra
        $compra = new Compra();
        $user = auth()->user();
        $compra->idusuario = $user->idusuario;
        $compra->fecha = $todayData;
        $compra->save();
        $idcompra = $compra->idcompra;

        // Guardo los items
        $items = $request->items;
        foreach ($items as $item) {
            $compraItem = new CompraItem();
            $compraItem->idproducto = $item->id;
            $compraItem->idcompra = $idcompra;
            $compraItem->cantidad = $item->quantity;
            $compraItem->save();
        }
        // Guardo el estado inicial
        $estado = 'iniciada';
        $compraEtadoTipo = CompraEstadoTipo::where(['nombre' => $estado])->first();

        $compraEstado = new CompraEstado();
        $compraEstado->idcompra = $idcompra;
        $compraEstado->idcompraestadotipo = $compraEtadoTipo->idcompraestadotipo;
        $compraEstado->fechainicio = $todayData;
        $compraEstado->save();

        // Envío email
        $template = "admin.sales.templates.{$estado}";
        $templateCompiler = new TemplateCompiler($template);
        $data = [
            'nombre' => $user->nombre,
            'estado' => $estado,
            'fecha' => $todayEmail,
            'appName' => env('APP_NAME')
        ];
        $emailBody = $templateCompiler->render($data);
        $this->emailSender->send($user->email, 'Actualización de estado de compra', $emailBody, true);

        // Limpio el carrito
        $this->clearCart();
        // Devolver JsonResult
        return json("Compra realizada con exito");
    }

    private function getCart(): Cart
    {
        return Cart::instance();
    }

    private function addToCart($productId)
    {
        $this->getCart()->add($productId);
    }

    private function removeFromCart($productId): int
    {
        return $this->getCart()->remove($productId);
    }

    private function removeOneFromCart($productId): int
    {
        return $this->getCart()->removeOne($productId);
    }

    private function updateOne($productId, $cantidad)
    {
        return $this->getCart()->update($productId, $cantidad);
    }

    private function clearCart()
    {
        $this->getCart()->clear();
    }
}
