const express = require("express");
const bodyParser = require("body-parser");
const { printer: ThermalPrinter, types: PrinterTypes } = require("node-thermal-printer");

const app = express();
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Ruta para imprimir ticket
app.post("/imprimir-ticket", async (req, res) => {
  try {
    const { venta, detalles } = req.body;

    let printer = new ThermalPrinter({
      type: PrinterTypes.EPSON,
      interface: "printer:POS-76C", // Cambia al nombre exacto de tu impresora
      options: { timeout: 5000 },
    });

    const conectado = await printer.isPrinterConnected();
    if (!conectado) {
      return res.status(500).json({ success: false, error: "Impresora no conectada" });
    }

    // 🔹 Encabezado
    printer.alignCenter();
    printer.bold(true);
    printer.println("MERKEO DIGITAL");
    printer.println("TICKET DE COMPRA");
    printer.bold(false);
    printer.drawLine();

    // 🔹 Datos de venta
    printer.alignLeft();
    printer.println(`Cajero: ${venta.nombre_usuario}`);
    printer.println(`Fecha: ${venta.created_at}`);
    printer.drawLine();

    // 🔹 Detalles
    detalles.forEach(item => {
      printer.tableCustom([
        { text: item.nombre_producto, align: "LEFT", width: 0.4 },
        { text: item.cantidad.toString(), align: "CENTER", width: 0.2 },
        { text: "$" + item.sub_total.toFixed(2), align: "RIGHT", width: 0.4 },
      ]);
    });

    printer.drawLine();

    // 🔹 Total
    printer.bold(true);
    printer.alignRight();
    printer.println(`TOTAL: $${venta.total_venta.toFixed(2)}`);
    printer.bold(false);

    printer.alignCenter();
    printer.println("¡Gracias por su compra!");
    printer.cut();

    await printer.execute();

    res.json({ success: true, message: "Ticket impreso correctamente" });
  } catch (error) {
    res.status(500).json({ success: false, error: error.message });
  }
});

app.listen(3000, () => console.log("Servidor Node.js corriendo en puerto 3000"));
