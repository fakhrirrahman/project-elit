<?php
// Koneksi database
$conn = new mysqli("localhost", "root", "", "projectelit");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Inisialisasi tanggal filter
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Kelompok fasilitas
$facilityGroups = [
    "TERMINAL" => [
        "SOUND SYSTEM (PAS)",
        "FIDS KEBERANGKATAN",
        "FIDS KEDATANGAN",
        "FIDS R.TUNGGU 1",
        "FIDS R.TUNGGU 2",
        "FIDS BAGGAGE INFORMATION 1",
        "FIDS BAGGAGE INFORMATION 2",
        "PACC CITILINK",
        "PACC GARUDA 1",
        "PACC GARUDA2",
        "PACC LION",
        "PACC SUPER AIR JET",
        "PACC SUSI AIR",
        "DIGITAL BANNER SCP 1",
        "DIGITAL BANNER AREA CHECK IN",
        "DIGITAL BANNER GATE 1",
        "DIGITAL BANNER GATE 2",
        "DIGITAL BANNER KEDATANGAN (2 UNIT)",
        "DIGITAL BANNER RUANG TUNGGU (5 UNIT)",
        "DIGITAL BANNER LOBBY (3 UNIT)",
        "RUNNING TEXT SCP 2",
        "HHMD SCP 1",
        "HHMD SCP 2 ",
        "X-RAY BAGASI SCP 1 A",
        "X-RAY BAGASI SCP 1 A",
        "X-RAY BAGASI SCP 1 B",
        "X-RAY CABIN SCP 2 A",
        "X-RAY CABIN SCP 2 B",
        "X-RAY CABIN CIP LOUNGE",
        "WTMD BHS",
        "WTMD KEDATANGAN",
        "WTMD SCP 2 A",
        "WTMD SCP 2 B",
        "WTMD CIP",
        "CCTV TERMINAL",
    ],
    "TERMINAL CARGO" => ["X-RAY CARGO A", "X-RAY CARGO B", "WTMD", " CCTV ", " FIDS"],
    "PERKANTORAN" => [
        "WIFI Lt. 1",
        "WIFI Lt. 2",
        "SERVER VPN",
        " MIC CONFERENCE RUANG RAPAT",
        "PABX",
        "CCTV",
        "WIFI VPN R. ADM",
        "WIFI VPN R. TU",
    ],
    "Gedung PK-PPK" => ["Perangkat RIG VHF Air To Ground", "PABX ",],
    "RUANG CUSTOMER SERVICE" => [
        "Server FIDS",
        "Server PACC",
        " Server PAS",
        "ADAPTOR",
        " PC Operator",
        "HT/RIG",
    ],
    "LAIN-LAIN" => ["PABX AIRNAV", "RUANG server CCTV",],
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Daftar Fasilitas Elektronik dan IT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Default style for sidebar */
        .sidebar {
            width: 250px;
            transition: transform 0.3s ease;
            transform: translateX(-100%);
            position: fixed;
            height: 100%;
            top: 0;
            left: 0;
            background-image: url('images/pesawat.jpg');
            /* Jalur ke gambar */
            background-size: cover;
            /* Membuat gambar mencakup seluruh area sidebar */
            background-repeat: no-repeat;
            /* Mencegah gambar mengulangi diri */
            background-position: center;
            /* Memastikan gambar selalu terpusat */
            color: white;
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


        /* Sidebar open state */
        .sidebar-open .sidebar {
            transform: translateX(0);
        }

        /* Main content moves when sidebar is open */
        .sidebar-open #mainContent {
            transform: translateX(250px);
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
                                    <a class="flex items-center hover:text-gray-300" href="tampil_data_sunrise.php">
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
                    <!-- Hamburger Button -->
                    <button class="text-gray-600 focus:outline-none" id="toggleSidebar">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                            <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                    </button>
                </div>
                <h1 class="text-2xl font-semibold text-gray-800"></h1>
                <div class="flex items-center space-x-6">
                    <button class="flex items-center space-x-3 hover:text-indigo-600 transition">
                        <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>
                        </span>
                    </button>
                </div>
            </header>

            <!-- Main Content -->
            <main class="p-8 bg-gray-50 h-full overflow-y-auto mt-16">
                <h1 class="text-2xl font-bold mb-6 text-black">Daftar Fasilitas Elektronik dan IT</h1>
                <div>

                </div>
                <!-- Form untuk memilih tanggal -->
                <form method="GET" class="mb-6">
                    <label for="date" class="block text-lg font-medium text-gray-700">Pilih Tanggal:</label>
                    <input type="date" id="date" name="date" value="<?php echo $selectedDate; ?>" class="border rounded px-4 py-2 w-full max-w-sm">
                    <button type="submit" class="mt-2 bg-blue-600 text-white px-4 py-2 rounded">Tampilkan</button>
                </form>

                <!-- Menampilkan data fasilitas berdasarkan grup -->
                <?php
                foreach ($facilityGroups as $groupName => $facilities) {
                    echo "<div class='bg-white shadow-md rounded-lg overflow-hidden mb-8'>";
                    echo "<h2 class='text-xl font-semibold bg-green-600 text-white px-4 py-2'>$groupName</h2>";
                    echo "<table class='table-auto w-full'>";
                    echo "
                 <thead>
  <tr>
    <th class='border px-4 py-2 text-left text-white bg-red-500'>No</th>
    <th class='border px-4 py-2 text-left text-white bg-green-500'>Fasilitas</th>
    <th class='border px-4 py-2 text-left text-white bg-yellow-500'>Kondisi</th>
    <th class='border px-4 py-2 text-left text-white bg-blue-500'>Status</th>
    <th class='border px-4 py-2 text-left text-white bg-purple-500'>Keterangan</th>
  </tr>
</thead>
                        <tbody class='bg-white'>
                    ";

                    // Query untuk mengambil data fasilitas berdasarkan grup dan tanggal
                    $facilityNames = "'" . implode("','", $facilities) . "'";
                    $sql = "SELECT * FROM facilities 
                            WHERE facility_name IN ($facilityNames) 
                            AND DATE(tanggal) = '$selectedDate'";
                    $result = $conn->query($sql);

                    if (!$result) {
                        echo "<tr><td colspan='5' class='border px-4 py-2 text-center text-red-500'>SQL Error: " . $conn->error . "</td></tr>";
                    } elseif ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "
                                <tr>
                                    <td class='border px-4 py-2'>{$row['facility_name']}</td>
                                    <td class='border px-4 py-2'>{$row['kondisi']}</td>
                                    <td class='border px-4 py-2'>{$row['status']}</td>
                                    <td class='border px-4 py-2'>{$row['description']}</td>
                                    <td class='border px-4 py-2'>
                                        <a href='edit_fasilitas.php?id={$row['id']}' class='text-blue-600'>Edit</a> | 
                                        <a href='delete_fasilitas.php?id={$row['id']}' class='text-red-600' onclick='return confirm(\"Are you sure you want to delete this item?\")'>Delete</a>
                                    </td>
                                </tr>
                            ";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='border px-4 py-2 text-center text-red-500'>Tidak ada data fasilitas untuk grup ini pada tanggal $selectedDate.</td></tr>";
                    }

                    echo "</tbody></table></div>";
                }

                ?>

                <!-- Menampilkan Foto Fasilitas -->
                <div class="bg-white shadow-md rounded-lg overflow-hidden mb-8 p-4">
                    <h2 class="text-xl font-semibold text-white-600 mb-4">Foto Fasilitas</h2>

                    <?php
                    // Query untuk mengambil foto pertama dari fasilitas berdasarkan tanggal
                    $photoSql = "SELECT photo FROM facilities WHERE DATE(tanggal) = '$selectedDate' LIMIT 1";
                    $photoResult = $conn->query($photoSql);
                    if ($photoResult->num_rows > 0) {
                        $photoRow = $photoResult->fetch_assoc();
                        if ($photoRow['photo']) {
                            echo "
                                <div class='flex justify-center mb-4'>
                                    <img src='{$photoRow['photo']}' alt='Foto Fasilitas' class='w-32 h-32 object-cover'>
                                </div>
                            ";
                        } else {
                            echo "<p class='text-center'>No photo available</p>";
                        }
                    }
                    ?>
                </div>


            </main>
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

<?php $conn->close(); ?>