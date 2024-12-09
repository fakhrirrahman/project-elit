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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js">
    </script>
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
    <main class="p-8 bg-gray-50 h-full overflow-y-auto mt-16">
        <form action="upload.php" enctype="multipart/form-data" method="post">
            <div class="mb-8 space-y-4">
                <div>
                    <label class="block font-semibold">
                        Hari/Tanggal:
                    </label>
                    <input class="w-full p-2 border border-gray-300 rounded-md text-gray-900" name="tanggal" type="date" id="tanggal" value="" />
                </div>
                <div>
                    <label class="block font-semibold">
                        Tempat/Lokasi:
                    </label>
                    <input class="w-full p-2 border border-gray-300 rounded-md text-gray-900" name="lokasi" type="text" value="Bandara Fatmawati Soekarno" />
                </div>
                <div>
                    <label class="block font-semibold">
                        Shift:
                    </label>
                    <select class="w-full p-2 border border-gray-300 rounded-md text-gray-900" name="shift">
                        <option value="06:00-18:00">
                            06:00 WIB - 18:00 WIB
                        </option>
                        <option value="08:00-18:00">
                            08:00 WIB - 18:00 WIB
                        </option>
                        <option value="22:00-06:00">
                            22:00 WIB - 06:00 WIB
                        </option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold">
                        Petugas:
                    </label>
                    <input class="w-full p-2 border border-gray-300 rounded-md text-gray-900" name="petugas" type="text" value="" />
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-blue-600 text-white">
                            <th class="p-2 border">
                                No
                            </th>
                            <th class="p-2 border">
                                Lokasi
                            </th>
                            <th class="p-2 border">
                                Waktu Temuan
                            </th>
                            <th class="p-2 border">
                                Hasil Temuan
                            </th>
                            <th class="p-2 border">
                                Kuantitas
                            </th>
                            <th class="p-2 border">
                                Tindak Lanjut
                            </th>
                            <th class="p-2 border">
                                Sumber Laporan
                            </th>
                            <th class="p-2 border">
                                Realisasi Tindak Lanjut
                            </th>
                            <th class="p-2 border">
                                Keterangan/Dokumentasi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-gray-700">
                            <td class="p-2 border">
                                1
                            </td>
                            <td class="p-2 border">
                                <input class="w-full p-2 border rounded-md text-gray-900" name="lokasi1" placeholder="Lokasi" type="text" />
                            </td>
                            <td class="p-2 border">
                                <input class="w-full p-2 border rounded-md text-gray-900" name="waktu1" type="time" />
                            </td>
                            <td class="p-2 border">
                                <textarea class="w-full p-2 border rounded-md text-gray-900" name="temuan1" placeholder="Hasil Temuan"></textarea>
                            </td>
                            <td class="p-2 border">
                                <input class="w-full p-2 border rounded-md text-gray-900" name="kuantitas1" placeholder="Kuantitas" type="number" />
                            </td>
                            <td class="p-2 border">
                                <textarea class="w-full p-2 border rounded-md text-gray-900" name="tindaklanjut1" placeholder="Tindak Lanjut"></textarea>
                            </td>
                            <td class="p-2 border">
                                <input class="w-full p-2 border rounded-md text-gray-900" name="sumberlaporan1" placeholder="Sumber Laporan" type="text" />
                            </td>
                            <td class="p-2 border">
                                <input class="w-full p-2 border rounded-md text-gray-900" name="realisasi1" placeholder="Realisasi Tindak Lanjut" type="text" />
                            </td>
                            <td class="p-2 border">
                                <input accept="image/*" class="w-full p-2 border rounded-md text-gray-900" name="foto1" type="file" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-6 text-center">
                <input class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition duration-300 cursor-pointer" type="submit" value="Submit" />
            </div>
        </form>
    </main>
</div>
</div>
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
</body>

</html>