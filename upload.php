<?php
// Konfigurasi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projectelit";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk membersihkan data
function sanitizeInput($data, $conn) {
    return $conn->real_escape_string(trim(htmlspecialchars($data)));
}

// Proses form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $tanggal = sanitizeInput($_POST['tanggal'], $conn);
    $lokasi_laporan = sanitizeInput($_POST['lokasi'], $conn);
    $shift = sanitizeInput($_POST['shift'], $conn);
    $petugas = sanitizeInput($_POST['petugas'], $conn);

    $lokasi_temuan = sanitizeInput($_POST['lokasi1'], $conn);
    $waktu_temuan = sanitizeInput($_POST['waktu1'], $conn);
    $hasil_temuan = sanitizeInput($_POST['temuan1'], $conn);
    $kuantitas = (int)($_POST['kuantitas1'] ?? 0);
    $tindak_lanjut = sanitizeInput($_POST['tindaklanjut1'], $conn);
    $sumber_laporan = sanitizeInput($_POST['sumberlaporan1'], $conn);
    $realisasi = sanitizeInput($_POST['realisasi1'], $conn);

    $nama_file = '';
    $path_file = '';

    // Proses unggah file
    if (isset($_FILES['foto1']) && $_FILES['foto1']['error'] === 0) {
        $nama_file = basename($_FILES['foto1']['name']);
        $path_file = "uploads/" . $nama_file;

        // Validasi file
        $image_file_type = strtolower(pathinfo($path_file, PATHINFO_EXTENSION));
        if ($_FILES['foto1']['size'] > 2000000) {
            echo "File terlalu besar. Maksimal 2MB.";
            exit;
        }
        if (!in_array($image_file_type, ['jpg', 'jpeg', 'png'])) {
            echo "Format file tidak valid. Hanya JPG, JPEG, dan PNG yang diperbolehkan.";
            exit;
        }

        // Simpan file ke direktori
        if (!move_uploaded_file($_FILES['foto1']['tmp_name'], $path_file)) {
            echo "Gagal mengunggah file.";
            exit;
        }
    }

    // Simpan data ke database
    $sql = "INSERT INTO laporan_perbaikan_elit 
            (tanggal, lokasi_laporan, shift, petugas, lokasi_temuan, waktu_temuan, hasil_temuan, kuantitas, tindak_lanjut, sumber_laporan, realisasi, nama_file, path_file) 
            VALUES 
            ('$tanggal', '$lokasi_laporan', '$shift', '$petugas', '$lokasi_temuan', '$waktu_temuan', '$hasil_temuan', $kuantitas, '$tindak_lanjut', '$sumber_laporan', '$realisasi', '$nama_file', '$path_file')";

    if ($conn->query($sql) === TRUE) {
        header("Location: hasil.php"); // Arahkan ke file hasil.php
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
