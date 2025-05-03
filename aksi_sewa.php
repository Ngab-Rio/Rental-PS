<?php

include "config/koneksi.php";

if(isset($_POST['sewa_ps'])){
    $nama = $_POST['nama'];
    $jenis_ps = $_POST['jenis_ps'];
    $durasi = $_POST['durasi'];
    $tagihan = "";

    if ($jenis_ps == "ps2"){
        $tagihan = 4000 * $durasi;
    }elseif ($jenis_ps == "ps3"){
        $tagihan = 6000 * $durasi;
    }elseif($jenis_ps == "ps4"){
        $tagihan = 10000 * $durasi;
    }else{
        $tagihan = 15000 * $durasi;
    }

    $query_add_data = "INSERT INTO data_sewa (nama, jenis_ps, tagihan, durasi) VALUES ('$nama', '$jenis_ps', '$tagihan', '$durasi')";
    $sql = mysqli_query($conn, $query_add_data);

    header("Location: sewa.php?status=" . ($sql ? "success_create" : "error"));
    exit();
}


?>