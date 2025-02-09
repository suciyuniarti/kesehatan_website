<?php include "../includes/header.php"; ?>
<?php
// Fungsi untuk menghitung kebutuhan kalori
function hitungKalori($gender, $usia, $berat, $tinggi, $aktivitas) {
    // Menghitung BMR (Basal Metabolic Rate)
    if ($gender == "Laki-laki") {
        $bmr = 88.36 + (13.4 * $berat) + (4.8 * $tinggi) - (5.7 * $usia);
    } else {
        $bmr = 447.6 + (9.2 * $berat) + (3.1 * $tinggi) - (4.3 * $usia);
    }

    // Faktor aktivitas
    $faktor = [
        "sedentary" => 1.2,  // Jarang olahraga
        "light" => 1.375,    // Olahraga ringan 1-3 hari/minggu
        "moderate" => 1.55,  // Olahraga sedang 3-5 hari/minggu
        "active" => 1.725,   // Olahraga berat 6-7 hari/minggu
        "very_active" => 1.9 // Olahraga sangat berat, atlet
    ];

    return round($bmr * $faktor[$aktivitas]);
}

// Jika form dikirim, proses perhitungan
$hasil_kalori = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gender = $_POST["gender"];
    $usia = $_POST["usia"];
    $berat = $_POST["berat"];
    $tinggi = $_POST["tinggi"];
    $aktivitas = $_POST["aktivitas"];

    $hasil_kalori = hitungKalori($gender, $usia, $berat, $tinggi, $aktivitas);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Kebutuhan Kalori</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h2>Cek Kebutuhan Kalori Harian</h2>
    <form method="POST" action="">
        <label for="gender">Jenis Kelamin:</label>
        <select name="gender" required>
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
        </select>

        <label for="usia">Usia (tahun):</label>
        <input type="number" name="usia" required>

        <label for="berat">Berat Badan (kg):</label>
        <input type="number" name="berat" required>

        <label for="tinggi">Tinggi Badan (cm):</label>
        <input type="number" name="tinggi" required>

        <label for="aktivitas">Tingkat Aktivitas:</label>
        <select name="aktivitas" required>
            <option value="sedentary">Jarang olahraga</option>
            <option value="light">Olahraga ringan</option>
            <option value="moderate">Olahraga sedang</option>
            <option value="active">Olahraga berat</option>
            <option value="very_active">Atlet</option>
        </select>

        <button type="submit">Hitung Kalori</button>
    </form>

    <?php if ($hasil_kalori): ?>
        <h3>Hasil Perhitungan</h3>
        <p>Kebutuhan kalori harian Anda: <strong><?= $hasil_kalori; ?> kcal</strong></p>
    <?php endif; ?>
</body>

<?php include "../includes/footer.php"; ?> 

</html>
