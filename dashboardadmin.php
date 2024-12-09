<?php
// Koneksi ke database
$host = "localhost";
$username = "root"; // ganti dengan username database Anda
$password = ""; // ganti dengan password database Anda
$database = "projectelit";

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengambil semua tanggal yang sudah ada di database
$query = "SELECT tanggal FROM laporan_perbaikan_elit";
$result = $conn->query($query);

$datesWithReports = [];
while ($row = $result->fetch_assoc()) {
    $datesWithReports[] = $row['tanggal'];
}

$conn->close();
$startDate = "01";
$endDate = date("t"); // Jumlah hari dalam bulan saat ini
$monthYear = date("F Y"); // Format bulan dan tahun, contoh: Oktober 2024

// Periksa apakah parameter tanggal dikirim
if (isset($_GET['tanggal'])) {
    // Formatkan tanggal yang diterima
    $inputDate = DateTime::createFromFormat('Y-m-d', $_GET['tanggal']);
    if ($inputDate) {
        $startDate = "01";
        $endDate = $inputDate->format("t"); // Jumlah hari dalam bulan yang dipilih
        $monthYear = $inputDate->format("F Y"); // Format bulan dan tahun dari tanggal input
    }
}
?>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        Dashboard Home
    </title>
    <script src="https://cdn.tailwindcss.com">
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    <!-- FullCalendar CSS and JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js">
    </script>
    <style>
        /* Custom styles */
        body {
            transition: background-color 0.3s;
            font-family: 'Roboto', sans-serif;
        }

        .main-content {
            transition: margin-left 0.3s ease;
        }

        /* Sidebar and header styles */
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

        /* Adjust the layout when the sidebar is visible */
        .sidebar-open .sidebar {
            transform: translateX(0);
        }

        .sidebar-open .main-content {
            margin-left: 256px;
        }
    </style>
    </link>
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
                    <!-- Hamburger Button -->
                    <button class="text-gray-600 focus:outline-none" id="toggleSidebar">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                            <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            </path>
                        </svg>
                    </button>
                </div>
                <h1 class="text-2xl font-semibold text-gray-800">
                </h1>
                <div class="flex items-center space-x-6">
                    <button class="flex items-center space-x-3 hover:text-indigo-600 transition">
                        <img alt="User Avatar" class="w-10 h-10 rounded-full" height="40" src="images/angksapura.jpg" width="40" />
                        <span class="hidden md:block font-medium">
                            <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>
                        </span>
                    </button>
                </div>
            </header>
            <!-- Main Content Area -->
            <main class="p-8 bg-gray-50 h-full overflow-y-auto">
                <div class="bg-white shadow-lg rounded-lg p-6 w-full mb-6">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="w-1/4 text-center">
                                    <img src="images/angksapura.jpg" class="w-full h-auto object-contain mx-auto rounded-lg" height="100" src="https://storage.googleapis.com/a1aa/image/Tn3CtmcWf8wiDKJYejeL4NmerHotti2oX4MezS8NrjkqezG8E.jpg" width="200" />
                                </th>
                                <th class="w-1/2 text-left">
                                    <h1 class="text-3xl font-bold">
                                        PT ANGKASA PURA INDONESIA
                                    </h1>
                                    <p class="text-lg text-gray-600">
                                        Bandara Fatmawati Soekarno Bengkulu
                                    </p>
                                </th>
                                <th class="w-1/4 text-right">
                                    <p class="text-lg text-gray-500">
                                        Laporan Pengecekan Fasilitas Elektronika dan IT
                                    </p>
                                    <p class="text-gray-500">
                                        Periode: <?= $startDate ?> - <?= $endDate ?> <?= $monthYear ?>
                                    </p>

                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- Form and Calendar -->
                <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1 text-sm font-semibold">
                                Bandara
                            </label>
                            <select class="form-select w-full border border-gray-300 rounded-md p-2" id="bandara" name="bandara">
                                <option disabled="" selected="">
                                    Pilih Bandara
                                </option>
                                <option value="1">
                                    BKS
                                </option>
                                <option value="2">
                                    JOG
                                </option>
                                <option value="3">
                                    OGK
                                </option>
                                <option value="4">
                                    LOP
                                </option>
                                <option value="5">
                                    DJJ
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-semibold">
                                Unit
                            </label>
                            <select class="form-select w-full border border-gray-300 rounded-md p-2" id="unit" name="unit">
                                <option disabled="" selected="">
                                    Pilih Unit
                                </option>
                                <option value="1">
                                    AIRPORT FACILITIES, EQUIPMENT, &amp; TECHNOLOGY DEPARTMENT HEAD
                                </option>
                                <option value="2">
                                    AIRPORT EQUIPMENT &amp; TECHNOLOGY SUPERVISOR
                                </option>
                                <option value="3">
                                    AIRPORT FACILITIES SUPERVISOR
                                </option>
                                <option value="4">
                                    AIRPORT FACILITIES ENGINEER
                                </option>
                                <option value="5">
                                    AIRPORT EQUIPMENT &amp; TECHNOLOGY ENGINEER
                                </option>
                                <option value="6">
                                    AIRPORT EQUIPMENT &amp; TECHNOLOGY TECHNICIAN
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                    <form class="grid grid-cols-1 md:grid-cols-2 gap-4" method="GET">
                        <div>
                            <label class="block mb-1 text-sm font-semibold">
                                Laporan Harian
                            </label>
                            <select class="form-select w-full border border-gray-300 rounded-md p-2" id="laporan" name="laporan">
                                <option disabled="" selected="">
                                    Pilih Laporan Harian
                                </option>
                                <option value="1">
                                    Laporan 1
                                </option>
                                <option value="2">
                                    Laporan 2
                                </option>
                                <option value="3">
                                    Laporan 3
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-semibold">
                                Tanggal
                            </label>
                            <input class="form-input w-full border border-gray-300 rounded-md p-2" id="tanggal" name="tanggal" required="" type="date" />
                        </div>
                        <div class="col-span-2 text-right">
                            <button class="bg-blue-500 text-white font-semibold py-2 px-4 rounded hover:bg-blue-400 transition" type="submit">
                                Tampilkan
                            </button>
                        
                </div>
                </form>
        </div>
        <div id="calendar">
        </div>
        </main>
    </div>
    </div>
    <script>
        // Script untuk menampilkan periode secara dinamis
        document.addEventListener('DOMContentLoaded', function() {
            const periodeElement = document.getElementById('periode');
            const today = new Date();
            const monthNames = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];
            const year = today.getFullYear();
            const month = today.getMonth(); // Bulan dimulai dari 0

            // Tentukan jumlah hari dalam bulan ini
            const lastDay = new Date(year, month + 1, 0).getDate();

            // Set teks periode dengan format: "01 - [Last Day] [Month Name] [Year]"
            periodeElement.textContent = `Periode: 01 - ${lastDay} ${monthNames[month]} ${year}`;
        });
    </script>
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.body.classList.toggle('sidebar-open');
        });

        // Set the date input to today's date
        document.addEventListener('DOMContentLoaded', function() {
            var today = new Date().toISOString().split('T')[0];
            document.getElementById('tanggal').value = today;
        });
    </script>

    <script>
        $(document).ready(function() {
            // Data tanggal yang sudah memiliki laporan dari PHP
            var datesWithReports = <?php echo json_encode($datesWithReports); ?>;

            // FullCalendar setup
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                // Menambahkan event pada tanggal yang sudah ada laporan
                events: function(start, end, timezone, callback) {
                    var events = [];
                    var uniqueDates = {}; // Untuk memastikan hanya satu event per tanggal

                    // Loop untuk memasukkan event hanya sekali per tanggal
                    datesWithReports.forEach(function(date) {
                        if (!uniqueDates[date]) { // Jika tanggal belum ada di uniqueDates
                            events.push({
                                title: 'Laporan Tersedia',
                                start: date,
                                allDay: true,
                                backgroundColor: '#3b9dff', // Warna kuning untuk penanda
                                textColor: '#000000' // Warna teks hitam agar kontras dengan kuning
                            });
                            uniqueDates[date] = true; // Tandai tanggal ini sudah ada event-nya
                        }
                    });
                    callback(events);
                },
                // Event click handler untuk mengecek apakah tanggal sudah memiliki inputan
                dayClick: function(date, jsEvent, view) {
                    var selectedDate = date.format('YYYY-MM-DD'); // Format tanggal ke YYYY-MM-DD

                    // Cek apakah tanggal sudah ada di dalam datesWithReports
                    if (datesWithReports.includes(selectedDate)) {
                        // Jika sudah ada inputan, arahkan ke tampilan laporan
                        window.location.href = "http://localhost/projectelit/tampil_data_laporan.php?tanggal=" + selectedDate;
                    } else {
                        // Jika belum ada inputan, arahkan ke halaman input laporan
                        window.location.href = "http://localhost/projectelit/laporan.php?tanggal=" + selectedDate;
                    }
                }
            });
        });
    </script>
</body>

</html>