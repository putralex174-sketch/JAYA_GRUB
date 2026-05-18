<!DOCTYPE html>
<html>
<head><title>Materi 2: Kondisi dan Looping</title></head>
<body>
<form method="POST">
    Masukkan Angka: <input type="number" name="angka" min="1"><br>
    <input type="submit" value="Kirim">
</form>

<?php
if (isset($_POST['angka']) &amp;&amp; is_numeric($_POST['angka'])) {
    $data = (int)$_POST['angka'];
    
    echo "<h3>For Loop:</h3>";
    for ($i = 1; $i <= $data; $i++) {
        echo "Angka $i <br>";
        if ($i % 2 == 0) {
            echo " - genap<br>";
        } else {
            echo " - ganjil<br>";
        }
    }
    
    echo "<h3>While Loop:</h3>";
    $i = 1;
    while ($i <= $data) {
        echo "Angka $i <br>";
        $i++;
    }
}
?>
</body>
</html>
