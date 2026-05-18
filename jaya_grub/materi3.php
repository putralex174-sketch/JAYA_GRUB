<?php
function hellow()
{
    echo "Selamat datang <br>";
}

hellow();

function tambah(int $a, int $b)
{
    return $a + $b;
}

function kali(int $a, int $b)
{
    return $a * $b;
}
?>

<form method="POST">
    <input type="number" name="a" placeholder="Angka 1">
    <input type="number" name="b" placeholder="Angka 2">
    <input type="submit" value="Kirim">
</form>

<?php
if (isset($_POST['a']) && isset($_POST['b'])) {
    $angka1 = $_POST['a'];
    $angka2 = $_POST['b'];

    echo "Hasil tambah: " . tambah($angka1, $angka2);
    echo "<br>";
    echo "Hasil kali: " . kali($angka1, $angka2);
}
?>
<?php
function login($user, $pass)
{
    $userBenar = "putra";
    $passBenar = "123";

    if ($user == $userBenar && $pass == $passBenar) {
        return "Login berhasil";
    } else {
        return "Login gagal";
    }
}
?>

<form method="POST">
    <input type="text" name="username" placeholder="Username">
    <br><br>
    <input type="password" name="password" placeholder="Password">
    <br><br>
    <input type="submit" value="Login">
</form>

<?php
if (isset($_POST['username']) && isset($_POST['password'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];

    echo login($u, $p);
}
?>