<?php

namespace App\Http\Controllers;

use App\Models\Detalle_venta;
use App\Models\Producto;
use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetalleVentas extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $titulo = 'Detalles de ventas';

        // 🔹 Capturar fechas del formulario (si existen)
        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_fin = $request->input('fecha_fin');

        // 🔹 Consulta base
        $query = Venta::select(
            'ventas.*',
            'users.name as nombre_usuario'
        )
        ->join('users', 'ventas.user_id', '=', 'users.id')
        ->orderBy('ventas.created_at', 'desc');

        // 🔹 Si hay filtros, aplicarlos
        if ($fecha_inicio && $fecha_fin) {
            $query->whereBetween(DB::raw('DATE(ventas.created_at)'), [$fecha_inicio, $fecha_fin]);
        } elseif ($fecha_inicio) {
            $query->whereDate('ventas.created_at', '>=', $fecha_inicio);
        } elseif ($fecha_fin) {
            $query->whereDate('ventas.created_at', '<=', $fecha_fin);
        } else {
            // 🔹 Si no se filtra, mostrar solo las ventas del día actual
            $query->whereDate('ventas.created_at', now()->toDateString());
        }

        $items = $query->get();

        // 🔹 Calcular total general filtrado
        $total_general = $items->sum('total_venta');

        return view('modules.detalles_ventas.index', compact(
            'titulo',
            'items',
            'total_general',
            'fecha_inicio',
            'fecha_fin'
        ));
    }

    /**
     * Mostrar detalle de una venta específica.
     */
    public function vista_detalle($id)
    {
        $titulo = 'Detalle de venta';
        $venta = Venta::select(
            'ventas.*',
            'users.name as nombre_usuario'
        )
        ->join('users', 'ventas.user_id', '=', 'users.id')
        ->where('ventas.id', $id)
        ->firstOrFail();

        $detalles = Detalle_venta::select(
            'detalle_venta.*',
            'productos.nombre as nombre_producto'
        )
        ->join('productos', 'detalle_venta.producto_id', '=', 'productos.id')
        ->where('venta_id', $id)
        ->get();

        return view('modules.detalles_ventas.detalle_venta', compact('titulo', 'venta', 'detalles'));
    }

    /**
     * Revocar (eliminar) una venta y devolver stock.
     */
    public function revocar($id)
    {
        DB::beginTransaction();
        try {
            $detalles = Detalle_venta::select('producto_id', 'cantidad')
                ->where('venta_id', $id)
                ->get();

            // 🔹 Devolver stock
            foreach ($detalles as $detalle) {
                Producto::where('id', $detalle->producto_id)
                    ->increment('cantidad', $detalle->cantidad);
            }

            // 🔹 Eliminar productos vendidos y la venta
            Detalle_venta::where('venta_id', $id)->delete();
            Venta::where('id', $id)->delete();

            DB::commit();
            return to_route('detalle-venta')->with('success', 'Revocación de venta exitosa!!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return to_route('detalle-venta')->with('error', 'No se pudo revocar la venta!!');
        }
    }

    /**
     * Generar ticket simple (sin PDF).
     */
    public function generarTicket($id)
    {
        $venta = Venta::select(
            'ventas.*',
            'users.name as nombre_usuario'
        )
        ->join('users', 'ventas.user_id', '=', 'users.id')
        ->where('ventas.id', $id)
        ->firstOrFail();

        $detalles = Detalle_venta::select(
            'detalle_venta.*',
            'productos.nombre as nombre_producto'
        )
        ->join('productos', 'detalle_venta.producto_id', '=', 'productos.id')
        ->where('venta_id', $id)
        ->get();

        // 🔹 En lugar de generar PDF, mostramos vista limpia
        return view('modules.detalles_ventas.ticket_simple', compact('venta', 'detalles'));
    }
}
