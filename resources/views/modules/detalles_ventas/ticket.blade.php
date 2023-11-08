@extends('layouts.main')

@section('titulo', 'Ticket de Venta')

@section('contenido')
<main id="main" class="main">
    <section class="section">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <!-- Ticket HTML -->
                        <div id="ticket-impresion" class="ticket">
                            <div class="titulo">MERKEO DIGITAL</div>
                            <div class="subtitulo">TICKET DE COMPRA</div>

                            <p><strong>Cajero:</strong> {{ $venta->nombre_usuario ?? 'N/A' }}</p>
                            <p><strong>Fecha:</strong> {{ $venta->created_at ?? 'N/A' }}</p>

                            <table>
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cant.</th>
                                        <th>Precio</th>
                                        <th>Subt.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detalles ?? [] as $detalle)
                                    <tr>
                                        <td>{{ $detalle->nombre_producto }}</td>
                                        <td>{{ $detalle->cantidad }}</td>
                                        <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                                        <td>${{ number_format($detalle->sub_total, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <p class="total">TOTAL: ${{ number_format($venta->total_venta ?? 0, 2) }}</p>
                            <p class="gracias">¡Gracias por su compra!<br>Vuelva pronto 😊</p>
                        </div>

                        <script>
                        // Se elimina el botón de impresión, pero dejamos la función por si deseas llamarla desde fuera
                        function imprimirTicket() {
                            const contenido = document.getElementById('ticket-impresion').outerHTML;
                            const estilos = `
                                <style>
                                    body { margin: 0; font-family: Arial, monospace; font-size: 12px; }
                                    .ticket { width: 270px; margin: 0 auto; }
                                    .titulo { text-align: center; font-weight: bold; font-size: 18px; margin-bottom: 2px; }
                                    .subtitulo { text-align: center; font-size: 14px; font-weight: bold; margin-bottom: 8px; text-decoration: underline; }
                                    table { width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 5px; }
                                    th, td { padding: 2px 0; border-bottom: 1px dashed #000; }
                                    th:nth-child(2), td:nth-child(2) { text-align: center; }
                                    th:nth-child(3), td:nth-child(3), th:nth-child(4), td:nth-child(4) { text-align: right; }
                                    .total { text-align: right; font-weight: bold; margin-top: 5px; font-size: 14px; }
                                    .gracias { text-align: center; margin-top: 5px; font-size: 12px; font-style: italic; }
                                    @page { size: 72mm auto; margin: 0; }
                                </style>
                            `;
                            const ventana = window.open('', '_blank', 'width=280,height=600');
                            ventana.document.write(`<html><head><title>Ticket</title>${estilos}</head><body>${contenido}</body></html>`);
                            ventana.document.close();
                            ventana.onload = () => {
                                ventana.print();
                                ventana.close();
                            };
                        }
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
