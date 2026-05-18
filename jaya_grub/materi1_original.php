<?php
echo \"Hello World\";
$nama = \"putra\";
$umur = 20;
$tinggi = 175.9;
$hobi = [\"joging\",\"hiking\",\"renang\"];

echo \"Nama saya $nama,Umur saya $umur,Tinggi saya $tinggi,Hobi saya $hobi[0],\";
echo \"<br><br>==================================================================<br><br>\";

//Oprator dan kodisi (if else)

$nilai1 = 10;
$nilai2 = 20;
$hasil1 = $nilai1 + $nilai2 ;
$hasil2 = $nilai1 - $nilai2 ;
$hasil3 = $nilai1 * $nilai2 ;
$hasil4 = $nilai1 / $nilai2 ;

echo \"hasil dari $nilai1 + $nilai2 = $hasil1 \";
echo \"hasil dari $nilai1 - $nilai2 = $hasil2 \";
echo \"hasil dari $nilai1 * $nilai2 = $hasil3 \";
echo \"hasil dari $nilai1 / $nilai2 = $hasil4 \";

echo \"<br><br>==================================================================<br><br>\";
//pengkondisian

$hasil = 98;

if($hasil >= 98 ){
    echo\"Nilai anda A\";
}
else if ($hasil >= 80 ){
    echo\"Nilai anda B\";
}
else if ($hasil >= 65 ){
    echo\"Nilai anda C\";
}
else if ($hasil >= 50 ){
    echo\"Nilai anda D\";
}


echo \"<br><br>==================================================================<br><br>\";
//pengkondisian

$nilai = 3;

if ($nilai % 2 == 0){
    echo \"nilai genap\";
}
else {
    echo \"nilai ganjil\";
}





?>

