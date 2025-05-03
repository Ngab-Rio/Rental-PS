<?php

include "config/koneksi.php";

if(isset($_POST['update_antrian'])){
    $id = $_POST['id'];
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

    $query_update_data = "UPDATE data_sewa SET nama='$nama', jenis_ps='$jenis_ps', durasi='$durasi', tagihan='$tagihan' WHERE id='$id'";
    $exec_sql = mysqli_query($conn, $query_update_data);

    header("Location: antrian.php?status=" . ($exec_sql ? "success_create" : "error"));
    exit();
}

?>