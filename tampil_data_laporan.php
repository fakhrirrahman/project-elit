<?php
// Konfigurasi database
$dsn = 'mysql:host=localhost;dbname=projectelit;charset=utf8';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Inisialisasi query
    $sql = "SELECT * FROM laporan_perbaikan_elit";
    $params = [];

    // Cek apakah ada input pencarian berdasarkan tanggal
    if (!empty($_GET['tanggal'])) {
        $sql .= " WHERE tanggal = :tanggal";
        $params[':tanggal'] = $_GET['tanggal'];
    }

    // Tambahkan ORDER BY untuk mengurutkan berdasarkan tanggal
    $sql .= " ORDER BY tanggal DESC";

    // Persiapkan dan eksekusi query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Laporan Harian</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        .main-content {
            height: calc(100vh - 4rem);
            transition: margin-left 0.3s ease;
        }

        .table-container {
            overflow-y: auto;
        }

        .table-auto {
            min-width: 800px;
        }

        .table-auto th,
        .table-auto td {
            padding-right: 16px;
            /* Tambahkan padding kanan */
            padding-left: 8px;
            /* Tambahkan padding kiri untuk keseimbangan */
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
            height: 64px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar-open .sidebar {
            transform: translateX(0);
        }

        .sidebar-open .main-content {
            margin-left: 256px;
        }
    </style>

</head>

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
<div class="main-content flex-1 bg-white flex flex-col transition-all duration-300" id="mainContent">
    <!-- Header -->
    <header class="header p-4 flex items-center justify-between">
        <!-- Hamburger Button -->
        <button class="text-gray-600 focus:outline-none" id="toggleSidebar">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
            </svg>
        </button>
        <h1 class="text-lg font-semibold text-center w-full">Data Laporan Harian</h1>
    </header>
    <!-- Search Form -->
    <div class="p-6">
        <form method="GET" class="flex gap-4 items-center mb-6">
            <input
                type="date"
                name="tanggal"
                value="<?php echo htmlspecialchars($_GET['tanggal'] ?? ''); ?>"
                class="border rounded-lg px-4 py-2"
                placeholder="Cari berdasarkan tanggal">
            <button
                type="submit"
                class="bg-indigo-500 text-white px-6 py-2 rounded-lg hover:bg-indigo-600">
                Search
            </button>
        </form>
    </div>
    <!-- Table -->
    <div class="table-container p-6">
        <?php if ($stmt->rowCount() > 0): ?>
            <div class="overflow-x-auto bg-white shadow rounded-lg border border-gray-200">
                <table class="min-w-full table-auto">
                    <!-- Bagian Header Tabel -->
                    <thead class="bg-indigo-600 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left border">Tanggal</th>
                            <th class="px-4 py-3 text-left border">Lokasi</th>
                            <th class="px-4 py-3 text-left border">Shift</th>
                            <th class="px-4 py-3 text-left border hidden lg:table-cell">Petugas</th>
                            <th class="px-4 py-3 text-left border hidden lg:table-cell">Lokasi Temuan</th>
                            <th class="px-4 py-3 text-left border hidden xl:table-cell">Waktu</th>
                            <th class="px-4 py-3 text-left border hidden xl:table-cell">Hasil</th>
                            <th class="px-4 py-3 text-left border">Kuantitas</th>
                            <th class="px-4 py-3 text-left border hidden lg:table-cell">Tindak Lanjut</th>
                            <th class="px-4 py-3 text-left border hidden xl:table-cell">Realisasi</th>
                            <th class="px-4 py-3 text-left border">Foto</th>
                            <th class="px-4 py-3 text-left border">Aksi</th>
                        </tr>
                    </thead>
                    <!-- Bagian Body Tabel -->
                    <tbody>
                        <?php foreach ($stmt as $row): ?>
                            <tr class="hover:bg-gray-100">
                                <td class="px-4 py-3 border"><?php echo htmlspecialchars($row['tanggal']); ?></td>
                                <td class="px-4 py-3 border"><?php echo htmlspecialchars($row['lokasi_laporan']); ?></td>
                                <td class="px-4 py-3 border"><?php echo htmlspecialchars($row['shift']); ?></td>
                                <td class="px-4 py-3 border hidden lg:table-cell"><?php echo htmlspecialchars($row['petugas']); ?></td>
                                <td class="px-4 py-3 border hidden lg:table-cell"><?php echo htmlspecialchars($row['lokasi_temuan']); ?></td>
                                <td class="px-4 py-3 border hidden xl:table-cell"><?php echo htmlspecialchars($row['waktu_temuan']); ?></td>
                                <td class="px-4 py-3 border hidden xl:table-cell"><?php echo htmlspecialchars($row['hasil_temuan']); ?></td>
                                <td class="px-4 py-3 border"><?php echo htmlspecialchars($row['kuantitas']); ?></td>
                                <td class="px-4 py-3 border hidden lg:table-cell"><?php echo htmlspecialchars($row['tindak_lanjut']); ?></td>
                                <td class="px-4 py-3 border hidden xl:table-cell"><?php echo htmlspecialchars($row['realisasi']); ?></td>
                                <td class="px-4 py-3 border">
                                    <?php if (!empty($row['path_file'])): ?>
                                        <img src="<?php echo htmlspecialchars($row['path_file']); ?>" alt="Foto Laporan" class="w-16 h-16 object-cover rounded">
                                    <?php else: ?>
                                        <span class="text-gray-500">Tidak ada foto</span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-4 py-3 border text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- View Button -->
                                        <a href="view.php?id=<?php echo $row['id_laporan']; ?>"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                                            <i class="fas fa-eye mr-2"></i> View
                                        </a>
                                        <!-- Edit Button -->
                                        <a href="edit.php?id=<?php echo $row['id_laporan']; ?>"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-yellow-500 rounded hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2">
                                            <i class="fas fa-edit mr-2"></i> Edit
                                        </a>
                                        <!-- Delete Button -->
                                        <a href="delete.php?id=<?php echo $row['id_laporan']; ?>"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-500 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2">
                                            <i class="fas fa-trash-alt mr-2"></i> Delete
                                        </a>
                                    </div>
                                </td>




                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php else: ?>
            <p class="text-center text-gray-500">Tidak ada data laporan perbaikan.</p>
        <?php endif; ?>
    </div>

</div>
</div>

<script>
    // Sidebar toggle functionality
    const toggleSidebar = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');

    toggleSidebar.addEventListener('click', () => {
        document.body.classList.toggle('sidebar-open');
    });
</script>

</body>

</html>