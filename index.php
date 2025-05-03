<?php
include "config/koneksi.php";


function total_pemasukan() {
    global $conn;
    $query = "SELECT IFNULL(SUM(tagihan), 0) AS total_pemasukan FROM data_sewa WHERE status = 'finish'";
    $hasil_income = mysqli_query($conn, $query);
    $row_income = mysqli_fetch_array($hasil_income);
    return $row_income['total_pemasukan'];
}


function total_antrean($status){
    global $conn;
    $query = "SELECT COUNT(*) AS total_antrean FROM data_sewa WHERE status='$status'";
    $exec_sql = mysqli_query($conn, $query);
    $row_total = mysqli_fetch_assoc($exec_sql);
    return $row_total['total_antrean'];
}


function getPlaystationUsageData($nama_ps) {
    global $conn;
    $query = "
        SELECT 
            COUNT(*) AS jumlah_sesi, 
            IFNULL(SUM(durasi), 0) AS total_durasi
        FROM data_sewa 
        WHERE status = 'finish' AND jenis_ps = '$nama_ps'
    ";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function detailPlaystationUsage($jenis_ps, $status_ps){
    global $conn;
    $query = "SELECT COUNT(*) AS total_per_ps FROM data_sewa WHERE jenis_ps='$jenis_ps' AND status='$status_ps'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    return $data['total_per_ps'];
}


// Data
$keuntungan = total_pemasukan();
$antrean_pending = total_antrean('pending');
$antrean_playing = total_antrean('dipanggil');
$antrean_finish = total_antrean('finish');

$playstations = [
    ["name" => "PlayStation 2", "color" => "secondary", "jenis_ps" => "ps2"],
    ["name" => "PlayStation 3", "color" => "dark", "jenis_ps" => "ps3"],
    ["name" => "PlayStation 4", "color" => "primary", "jenis_ps" => "ps4"],
    ["name" => "PlayStation 5", "color" => "info", "jenis_ps" => "ps5"]
];



foreach ($playstations as $index => $ps) {
    $usageData = getPlaystationUsageData($ps["jenis_ps"]);
    $playstations[$index]["jumlah_sesi"] = $usageData["jumlah_sesi"];
    $playstations[$index]["durasi_total"] = $usageData["total_durasi"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/sweet_alert.js"></script>
    <script src="js/countdown.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background-color: #f8f9fa;
        }
        .nav-link {
            border-radius: 12px;
            transition: background-color 0.3s, color 0.3s;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: #0d6efd !important;
            color: white !important;
        }

        .card-custom {
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body>


<div class="d-flex">
    <!-- Sidebar -->
    <?php include "partials/sidebar.php"; ?>

    <!-- Main Content -->
    <main class="flex-grow-1 p-4" style="margin-left: 250px;padding: 2rem;min-height: 100vh;">
        <h1 class="fw-bold mb-4">Dashboard</h1>
        
        <!-- Pendapatan -->
        <section class="mb-5">
            <h4 class="fw-semibold mb-3"><i class="bi bi-graph-up-arrow me-2"></i>Pendapatan</h4>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card text-bg-success card-custom p-3">
                        <h6 class="mb-1"><i class="bi bi-cash-coin me-2"></i>Total Keuntungan</h6>
                        <h4 class="mb-0">Rp. <?= number_format($keuntungan, 0, ',', '.'); ?></h4>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="mb-5">
            <h4 class="fw-semibold mb-3"><i class="bi bi-clock-history me-2"></i>Status Antrean</h4>
            <div class="row g-4">
                <!-- Menunggu -->
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm bg-warning-subtle text-warning-emphasis position-relative p-4 rounded-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0"><i class="bi bi-hourglass-split me-2"></i>Menunggu</h6>
                            <button class="btn btn-sm btn-outline-warning rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalPending">
                                Detail <i class="bi bi-chevron-right ms-1"></i>
                            </button>
                        </div>
                        <h2 class="display-6 fw-bold"><?= $antrean_pending ?> <span class="fs-6 fw-normal">Antrean</span></h2>
                    </div>
                </div>

                <!-- Sedang Bermain -->
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm bg-primary-subtle text-primary-emphasis position-relative p-4 rounded-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0"><i class="bi bi-controller me-2"></i>Sedang Bermain</h6>
                            <button class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalPlaying">
                                Detail <i class="bi bi-chevron-right ms-1"></i>
                            </button>
                        </div>
                        <h2 class="display-6 fw-bold"><?= $antrean_playing ?> <span class="fs-6 fw-normal">Antrean</span></h2>
                    </div>
                </div>

                <!-- Selesai -->
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm bg-secondary-subtle text-secondary-emphasis position-relative p-4 rounded-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0"><i class="bi bi-check2-circle me-2"></i>Selesai</h6>
                        </div>
                        <h2 class="display-6 fw-bold"><?= $antrean_finish ?> <span class="fs-6 fw-normal">Unit</span></h2>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modal Menunggu -->
        <div class="modal fade" id="modalPending" tabindex="-1" aria-labelledby="modalPendingLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content rounded-4 shadow">
              <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="modalPendingLabel"><i class="bi bi-hourglass-split me-2"></i>Detail Antrian Menunggu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
              </div>
              <div class="modal-body">
                <ul class="list-group list-group-flush">
                    <?php foreach ($playstations as $item): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-person-circle me-2"></i><?= htmlspecialchars($item['name']) ?></span>
                            <span class="badge rounded-pill text-bg-warning"><?= detailPlaystationUsage($item['jenis_ps'], "pending") ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
              </div>
            </div>
          </div>
        </div>
                    
        <!-- Modal Sedang Bermain -->
        <div class="modal fade" id="modalPlaying" tabindex="-1" aria-labelledby="modalPlayingLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content rounded-4 shadow">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalPlayingLabel"><i class="bi bi-controller me-2"></i>Detail Sedang Bermain</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
              </div>
              <div class="modal-body">
                <ul class="list-group list-group-flush">
                    <?php foreach ($playstations as $item): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-person-circle me-2"></i><?= htmlspecialchars($item['name']) ?></span>
                            <span class="badge rounded-pill text-bg-primary"><?= detailPlaystationUsage($item['jenis_ps'], "dipanggil") ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
              </div>
            </div>
          </div>
        </div>

        
        <!-- Penggunaan PS -->
        <section>
            <h4 class="fw-semibold mb-3"><i class="bi bi-display me-2"></i>Data Penggunaan PlayStation</h4>
            <div class="row g-4">
                <?php foreach($playstations as $ps): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm bg-<?= $ps['color'] ?>-subtle text-<?= $ps['color'] ?>-emphasis p-4 rounded-4">
                            <h6 class="fw-bold mb-7"><?=$ps['name'];?></h6>
                            <p class="mb-1">Jumlah sesi: <strong><?= $ps['jumlah_sesi']; ?></strong></p>
                            <p class="mb-0">Total durasi: <strong><?= $ps['durasi_total']; ?></strong> jam</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

</div>
</body>
</html>
