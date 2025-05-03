<?php
include "config/koneksi.php";

$ps_list = [
    "ps2" => "PlayStation 2",
    "ps3" => "PlayStation 3",
    "ps4" => "PlayStation 4",
    "ps5" => "PlayStation 5"
];

// Konfigurasi Pagination
$limit = 6;

// Untuk antrian
$antrian_page = isset($_GET['page_antrian']) ? (int)$_GET['page_antrian'] : 1;
$antrian_offset = ($antrian_page - 1) * $limit;

// Untuk progress
$progress_page = isset($_GET['page_progress']) ? (int)$_GET['page_progress'] : 1;
$progress_offset = ($progress_page - 1) * $limit;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = $_POST['id'];

        if (isset($_POST['panggil'])) {
            $status = 'dipanggil';
            $waktu_panggil = date('Y-m-d H:i:s');  // Mendapatkan waktu sekarang
            $query = "UPDATE data_sewa SET status=?, waktu_panggil=? WHERE id=?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssi", $status, $waktu_panggil, $id);
        } elseif (isset($_POST['finish'])) {
            $status = 'finish';
            $waktu_selesai = date('Y-m-d H:i:s');
            $query = "UPDATE data_sewa SET status=?, waktu_selesai=? WHERE id=?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssi", $status, $waktu_selesai, $id);
        } else {
            echo "Aksi tidak valid!";
            exit();
        }

        $result = mysqli_stmt_execute($stmt);
        if ($result) {
            header("Location: antrian.php?status=success_update");
            exit();
        } else {
            echo "Gagal memperbarui status. Error: " . mysqli_error($conn);
        }
    } else {
        echo "ID tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Antrian</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/sweet_alert.js"></script>
    <script src="js/countdown.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
        }
        .nav-link {
            border-radius: 12px;
            transition: background-color 0.3s, color 0.3s;
        }
        .nav-link:hover,
        .nav-link.active {
            background-color: #0d6efd !important;
            color: white !important;
            border-radius: 12px;
            transition: background-color 0.3s, color 0.3s, border-radius 0.3s;
        }

        .btn {
            border-radius: 20px;
        }
        table {
            border-radius: 12px;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-light text-dark">

<div class="d-flex">
    <!-- Sidebar -->
    <?php include "partials/sidebar.php"; ?>

    <!-- Main Content -->
    <main class="flex-grow-1 p-4" style="margin-left: 250px;padding: 2rem;min-height: 100vh;">
        <div class="container">
            <!-- Antrian -->
            <div class="mb-5">
                <div class="card border-0 shadow rounded-4">
                    <div class="card-body p-4">
                        <h3 class="mb-4 text-center fw-bold">📋 Daftar Antrian</h3>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle text-center rounded-4 overflow-hidden">
                                <thead class="table-primary text-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama</th>
                                        <th>Jenis PS</th>
                                        <th>Durasi</th>
                                        <th>Tagihan</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $no = 1;
                                        $query_read_data = "SELECT * FROM data_sewa WHERE status='pending' LIMIT $limit OFFSET $antrian_offset";
                                        $total_antrian = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM data_sewa WHERE status='pending'"));
                                        $total_antrian_pages = ceil($total_antrian / $limit);

                                        $sql = mysqli_query($conn, $query_read_data);
                                        if (mysqli_num_rows($sql) > 0) {
                                            while($result = mysqli_fetch_assoc($sql)){ 
                                    ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($result['nama']); ?></td>
                                        <td><?= $ps_list[$result['jenis_ps']]; ?></td>
                                        <td><?= $result['durasi']; ?> Jam</td>
                                        <td>Rp <?= number_format($result['tagihan'], 0, ',', '.'); ?></td>
                                        <td><span class="badge bg-secondary text-uppercase"><?= $result['status']; ?></span></td>
                                        <td class="d-flex justify-content-center gap-2">
                                            <form method="POST" action="antrian.php" class="d-inline">
                                                <input type="hidden" name="id" value="<?= $result['id']; ?>">
                                                <button type="submit" name="panggil" class="btn btn-outline-primary btn-sm rounded-pill">
                                                    <i class="bi bi-megaphone">
                                                    </i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-outline-warning btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#updateAntrian<?= $result['id']; ?>">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- Modal -->
                                    <div class="modal fade" id="updateAntrian<?= $result['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="aksi_antrian.php" method="post">
                                                <div class="modal-header">
                                                  <h1 class="modal-title fs-5" id="exampleModalLabel">Form Ubah Data User</h1>
                                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                  <input type="hidden" name="id" value="<?= $result['id'];?>">
                                                  <div class="mb-3">
                                                    <label class="form-label">Nama</label>
                                                    <input class="form-control" type="text" name="nama" value="<?=$result['nama']?>">
                                                  </div>
                                                  <div class="mb-3">
                                                    <label for="" class="form-label">Jenis PlayStation</label>
                                                    <select class="form-select" name="jenis_ps">
                                                        <?php foreach($ps_list as $ps => $value){?>
                                                        <option value="<?= $ps?>" <?= $result['jenis_ps'] == $ps ? 'selected' : '' ?>><?=$value?></option>
                                                        <?php } ?> 
                                                    </select>
                                                  </div>
                                                  <div class="mb-3">
                                                    <label class="form-label" for="">Durasi (Jam)</label>
                                                    <input class="form-control" type="number" name="durasi" value="<?=$result['durasi'];?>">
                                                  </div>
                                                </div>
                                                <div class="modal-footer">
                                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                  <button type="submit" name="update_antrian" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                      </div>
                                    </div>
                                    <?php 
                                        }
                                    } else {
                                    ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted fst-italic">Tidak ada data antrian.</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <nav>
                              <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $total_antrian_pages; $i++): ?>
                                  <li class="page-item <?= $i == $antrian_page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page_antrian=<?= $i; ?>#antrian"><?= $i; ?></a>
                                  </li>
                                <?php endfor; ?>
                              </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Progress -->
            <div>
                <div class="card border-0 shadow rounded-4">
                    <div class="card-body p-4">
                        <h3 class="mb-4 text-center fw-bold">⏳ Progress</h3>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle text-center">
                                <thead class="table-warning text-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama</th>
                                        <th>Jenis PS</th>
                                        <th>Durasi</th>
                                        <th>Status</th>
                                        <th>Waktu Mundur</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    $no = 1;
                                    $query_progress = "SELECT * FROM data_sewa WHERE status='dipanggil' LIMIT $limit OFFSET $progress_offset";
                                    $total_progress = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM data_sewa WHERE status='dipanggil'"));
                                    $total_progress_pages = ceil($total_progress / $limit);
                                    $sql = mysqli_query($conn, $query_progress);
                                    if (mysqli_num_rows($sql) > 0) {
                                        while($result = mysqli_fetch_assoc($sql)){                       
                                ?>
                                    <tr>
                                        <td><?= $no++?></td>
                                        <td><?= $result['nama'];?></td>
                                        <td><?= $ps_list[$result['jenis_ps']];?></td>
                                        <td><?= $result['durasi']; ?> Jam</td>
                                        <td><span class="badge bg-danger">PLAYING</span></td>
                                        <td>
                                            <?php
                                                // Menghitung waktu mundur jika status 'dipanggil'
                                                if ($result['status'] == 'dipanggil') {
                                                    $waktu_mundur_detik = (strtotime($result['waktu_panggil']) + $result['durasi'] * 3600 - time());
                                                
                                                    if ($waktu_mundur_detik > 0) {
                                                        $jam = floor($waktu_mundur_detik / 3600);
                                                        $menit = floor(($waktu_mundur_detik % 3600) / 60);
                                                        $detik = $waktu_mundur_detik % 60;
                                                        
                                                        // Menghasilkan elemen HTML dengan id yang dapat digunakan untuk update waktu mundur
                                                        echo "<span class='countdown' data-timestamp='" . strtotime($result['waktu_panggil']) . "'>" . sprintf("%02d:%02d:%02d", $jam, $menit, $detik) . "</span>";
                                                    } else{
                                                        $id = $result['id'];
                                                        $waktu_selesai = date('Y-m-d H:i:s');
                                                        $query = "UPDATE data_sewa SET status='finish', waktu_selesai='$waktu_selesai' WHERE id='$id'";
                                                        $sql_update_finish = mysqli_query($conn, $query);
                                                        echo 'Selesai';
                                                    }
                                                } else {
                                                    echo '-';
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <form action="antrian.php" method="post" class="d-inline">
                                                <input type="hidden" name="id" value="<?= $result['id'];?>">
                                                <button type="submit" name="finish" class="btn btn-outline-success btn-sm">
                                                    <i class="bi bi-check2-circle"> Finish</i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php }
                                 } else { 
                                ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted fst-italic">Tidak ada data progress.</td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                            <nav>
                              <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $total_progress_pages; $i++): ?>
                                  <li class="page-item <?= $i == $progress_page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page_progress=<?= $i; ?>#progress"><?= $i; ?></a>
                                  </li>
                                <?php endfor; ?>
                              </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

</body>
</html>
