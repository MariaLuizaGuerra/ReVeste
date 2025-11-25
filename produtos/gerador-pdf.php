<?php

require "../vendor/autoload.php";

// Import do namespace Dompdf
use Dompdf\Dompdf;

// instanciação e uso da classe dompdf
$dompdf = new Dompdf();

// Liga o buffer de saída
ob_start();
// Carrega o arquivo que gera o HTML
require "conteudo-pdf.php";
// Pega tudo que estava no buffer (o HTML gerado) e limpa o buffer.
$html = ob_get_clean();

// Envia o HTML para o Dompdf processar.
$dompdf->loadHtml($html);

// (Opcional) Configuração do tamanho do papel
$dompdf->setPaper('A4');

// Renderização do HTML para PDF
$dompdf->render();

$filename = 'Relatorio-produtos-' . date('dmY') . '.pdf';
// $filename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $filename);


// Saída do PDF gerado para o Browser ou download
// $dompdf->stream();
// Attachment => 1 força o download. Se fosse 0 abriria no navegador.
$dompdf->stream($filename, ['Attachment' => 1]);