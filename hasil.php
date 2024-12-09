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

// Ambil data dari database
$sql = "SELECT * FROM laporan_perbaikan_elit ORDER BY id_laporan DESC LIMIT 1";
$result = $conn->query($sql);

// Pastikan ada hasil dari query
if ($result->num_rows > 0) {
    $laporan = $result->fetch_assoc();
} else {
    die("Tidak ada data yang ditemukan.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Laporan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <style>
        body {
            transition: background-color 0.3s;
            font-family: 'Roboto', sans-serif;
        }

        .main-content {
            transition: margin-left 0.3s ease;
        }

        .sidebar {
            width: 256px;
            background-image: url('images/pesawat.jpg');
            /* Jalur ke gambar */
            background-size: cover;
            /* Membuat gambar mencakup seluruh area sidebar */
            background-repeat: no-repeat;
            /* Mencegah gambar mengulangi diri */
            background-position: center;
            /* Memastikan gambar selalu terpusat */
            color: white;
            position: fixed;
            height: 100%;
            overflow-y: auto;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 64px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 10;
            transition: margin-left 0.3s ease;
        }

        .sidebar-open .sidebar {
            transform: translateX(0);
        }

        .sidebar-open .main-content {
            margin-left: 256px;
        }
    </style>
</head>


<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar p-6" id="sidebar">
            <h2 class="text-2xl font-semibold mb-8">Dashboard</h2>
            <nav>
                <ul>
                    <li class="mb-4">
                        <a class="flex items-center hover:text-gray-300" href="dashboardadmin.php">
                            <i class="fas fa-home mr-3"></i> Home
                        </a>
                    </li>
                    <li class="mb-4">
                        <a class="flex items-center hover:text-gray-300" href="laporan.php">
                            <i class="fas fa-file-alt mr-3"></i> Laporan Harian
                            <ul class="ml-6 mt-2">
                                <li class="mb-2">
                                    <a class="flex items-center hover:text-gray-300" href="tampil_data_laporan.php">
                                        <i class="fas fa-file-alt mr-3"></i> Tampilan laporan
                                    </a>
                                </li>
                            </ul>
                        </a>
                    </li>
                    <li class="mb-4">
                        <a class="flex items-center hover:text-gray-300" href="sunrise.php">
                            <i class="fas fa-sun mr-3"></i> Input Sunrise
                            <ul class="ml-6 mt-2">
                                <li class="mb-2">
                                    <a class="flex items-center hover:text-gray-300" href="tampil_data_sunrise.php">
                                        <i class="fas fa-file-alt mr-3"></i> Tampilan sunrise
                                    </a>
                                </li>
                            </ul>
                        </a>
                    </li>
                    <li class="mb-4">
                        <a class="flex items-center hover:text-gray-300" href="sunset.php">
                            <i class="fas fa-moon mr-3"></i> Input Sunset
                            <ul class="ml-6 mt-2">
                                <li class="mb-2">
                                    <a class="flex items-center hover:text-gray-300" href="tampil_data_sunset.php">
                                        <i class="fas fa-file-alt mr-3"></i> Tampilan sunset
                                    </a>
                                </li>
                            </ul>
                        </a>
                    </li>
                    <li>
                        <a class="flex items-center hover:text-gray-300" href="logout.php">
                            <i class="fas fa-sign-out-alt mr-3"></i> Logout
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <!-- Main Content -->
        <div class="main-content flex-1 flex flex-col transition-all duration-300" id="mainContent">
            <!-- Header -->
            <header class="header p-4 flex items-center justify-between">
                <div>
                    <button class="text-gray-600 focus:outline-none" id="toggleSidebar">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                            <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                    </button>
                </div>
                <h1 class="text-2xl font-semibold text-gray-800">Laporan Perbaikan</h1>
            </header>
            <!-- Content -->
            <div class="container mx-auto p-8 mt-16">
                <h1 class="text-3xl font-bold text-center mb-8">Hasil Laporan Perbaikan</h1>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <p><strong>Tanggal:</strong> <?php echo $laporan['tanggal']; ?></p>
                    <p><strong>Lokasi Laporan:</strong> <?php echo $laporan['lokasi_laporan']; ?></p>
                    <p><strong>Shift:</strong> <?php echo $laporan['shift']; ?></p>
                    <p><strong>Petugas:</strong> <?php echo $laporan['petugas']; ?></p>
                    <h3 class="text-xl font-bold mt-6">Detail Temuan</h3>
                    <table class="w-full table-auto mt-4 border-collapse">
                        <thead>
                            <tr class="bg-blue-600 text-white">
                                <th class="p-2 border">Lokasi Temuan</th>
                                <th class="p-2 border">Waktu Temuan</th>
                                <th class="p-2 border">Hasil Temuan</th>
                                <th class="p-2 border">Kuantitas</th>
                                <th class="p-2 border">Tindak Lanjut</th>
                                <th class="p-2 border">Realisasi</th>
                                <th class="p-2 border">Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white">
                                <td class="p-2 border"><?php echo $laporan['lokasi_temuan']; ?></td>
                                <td class="p-2 border"><?php echo $laporan['waktu_temuan']; ?></td>
                                <td class="p-2 border"><?php echo $laporan['hasil_temuan']; ?></td>
                                <td class="p-2 border"><?php echo $laporan['kuantitas']; ?></td>
                                <td class="p-2 border"><?php echo $laporan['tindak_lanjut']; ?></td>
                                <td class="p-2 border"><?php echo $laporan['realisasi']; ?></td>
                                <td class="p-2 border">
                                    <?php if ($laporan['path_file']) { ?>
                                        <img src="<?php echo $laporan['path_file']; ?>" alt="Foto" class="w-20 h-20">
                                    <?php } else { ?>
                                        Tidak ada file.
                                    <?php } ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-8 text-center">
                    <button onclick="window.print();" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md transition duration-300 cursor-pointer">
                        <i class="fas fa-print"></i> Print Laporan
                    </button>
                    <button onclick="generatePDF();" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition duration-300 cursor-pointer">
                        <i class="fas fa-file-pdf"></i> Download PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.body.classList.toggle('sidebar-open');
        });

        async function generatePDF() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            // Judul laporan
            doc.setFontSize(18);
            doc.text('Hasil Laporan Perbaikan', 10, 10);

            // Ambil data dari PHP
            const tanggal = "<?php echo $laporan['tanggal']; ?>";
            const lokasiLaporan = "<?php echo $laporan['lokasi_laporan']; ?>";
            const shift = "<?php echo $laporan['shift']; ?>";
            const petugas = "<?php echo $laporan['petugas']; ?>";

            const lokasiTemuan = "<?php echo $laporan['lokasi_temuan']; ?>";
            const waktuTemuan = "<?php echo $laporan['waktu_temuan']; ?>";
            const hasilTemuan = "<?php echo $laporan['hasil_temuan']; ?>";
            const kuantitas = "<?php echo $laporan['kuantitas']; ?>";
            const tindakLanjut = "<?php echo $laporan['tindak_lanjut']; ?>";
            const realisasi = "<?php echo $laporan['realisasi']; ?>";
            const fotoPath = "<?php echo $laporan['path_file']; ?>";

            // Tambahkan informasi laporan
            doc.setFontSize(12);
            doc.text(`Tanggal: ${tanggal}`, 10, 20);
            doc.text(`Lokasi Laporan: ${lokasiLaporan}`, 10, 30);
            doc.text(`Shift: ${shift}`, 10, 40);
            doc.text(`Petugas: ${petugas}`, 10, 50);

            // Tambahkan tabel detail temuan
            const startY = 60;
            doc.setFontSize(14);
            doc.text('Detail Temuan', 10, startY);

            const tableStartY = startY + 10;
            doc.setFontSize(12);
            doc.text('Lokasi Temuan:', 10, tableStartY);
            doc.text(lokasiTemuan, 50, tableStartY);

            doc.text('Waktu Temuan:', 10, tableStartY + 10);
            doc.text(waktuTemuan, 50, tableStartY + 10);

            doc.text('Hasil Temuan:', 10, tableStartY + 20);
            doc.text(hasilTemuan, 50, tableStartY + 20);

            doc.text('Kuantitas:', 10, tableStartY + 30);
            doc.text(kuantitas, 50, tableStartY + 30);

            doc.text('Tindak Lanjut:', 10, tableStartY + 40);
            doc.text(tindakLanjut, 50, tableStartY + 40);

            doc.text('Realisasi:', 10, tableStartY + 50);
            doc.text(realisasi, 50, tableStartY + 50);

            // Tambahkan foto jika ada
            if (fotoPath) {
                const img = new Image();
                img.src = fotoPath;

                img.onload = function() {
                    const imgWidth = 60; // Lebar gambar
                    const imgHeight = 60; // Tinggi gambar
                    const imgX = 10; // Posisi X
                    const imgY = tableStartY + 60; // Posisi Y

                    // Tambahkan gambar ke PDF
                    doc.addImage(img, 'JPEG', imgX, imgY, imgWidth, imgHeight);

                    // Simpan file PDF
                    doc.save('laporan_perbaikan.pdf');
                };
            } else {
                doc.text('Foto: Tidak ada file.', 10, tableStartY + 60);
                // Simpan file PDF
                doc.save('laporan_perbaikan.pdf');
            }
        }
    </script>
</body>

</html>