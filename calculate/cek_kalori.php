<?php include "../includes/header.php"; ?>

<?php
// Fungsi untuk menghitung kebutuhan kalori harian berdasarkan BMR + Aktivitas
function calculateDailyCaloricNeeds(int $age, int $weight, int $height, string $gender, string $activityLevel): int|false {
    if ($age <= 0 || $weight <= 0 || $height <= 0) {
        return false;
    }

    // Menghitung BMR berdasarkan jenis kelamin
    $bmr = ($gender === "Laki-laki")
        ? 88.36 + (13.4 * $weight) + (4.8 * $height) - (5.7 * $age)
        : 447.6 + (9.2 * $weight) + (3.1 * $height) - (4.3 * $age);

    // Faktor aktivitas
    $activityFactors = [
        "sedentary" => 1.2,       
        "light" => 1.375,         
        "moderate" => 1.55,       
        "active" => 1.725,        
        "very_active" => 1.9      
    ];

    return isset($activityFactors[$activityLevel]) ? round($bmr * $activityFactors[$activityLevel]) : false;
}

// Data aktivitas untuk dropdown
$activityFactors = [
    "sedentary" => "Jarang olahraga",
    "light" => "Olahraga ringan",
    "moderate" => "Olahraga sedang",
    "active" => "Olahraga berat",
    "very_active" => "Atlet"
];

$hasil_kalori = null;
$gender = $usia = $berat = $tinggi = $aktivitas = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usia = filter_input(INPUT_POST, "usia", FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    $berat = filter_input(INPUT_POST, "berat", FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    $tinggi = filter_input(INPUT_POST, "tinggi", FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    $gender = $_POST["gender"] ?? "";
    $aktivitas = $_POST["aktivitas"] ?? "";

    if ($usia && $berat && $tinggi && in_array($gender, ["Laki-laki", "Perempuan"]) && array_key_exists($aktivitas, $activityFactors)) {
        $hasil_kalori = calculateDailyCaloricNeeds($usia, $berat, $tinggi, $gender, $aktivitas);
    } else {
        $hasil_kalori = false;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Hitung kebutuhan kalori harian Anda berdasarkan usia, berat, tinggi, dan aktivitas.">
    <title>Cek Kebutuhan Kalori</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
    .footer {
        margin-top: 20px;
        font-size: 14px;
        color: #555;
        text-align: center;
    }
    </style>
</head>
<body>
    <div class="container">
        <div class="kalori-box">
            <h2>Cek Kebutuhan Kalori Harian</h2>
            <form method="POST" action="" class="kalori-form">
                <label for="gender">Jenis Kelamin:</label>
                <select id="gender" name="gender" required>
                    <option value="Laki-laki" <?= ($gender === "Laki-laki") ? "selected" : ""; ?>>Laki-laki</option>
                    <option value="Perempuan" <?= ($gender === "Perempuan") ? "selected" : ""; ?>>Perempuan</option>
                </select>

                <label for="usia">Usia (tahun):</label>
                <input type="number" id="usia" name="usia" min="1" required value="<?= htmlspecialchars($usia ?? ''); ?>">

                <label for="berat">Berat Badan (kg):</label>
                <input type="number" id="berat" name="berat" min="1" required value="<?= htmlspecialchars($berat ?? ''); ?>">

                <label for="tinggi">Tinggi Badan (cm):</label>
                <input type="number" id="tinggi" name="tinggi" min="1" required value="<?= htmlspecialchars($tinggi ?? ''); ?>">

                <label for="aktivitas">Tingkat Aktivitas:</label>
                <select id="aktivitas" name="aktivitas" required>
                    <?php foreach ($activityFactors as $key => $label): ?>
                        <option value="<?= $key; ?>" <?= ($aktivitas === $key) ? "selected" : ""; ?>><?= $label; ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Hitung Kalori</button>
            </form>

            <?php if ($hasil_kalori !== null): ?>
                <div class="result">
                    <?php if ($hasil_kalori === false): ?>
                        <div class="error">
                            <p>Pastikan semua input valid:</p>
                            <ul>
                                <?php if (!$usia) echo "<li>Usia harus lebih dari 0.</li>"; ?>
                                <?php if (!$berat) echo "<li>Berat badan harus lebih dari 0.</li>"; ?>
                                <?php if (!$tinggi) echo "<li>Tinggi badan harus lebih dari 0.</li>"; ?>
                                <?php if (!in_array($gender, ["Laki-laki", "Perempuan"])) echo "<li>Pilih jenis kelamin yang valid.</li>"; ?>
                                <?php if (!array_key_exists($aktivitas, $activityFactors)) echo "<li>Pilih tingkat aktivitas yang valid.</li>"; ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <p class="hasil-text">Kebutuhan kalori harian Anda: <strong><?= htmlspecialchars($hasil_kalori); ?> kcal</strong></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
<?php include "../includes/footer.php"; ?>
</html>
