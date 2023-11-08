<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ticket de Compra</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, monospace;
      font-size: 12px;
    }
    .ticket {
      width: 270px; /* aprox 80mm */
      margin: 0 auto;
      padding: 10px 0;
    }
    .titulo {
      text-align: center;
      font-weight: bold;
      font-size: 18px;
      margin-bottom: 2px;
    }
    .subtitulo {
      text-align: center;
      font-size: 14px;
      font-weight: bold;
      margin-bottom: 8px;
      text-decoration: underline;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 12px;
      margin-top: 5px;
    }
    th, td {
      padding: 2px 0;
      border-bottom: 1px dashed #000;
    }
    th:nth-child(2), td:nth-child(2) { text-align: center; }
    th:nth-child(3), td:nth-child(3),
    th:nth-child(4), td:nth-child(4) { text-align: right; }
    .total {
      text-align: right;
      font-weight: bold;
      margin-top: 5px;
      font-size: 14px;
    }
    .gracias {
      text-align: center;
      margin-top: 5px;
      font-size: 12px;
      font-style: italic;
    }
    @page {
      size: 72mm auto;
      margin: 0;
    }
  </style>
</head>
<body>
  <div class="ticket">
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
    window.onload = () => {
      window.print();
    };
  </script>
</body>
</html>
