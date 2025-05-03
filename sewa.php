<?php
include "config/koneksi.php";

$ps_list = [
    "ps2" => "PlayStation 2",
    "ps3" => "PlayStation 3",
    "ps4" => "PlayStation 4",
    "ps5" => "PlayStation 5"
];

function read_antrean($jenis_ps){
    global $conn;
    $query = "SELECT COUNT(*) AS total_antrean FROM data_sewa WHERE jenis_ps = '$jenis_ps' AND (status='dipanggil' OR status='pending');";
    $result_data = mysqli_query($conn, $query);
    $row_ps = mysqli_fetch_array($result_data);
    return $row_ps['total_antrean'];
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
        <div class="container">
            <!-- Button Sewa -->
            <div class="mb-4">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="bi bi-plus-circle-dotted"> Rental PlayStation</i>
                </button>
            </div>

            <!-- Modal Form -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="aksi_sewa.php" method="post" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-black">Form Penyewaan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-black">
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" name="nama" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jenis PlayStation</label>
                                <select class="form-select" name="jenis_ps" required>
                                    <option disabled selected>-- Pilih Jenis PlayStation --</option>
                                    <?php foreach ($ps_list as $ps => $value): ?>
                                        <option value="<?= $ps ?>"><?= $value ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Durasi (Jam)</label>
                                <input type="number" class="form-control" name="durasi" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" name="sewa_ps" class="btn btn-primary">Sewa</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Dashboard Cards -->
            <div class="row g-4 mt-4">
                <?php 
                $playstations = [
                    ["name" => "PlayStation 2", "color" => "secondary", "id" => "ps2"],
                    ["name" => "PlayStation 3", "color" => "dark", "id" => "ps3"],
                    ["name" => "PlayStation 4", "color" => "primary", "id" => "ps4"],
                    ["name" => "PlayStation 5", "color" => "info", "id" => "ps5"]
                ];

                foreach ($playstations as $ps): ?>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="card shadow rounded-4 text-center">
                        <div class="card-body">
                            <div class="bg-<?= $ps['color'] ?> text-white rounded-circle d-flex justify-content-center align-items-center mx-auto" style="width: 60px; height: 60px;">
                                <i class="bi bi-playstation fs-3"></i>
                            </div>
                            <h5 class="mt-3"><?= $ps['name'] ?></h5>
                            <p class="badge bg-success mb-1">Unit: 2</p>
                            <p class="badge bg-danger">Antrean: <?= read_antrean($ps['id']); ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</div>


</body>
</html>
