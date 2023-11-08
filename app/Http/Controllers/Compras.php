<?php 

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Compras extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titulo = 'Compras';
        $items = Compra::select(
            'compras.*',
            'users.name as nombre_usuario',
            'productos.nombre as nombre_producto'
        )
        ->join('users', 'compras.user_id', '=', 'users.id')
        ->join('productos', 'compras.producto_id', '=', 'productos.id')
        ->get();

        return view('modules.compras.index', compact('titulo', 'items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $titulo = 'Comprar productos';
        $item = Producto::findOrFail($id);
        return view('modules.compras.create', compact('titulo', 'item'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    try {
        $compra = new Compra();
        $compra->user_id = Auth::user()->id;
        $compra->producto_id = $request->id;
        $compra->cantidad = $request->cantidad;
        $compra->precio_compra = $request->precio_compra;

        if ($compra->save()) {
            $producto = Producto::find($request->id);
            $producto->cantidad = $producto->cantidad + $request->cantidad;
            $producto->precio_compra = $request->precio_compra;
            $producto->precio_venta = $request->precio_venta; // ✅ aquí sí lo actualizas correctamente
            $producto->save();
        }

        return to_route('productos')->with('success', 'Compra registrada correctamente y precio de venta actualizado!');
    } catch (\Throwable $th) {
        return to_route('compras')->with('error', 'Fallo al registrar la compra: ' . $th->getMessage());
    }
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $titulo = 'Detalle de compra';
        $items = Compra::select(
            'compras.*',
            'users.name as nombre_usuario',
            'productos.nombre as nombre_producto'
        )
        ->join('users', 'compras.user_id', '=', 'users.id')
        ->join('productos', 'compras.producto_id', '=', 'productos.id')
        ->where('compras.id', $id)
        ->first();

        return view('modules.compras.show', compact('titulo', 'items'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $titulo = 'Editar compra';
        $item = Compra::select(
            'compras.*',
            'users.name as nombre_usuario',
            'productos.nombre as nombre_producto'
        )
        ->join('users', 'compras.user_id', '=', 'users.id')
        ->join('productos', 'compras.producto_id', '=', 'productos.id')
        ->where('compras.id', $id)
        ->first();

        return view('modules.compras.edit', compact('titulo', 'item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $compra = Compra::findOrFail($id);
            $cantidad_anterior = $compra->cantidad;

            $compra->cantidad = $request->cantidad;
            $compra->precio_compra = $request->precio_compra;

            if ($compra->save()) {
                $producto = Producto::find($request->producto_id);
                $producto->cantidad = ($producto->cantidad - $cantidad_anterior) + $request->cantidad;
                $producto->save();
            }

            return to_route('compras')->with('success', 'Compra actualizada exitosamente.');
        } catch (\Throwable $th) {
            return to_route('compras')->with('error', 'Error al actualizar la compra: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        try {
            $compra = Compra::findOrFail($id);
            $cantidad_compra = $compra->cantidad;

            if ($compra->delete()) {
                $producto = Producto::find($request->producto_id);
                $producto->cantidad -= $cantidad_compra;
                $producto->save();
            }

           return to_route('productos')->with('success', 'Compra registrada exitosamente!!');

        } catch (\Throwable $th) {
            return to_route('compras')->with('error', 'Error al eliminar la compra: ' . $th->getMessage());
        }
    }
}
