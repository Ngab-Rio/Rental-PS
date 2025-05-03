<?php
$current_page = basename($_SERVER['PHP_SELF']);

function isActive($page) {
    global $current_page;
    return $current_page === $page ? 'active text-white bg-primary' : 'text-dark';
}
?>

<aside class="bg-white shadow-sm vh-100 p-4 d-none d-md-block" style="width: 250px; position: fixed;">
    <h4 class="text-center fw-bold border-bottom pb-3 mb-4">🎮 PlayStation Rental</h4>
    <nav>
        <ul class="nav flex-column gap-2">
            <li class="nav-item">
                <a class="nav-link <?php echo isActive('index.php'); ?> d-flex align-items-center fw-semibold" href="index.php">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo isActive('antrian.php'); ?> d-flex align-items-center fw-semibold" href="antrian.php">
                    <i class="bi bi-list-check me-2"></i> Antrian
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo isActive('sewa.php'); ?> d-flex align-items-center fw-semibold" href="sewa.php">
                    <i class="bi bi-person-check me-2"></i> Sewa
                </a>
            </li>
        </ul>
    </nav>
</aside>
