<?php
// Konfigurasi database
$dsn = 'mysql:host=localhost;dbname=projectelit;charset=utf8';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Periksa apakah parameter id_laporan diberikan
    if (isset($_GET['id'])) {
        $id_laporan = $_GET['id'];

        // Query untuk mengambil data berdasarkan id_laporan
        $sql = "SELECT * FROM laporan_perbaikan_elit WHERE id_laporan = :id_laporan";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_laporan', $id_laporan, PDO::PARAM_INT);
        $stmt->execute();

        // Ambil data
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            die("Data tidak ditemukan!");
        }
    } else {
        die("ID tidak diberikan!");
    }
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-lg mt-10">
        <h1 class="text-2xl font-bold mb-4">Detail Laporan</h1>
        <table class="table-auto w-full text-left">
            <tr>
                <th class="px-4 py-2 border">Tanggal</th>
                <td class="px-4 py-2 border"><?php echo htmlspecialchars($data['tanggal']); ?></td>
            </tr>
            <tr>
                <th class="px-4 py-2 border">Lokasi</th>
                <td class="px-4 py-2 border"><?php echo htmlspecialchars($data['lokasi_laporan']); ?></td>
            </tr>
            <tr>
                <th class="px-4 py-2 border">Shift</th>
                <td class="px-4 py-2 border"><?php echo htmlspecialchars($data['shift']); ?></td>
            </tr>
            <tr>
                <th class="px-4 py-2 border">Petugas</th>
                <td class="px-4 py-2 border"><?php echo htmlspecialchars($data['petugas']); ?></td>
            </tr>
            <tr>
                <th class="px-4 py-2 border">Lokasi Temuan</th>
                <td class="px-4 py-2 border"><?php echo htmlspecialchars($data['lokasi_temuan']); ?></td>
            </tr>
            <tr>
                <th class="px-4 py-2 border">Waktu Temuan</th>
                <td class="px-4 py-2 border"><?php echo htmlspecialchars($data['waktu_temuan']); ?></td>
            </tr>
            <tr>
                <th class="px-4 py-2 border">Hasil Temuan</th>
                <td class="px-4 py-2 border"><?php echo htmlspecialchars($data['hasil_temuan']); ?></td>
            </tr>
            <tr>
                <th class="px-4 py-2 border">Kuantitas</th>
                <td class="px-4 py-2 border"><?php echo htmlspecialchars($data['kuantitas']); ?></td>
            </tr>
            <tr>
                <th class="px-4 py-2 border">Tindak Lanjut</th>
                <td class="px-4 py-2 border"><?php echo htmlspecialchars($data['tindak_lanjut']); ?></td>
            </tr>
            <tr>
                <th class="px-4 py-2 border">Realisasi</th>
                <td class="px-4 py-2 border"><?php echo htmlspecialchars($data['realisasi']); ?></td>
            </tr>
            <tr>
                <th class="px-4 py-2 border">Foto</th>
                <td class="px-4 py-2 border">
                    <?php if (!empty($data['path_file'])): ?>
                        <img src="<?php echo htmlspecialchars($data['path_file']); ?>" alt="Foto Laporan" class="w-32 h-32 object-cover rounded">
                    <?php else: ?>
                        <span class="text-gray-500">Tidak ada foto</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        <div class="mt-4">
            <a href="tampil_data_laporan.php" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Kembali</a>
        </div>
    </div>
</body>

</html>
