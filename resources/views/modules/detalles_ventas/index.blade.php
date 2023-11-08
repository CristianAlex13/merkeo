@extends('layouts.main') 

@section('titulo', $titulo)

@section('contenido')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Consulta de ventas hechas</h1>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Revisar Ventas Existentes</h5>

            <!-- 🔹 FILTRO POR FECHAS -->
            <form method="GET" action="{{ route('detalle-venta') }}" class="row g-3 mb-3">
              <div class="col-md-4">
                <label for="fecha_inicio" class="form-label">Desde:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" 
                       value="{{ $fecha_inicio ?? '' }}">
              </div>
              <div class="col-md-4">
                <label for="fecha_fin" class="form-label">Hasta:</label>
                <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" 
                       value="{{ $fecha_fin ?? '' }}">
              </div>
              <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
              </div>
            </form>

            <table class="table datatable">
              <thead>
                <tr>
                  <th class="text-center">Total Vendido</th>
                  <th class="text-center">Fecha venta</th>
                  <th class="text-center">Usuario</th>
                  <th class="text-center">Ver Detalle</th>
                  <th class="text-center">Imprimir Ticket</th>
                  <th class="text-center">Revocar venta</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($items as $item)
                <tr class="text-center">
                  <td class="text-center">${{ $item->total_venta }}</td>
                  <td class="text-center">{{ $item->created_at }}</td>
                  <td class="text-center">{{ $item->nombre_usuario }}</td>
                  <td class="text-center">
                    <a href="{{ route('detalle.vista.detalle', $item->id) }}" class="btn btn-info">Detalle</a>
                  </td>
                  <td class="text-center">
                    <a href="{{ route('detalle-venta.ticket', $item->id) }}" target="_blank" class="btn btn-sm btn-primary">
                      <i class="bi bi-printer"></i> Ticket
                    </a>
                  </td>
                  <td class="text-center">
                    <form action="{{ route('detalle.revocar', $item->id) }}" method="POST" 
                      onsubmit="return confirm('¿¿Está seguro de revocar la venta??')">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-danger">Revocar</button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>

            <!-- 🔹 TOTAL GENERAL FILTRADO -->
            <div class="text-end mt-3">
              <h5><strong>Total general vendido:</strong> 
                ${{ number_format($total_general, 0, ',', '.') }}
              </h5>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection
