<?php
require_once '../src/app/reports/fpdf/fpdf.php';
require_once '../src/app/ModelDAO/ItemPedidoDAO.php';

use Slim\Http\Request;
use Slim\Http\Response;

class ReporteLogins extends FPDF
{
    //Genera un pdf con la información de todas las facturas.
    public static function ListarPDF(Request $request, Response $response, $args)
    {
        
        $pdf = new FPDF("P","mm","A4"); // Definimos (Orientacion, Unidad de Medida, Tamaño de Hoja)
        $pdf->AddPage(); // Agregamos Una pagina
        $pdf->SetFont("Arial","B",15); // Definimos la fuente

        $pdf->Cell(62); // Nos desplazamos
        $pdf->Cell(60, 10, "Ingresos al sistema", 1, 0, 'C'); // Definimos el titulo con un bordecito
        $pdf->Ln(20); // Salto de Linea

        // Encabezados
        $encabezados = array('Fecha', 'Hora', 'Usuario', 'Rol'); // Defimos los titulos de los encabezados
        $anchosColumnas = array(30, 30, 50, 30); // Anchos de las columnas

        // Colores, ancho de línea y fuente en negrita
        $pdf->SetFillColor(0,150,0); // Color de fondo
        $pdf->SetTextColor(255); // Color de tipografia
        $pdf->SetDrawColor(0,0,0); // Color de Linea
        $pdf->SetLineWidth(.3); // Ancho de linea
        $pdf->SetFont('','B'); // Lo ponemos en negrita
    
        $pdf->Cell(25); // Nos desplazamos
        // Imprimimos los encabezados
        for($i=0;$i<count($encabezados);$i++)
        {
            $pdf->Cell($anchosColumnas[$i],7,$encabezados[$i],1,0,'C',true);
        }
        
        $pdf->Ln(); // Hacemos un salto de linea

        // Restauración de colores y fuentes
        $pdf->SetFillColor(224,235,255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        // Datos
        $fill = false;

        $listadoItemsPedidos = ItemPedidoDAO::TraerPedidos(null, null, null, null)->getMensaje();
        foreach($listadoItemsPedidos as $unItemPedido)
        {
            $pdf->Cell(25); // Nos desplazamos
            $pdf->Cell($anchosColumnas[0],6,$unItemPedido->getIdPedido(),'LR',0,'L',$fill);
            $pdf->Cell($anchosColumnas[1],6,$unItemPedido->getEstado(),'LR',0,'L',$fill);
            $pdf->Cell($anchosColumnas[2],6,number_format($unItemPedido->getIdArticulo()),'LR',0,'R',$fill);
            $pdf->Cell($anchosColumnas[3],6,number_format($unItemPedido->getIdItemPedido()),'LR',0,'R',$fill);
            $pdf->Ln();
            $fill = !$fill;
        }

        $pdf->Cell(25);
        $pdf->Cell(array_sum($anchosColumnas),100,'','T'); // Línea de cierre

        $pdf->Output("F","Ventas35.pdf",true);
        return array("Estado" => "OK", "Mensaje" => "PDF generado correctamente.");
    }

}
