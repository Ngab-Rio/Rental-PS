CREATE DATABASE IF NOT EXISTS rental_ps;

USE rental_ps;

CREATE TABLE IF NOT EXISTS data_sewa (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL,
    jenis_ps TEXT NOT NULL,
    durasi INT(11) NOT NULL,
    tagihan INT(10) NOT NULL,
    status ENUM('pending', 'dipanggil', 'finish') DEFAULT 'pending',
    waktu_panggil DATETIME DEFAULT NULL,
    waktu_selesai DATETIME DEFAULT NULL
);
