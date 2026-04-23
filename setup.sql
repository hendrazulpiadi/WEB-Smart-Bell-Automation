CREATE DATABASE IF NOT EXISTS db_bel_sekolah;
USE db_bel_sekolah;

CREATE TABLE IF NOT EXISTS sekolah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_sekolah VARCHAR(100) NOT NULL,
    zona_waktu VARCHAR(50) DEFAULT 'Asia/Jakarta'
);

CREATE TABLE IF NOT EXISTS suara (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_suara VARCHAR(100) NOT NULL,
    file_path VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS jadwal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hari VARCHAR(20) NOT NULL,
    waktu TIME NOT NULL,
    kegiatan VARCHAR(100) NOT NULL,
    id_suara INT,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    FOREIGN KEY (id_suara) REFERENCES suara(id) ON DELETE SET NULL
);

-- Insert default data
INSERT INTO sekolah (nama_sekolah, zona_waktu) VALUES ('Sekolah Contoh', 'Asia/Jakarta');
