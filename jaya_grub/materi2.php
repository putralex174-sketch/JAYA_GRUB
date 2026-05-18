<form method ="POST">&#10;    Masukkan Angka : <input type="number" name="Angka"><br>&#10;    <input type="submit"value="kirim">&#10;</from>&#10;&#10;<?php&#10;if (isset($_POST[&apos;ANGKA&apos;]))&#10;    $data = $_POST[&apos;angka&apos;];&#10;    for ($i = 1; $i <= $data; $i++) &#10;        echo "Angka $i <br>":&#10;    }&#10;    if ($nilai % 2 == 0){&#10;    echo "nilai genap";&#10;}&#10;else {&#10;    echo "nilai ganjil";&#10;}&#10;&#10;?>&#10;//looping while dan dowile &#10;&#10;<?php&#10;if (isset($_POST[&apos;angka&apos;])){&#10;    $data = $-POST[&apos;angka&apos;];&#10;    $i = 1;&#10;    while ($i <= $data){&#10;        echo "angka $i <br>";&#10;        $i++;&#10;    }&#10;}&#10;?>
    Masukkan Angka : <input type="number" name="angka"><br>
    <input type="submit" value="kirim">
</form>

<?php
if (isset($_POST['angka'])){
    $data = $_POST['angka'];

    for($i=1; $i<=$data; $i++){
        echo "angka $i <br>";
    }

    // pindahkan ke dalam IF agar tidak error
    if ($data % 2 == 0){
        echo "Nilai Genap<br>";
    }
    else {
        echo "Nilai Ganjil<br>";
    }
}
?>

<br>//Looping While & Do While

<?php
echo "<br> Perulangan While<br>";

if (isset($_POST['angka'])) {
    $data = $_POST['angka'];
    $i = 1;

    while ($i <= $data) {
        echo "Angka $i <br>";
        $i++;
    }

    //Looping DO WHILE
    echo "<br> Perulangan Do While<br>";
    $i = 1;
    do {
        echo "Angka $i <br>";
        $i++;
    } while ($i <= $data);
}
?>