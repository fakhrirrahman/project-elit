<?php
// Konfigurasi database
$dsn = 'mysql:host=localhost;dbname=projectelit;charset=utf8';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Periksa apakah parameter id diberikan
    if (isset($_GET['id'])) {
        $id_laporan = $_GET['id'];

        // Query untuk menghapus data berdasarkan id_laporan
        $sql = "DELETE FROM laporan_perbaikan_elit WHERE id_laporan = :id_laporan";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_laporan', $id_laporan, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: tampil_data_laporan.php");
            exit;
        } else {
            echo "Terjadi kesalahan saat menghapus data.";
        }
    } else {
        die("ID tidak diberikan!");
    }
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>
