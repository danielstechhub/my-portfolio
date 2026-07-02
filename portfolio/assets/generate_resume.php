<?php
$path = __DIR__ . '/resume.pdf';
$objects = [];
$objects[1] = '<< /Type /Catalog /Pages 2 0 R >>';
$objects[2] = '<< /Type /Pages /Kids [3 0 R] /Count 1 >>';
$objects[3] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>';
$objects[4] = '<< /Length 104 >>' . PHP_EOL . 'stream' . PHP_EOL . 'BT /F1 18 Tf 50 720 Td (Ayodeji Oluwafemi Daniel) Tj' . PHP_EOL . '0 -20 Td (Full Stack Developer & Platform Architect) Tj' . PHP_EOL . '0 -20 Td (Founder, Lumynex) Tj' . PHP_EOL . '0 -20 Td (hello@ayodejidaniel.dev | +234 000 000 000) Tj' . PHP_EOL . '0 -20 Td (LinkedIn: https://www.linkedin.com/in/ayodeji-oluwafemi-daniel-75254b363) Tj' . PHP_EOL . '0 -20 Td (GitHub: https://github.com/danielstechhub) Tj' . PHP_EOL . '0 -20 Td (Instagram: https://www.instagram.com/daniels_techpro.io) Tj' . PHP_EOL . '0 -20 Td (X: https://x.com/danielstechhub1) Tj' . PHP_EOL . 'ET' . PHP_EOL . 'endstream';
$objects[5] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';

$pdf = "%PDF-1.4\n";
$offsets = [0];
foreach ($objects as $number => $object) {
    $offsets[$number] = strlen($pdf);
    $pdf .= $number . " 0 obj\n" . $object . "\nendobj\n";
}

$startxref = strlen($pdf);
$pdf .= "xref\n0 6\n";
$pdf .= "0000000000 65535 f \n";
for ($i = 1; $i <= 5; $i++) {
    $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
}
$pdf .= "trailer\n<< /Size 6 /Root 1 0 R >>\nstartxref\n" . $startxref . "\n%%EOF\n";

file_put_contents($path, $pdf);
echo "Wrote $path\n";
