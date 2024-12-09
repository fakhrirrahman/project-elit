<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $facilityNames = $_POST['fasilitas'];
  $conditions = $_POST['kondisi'];
  $statuses = $_POST['status'];
  $descriptions = $_POST['keterangan'];
  $photos = $_FILES['foto'];
  $groupNames = $_POST['group_name']; // Grup fasilitas
  $tanggal = $_POST['tanggal']; // Tanggal input

  // Koneksi database
  $conn = new mysqli("localhost", "root", "", "projectelit");
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Proses unggah foto
  $photoPath = '';
  if (isset($photos['tmp_name']) && $photos['error'] === UPLOAD_ERR_OK) {
    $photoTmpPath = $photos['tmp_name'];
    $photoName = uniqid() . '-' . basename($photos['name']);
    $photoPath = 'uploads/' . $photoName;

    if (!is_dir('uploads')) {
      mkdir('uploads', 0777, true);
    }

    if (!move_uploaded_file($photoTmpPath, $photoPath)) {
      echo "<p class='text-red-500'>Gagal mengunggah foto.</p>";
      exit;
    }
  }

  // Loop menyimpan data fasilitas
  foreach ($facilityNames as $i => $facility_name) {
    $facility_name = $conn->real_escape_string($facility_name);
    $kondisi = $conn->real_escape_string($conditions[$i]);
    $status = $conn->real_escape_string($statuses[$i]);
    $description = $conn->real_escape_string($descriptions[$i]);
    $group_name = $conn->real_escape_string($groupNames[$i]);
    $tanggal_db = $conn->real_escape_string($tanggal); // Sanitasi input tanggal

    $sql = "INSERT INTO facilities (facility_name, kondisi, status, description, photo, group_name, tanggal) 
                VALUES ('$facility_name', '$kondisi', '$status', '$description', '$photoPath', '$group_name', '$tanggal_db')";

    if (!$conn->query($sql)) {
      echo "<p class='text-red-500'>Error: " . $conn->error . "</p>";
    }
  }

  // Menutup koneksi dan mencegah pengiriman ulang
  $conn->close();
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Dashboard Home</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />
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

    .sidebar a {
      text-decoration: none;
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

        <h1 class="text-2xl font-semibold text-gray-800"></h1>
        <div class="flex items-center space-x-6">
          <button class="flex items-center space-x-3 hover:text-indigo-600 transition">

            <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>
            </span>
          </button>
        </div>
      </header>

      <!-- Main Content for Form -->
      <main class="p-8 bg-gray-50 h-full overflow-y-auto mt-16">
        <div id="facilities-table">
          <h1 class="text-3xl font-bold mb-6 text-black">Laporan Sunrise</h1>
          <form action="" method="POST" enctype="multipart/form-data" id="facilityForm">
            <!-- Input tanggal -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden mb-3 p-5">
              <h2 class="text-xl font-semibold text-black mb-4">Tanggal Input</h2>
              <input type="date" name="tanggal" class="border px-2 py-1 w-full" required>
            </div>

            <?php
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
            

            // Loop untuk setiap grup
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
                  <tbody class='bg-grey'>


              ";

              $counter = 1; // Variabel penghitung nomor urut
              foreach ($facilities as $facility) {
                echo "
                    <tr>
                      <td class='border px-4 py-2'>$counter</td>
                      <td class='border px-4 py-2'>
                        <span>$facility</span>
                        <input type='hidden' name='fasilitas[]' value='$facility'>
                      </td>
                      <td class='border px-4 py-2'>
                        <select name='kondisi[]' class='border px-2 py-1 w-full'>
                          <option value='On'>On</option>
                          <option value='Off'>Off</option>
                        </select>
                      </td>
                      <td class='border px-4 py-2'>
                        <select name='status[]' class='border px-2 py-1 w-full'>
                          <option value='Normal'>Normal</option>
                          <option value='Abnormal'>Abnormal</option>
                        </select>
                      </td>
                      <td class='border px-4 py-2'>
                        <input type='text' name='keterangan[]' class='border px-2 py-1 w-full' placeholder='Keterangan'>
                      </td>
                      <input type='hidden' name='group_name[]' value='$groupName'>
                    </tr>
                  ";
                $counter++; // Tambahkan nomor urut
              }
              echo "</tbody></table></div>";
            }

            ?>

            <!-- Input foto -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden mb-8 p-4">
              <h2 class="text-xl font-semibold text-blue-600 mb-4">Upload Foto</h2>
              <input type="file" name="foto" class="border px-2 py-1 w-full">
            </div>

            <div class="mb-4">
              <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Kirim Data</button>
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>

  <script>
    document.getElementById('toggleSidebar').addEventListener('click', function() {
      document.body.classList.toggle('sidebar-open');
    });
  </script>
</body>

</html>