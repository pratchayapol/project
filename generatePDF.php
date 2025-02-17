<?php
require_once __DIR__ . '/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $month = $_POST['month'] ?? null;
    $year = $_POST['year'] ?? null;

    if (!empty($month) && !empty($year)) {
        try {
            ob_start(); // เริ่มบัฟเฟอร์ Output

            $pdf = new \TCPDF();
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Your Name');
            $pdf->SetTitle("Report for $month / $year");
            $pdf->SetMargins(10, 10, 10);
            $pdf->AddPage();

            $html = "<h1>Report for $month / $year</h1><p>This is a sample report generated using TCPDF.</p>";
            $pdf->writeHTML($html, true, false, true, false, '');

            $pdf->Output("Report_$month_$year.pdf", 'D');

            ob_end_clean(); // ล้างบัฟเฟอร์ Output
        } catch (Exception $e) {
            ob_end_clean();
            echo "Error generating PDF: " . $e->getMessage();
        }
    } else {
        echo "Please provide valid month and year.";
    }
}
?>
