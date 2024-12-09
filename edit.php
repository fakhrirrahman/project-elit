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

        // Query untuk mengambil data berdasarkan id_laporan
        $sql = "SELECT * FROM laporan_perbaikan_elit WHERE id_laporan = :id_laporan";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_laporan', $id_laporan, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            die("Data tidak ditemukan!");
        }
    } else {
        die("ID tidak diberikan!");
    }

    // Proses update data jika form disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tanggal = $_POST['tanggal'];
        $lokasi = $_POST['lokasi'];
        $shift = $_POST['shift'];
        $petugas = $_POST['petugas'];
        $lokasi_temuan = $_POST['lokasi_temuan'];
        $waktu_temuan = $_POST['waktu_temuan'];
        $hasil_temuan = $_POST['hasil_temuan'];
        $kuantitas = $_POST['kuantitas'];
        $tindak_lanjut = $_POST['tindak_lanjut'];
        $realisasi = $_POST['realisasi'];

        $sql_update = "UPDATE laporan_perbaikan_elit 
                       SET tanggal = :tanggal, 
                           lokasi_laporan = :lokasi, 
                           shift = :shift, 
                           petugas = :petugas, 
                           lokasi_temuan = :lokasi_temuan, 
                           waktu_temuan = :waktu_temuan, 
                           hasil_temuan = :hasil_temuan, 
                           kuantitas = :kuantitas, 
                           tindak_lanjut = :tindak_lanjut, 
                           realisasi = :realisasi 
                       WHERE id_laporan = :id_laporan";

        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->bindParam(':tanggal', $tanggal);
        $stmt_update->bindParam(':lokasi', $lokasi);
        $stmt_update->bindParam(':shift', $shift);
        $stmt_update->bindParam(':petugas', $petugas);
        $stmt_update->bindParam(':lokasi_temuan', $lokasi_temuan);
        $stmt_update->bindParam(':waktu_temuan', $waktu_temuan);
        $stmt_update->bindParam(':hasil_temuan', $hasil_temuan);
        $stmt_update->bindParam(':kuantitas', $kuantitas);
        $stmt_update->bindParam(':tindak_lanjut', $tindak_lanjut);
        $stmt_update->bindParam(':realisasi', $realisasi);
        $stmt_update->bindParam(':id_laporan', $id_laporan, PDO::PARAM_INT);

        if ($stmt_update->execute()) {
            header("Location: tampil_data_laporan.php");
            exit;
        } else {
            echo "Terjadi kesalahan saat mengupdate data!";
        }
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
    <title>Edit Laporan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-lg mt-10">
        <h1 class="text-2xl font-bold mb-4">Edit Laporan</h1>
        <form method="POST" action="">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="tanggal" class="block text-sm font-medium">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" class="mt-1 block w-full border-gray-300 rounded-md" value="<?php echo htmlspecialchars($data['tanggal']); ?>" required>
                </div>
                <div>
                    <label for="lokasi" class="block text-sm font-medium">Lokasi</label>
                    <input type="text" id="lokasi" name="lokasi" class="mt-1 block w-full border-gray-300 rounded-md" value="<?php echo htmlspecialchars($data['lokasi_laporan']); ?>" required>
                </div>
                <div>
                    <label for="shift" class="block text-sm font-medium">Shift</label>
                    <input type="text" id="shift" name="shift" class="mt-1 block w-full border-gray-300 rounded-md" value="<?php echo htmlspecialchars($data['shift']); ?>" required>
                </div>
                <div>
                    <label for="petugas" class="block text-sm font-medium">Petugas</label>
                    <input type="text" id="petugas" name="petugas" class="mt-1 block w-full border-gray-300 rounded-md" value="<?php echo htmlspecialchars($data['petugas']); ?>" required>
                </div>
                <div>
                    <label for="lokasi_temuan" class="block text-sm font-medium">Lokasi Temuan</label>
                    <input type="text" id="lokasi_temuan" name="lokasi_temuan" class="mt-1 block w-full border-gray-300 rounded-md" value="<?php echo htmlspecialchars($data['lokasi_temuan']); ?>" required>
                </div>
                <div>
                    <label for="waktu_temuan" class="block text-sm font-medium">Waktu Temuan</label>
                    <input type="time" id="waktu_temuan" name="waktu_temuan" class="mt-1 block w-full border-gray-300 rounded-md" value="<?php echo htmlspecialchars($data['waktu_temuan']); ?>" required>
                </div>
                <div>
                    <label for="hasil_temuan" class="block text-sm font-medium">Hasil Temuan</label>
                    <input type="text" id="hasil_temuan" name="hasil_temuan" class="mt-1 block w-full border-gray-300 rounded-md" value="<?php echo htmlspecialchars($data['hasil_temuan']); ?>" required>
                </div>
                <div>
                    <label for="kuantitas" class="block text-sm font-medium">Kuantitas</label>
                    <input type="number" id="kuantitas" name="kuantitas" class="mt-1 block w-full border-gray-300 rounded-md" value="<?php echo htmlspecialchars($data['kuantitas']); ?>" required>
                </div>
                <div>
                    <label for="tindak_lanjut" class="block text-sm font-medium">Tindak Lanjut</label>
                    <textarea id="tindak_lanjut" name="tindak_lanjut" class="mt-1 block w-full border-gray-300 rounded-md" required><?php echo htmlspecialchars($data['tindak_lanjut']); ?></textarea>
                </div>
                <div>
                    <label for="realisasi" class="block text-sm font-medium">Realisasi</label>
                    <textarea id="realisasi" name="realisasi" class="mt-1 block w-full border-gray-300 rounded-md" required><?php echo htmlspecialchars($data['realisasi']); ?></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <a href="tampil_data_laporan.php" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Batal</a>
                <button type="submit" class="ml-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Simpan</button>
            </div>
        </form>
    </div>
</body>

</html>
