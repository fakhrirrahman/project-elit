<?php
require('tampil_data_sunrise.php');

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "projectelit");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk mengambil data
$sql = "SELECT * FROM tampil_data_sunrise";
$result = $conn->query($sql);

// Buat objek FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

// Header tabel
$pdf->Cell(40, 10, 'ID', 1);
$pdf->Cell(60, 10, 'Nama', 1);
$pdf->Cell(50, 10, 'Shift', 1);
$pdf->Cell(40, 10, 'Tanggal', 1);
$pdf->Ln();

// Isi tabel
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(40, 10, $row['id'], 1);
        $pdf->Cell(60, 10, $row['nama'], 1);
        $pdf->Cell(50, 10, $row['shift'], 1);
        $pdf->Cell(40, 10, $row['tanggal'], 1);
        $pdf->Ln();
    }
} else {
    $pdf->Cell(190, 10, 'Tidak ada data.', 1, 0, 'C');
}

// Output PDF ke browser
$pdf->Output('D', 'tampil_data_sunrise.pdf');
?>
