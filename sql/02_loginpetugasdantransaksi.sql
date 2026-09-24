-- akun petugas untuk login sungguhan
CREATE TABLE IF NOT EXISTS petugas (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- simpan hash, JANGAN plaintext
    dibuat_pada TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- data transaksi peminjaman/pengembalian
CREATE TABLE IF NOT EXISTS peminjaman (
    id SERIAL PRIMARY KEY,
    buku_id INTEGER NOT NULL REFERENCES buku(id),
    anggota_id INTEGER NOT NULL REFERENCES anggota(id),
    tanggal_pinjam DATE NOT NULL DEFAULT CURRENT_DATE,
    tanggal_kembali DATE, -- NULL = masih dipinjam
    status VARCHAR(20) NOT NULL DEFAULT 'dipinjam' -- 'dipinjam' atau 'selesai'
);