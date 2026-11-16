# Instalasi Rental Armada Bengkulu

1. Ekstrak folder `rental-kendaraan` ke direktori web server (`htdocs` pada XAMPP atau `www` pada Laragon).
2. Buat database MySQL baru bernama `rental_kendaraan`.
3. Import file `database/schema.sql` ke dalam database `rental_kendaraan`.
4. Sesuaikan kredensial database pada `config/database.php` jika diperlukan (default: `root` tanpa password).
5. Buka aplikasi melalui browser di `http://localhost/rental-kendaraan/public`.
