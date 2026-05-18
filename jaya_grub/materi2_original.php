<form method ="POST">
    Masukkan Angka : <input type="number" name="Angka"><br>
    <input type="submit"value="kirim">
</from>

<?php
if (isset($_POST['ANGKA']))
    $data = $_POST['angka'];
    for ($i = 1; $i <= $data; $i++) 
        echo \"Angka $i <br>\":
    }
    if ($nilai % 2 == 0){
    echo \"nilai genap\";
}
else {
    echo \"nilai ganjil\";
}

?>
//looping while dan dowile 

<?php
if (isset($_POST['angka'])){
    $data = $-POST['angka'];
    $i = 1;
    while ($i <= $data){
        echo \"angka $i <br>\";
        $i++;
    }
}
?>

