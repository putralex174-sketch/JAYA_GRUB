<?php
include 'koneksi.php';

if(isset($_POST['register'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    mysqli_query($conn,
    "INSERT INTO users(email,password,role)
     VALUES('$email','$password','customer')");

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Register Toko Baju</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Segoe UI;
}

body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:
radial-gradient(circle at top left,#4f46e5,#1e3a8a),
    radial-gradient(circle at bottom right,#ec4899,#7f1d1d);
    overflow:hidden;
}

/* efek cahaya */
body::before,
body::after{
    content:'';
    position:absolute;
    width:350px;
    height:350px;
    border-radius:50%;
    filter:blur(120px);
}

body::before{
    background:#3b82f6;
    top:-100px;
    left:-100px;
}

body::after{
    background:#60a5fa;
    bottom:-100px;
    right:-100px;
}

/* box register */
.register-box{
    width:390px;
    padding:35px;
    border-radius:24px;
    backdrop-filter:blur(18px);
    background:rgba(255,255,255,.12);
    box-shadow:0 10px 40px rgba(0,0,0,.35);
    border:1px solid rgba(255,255,255,.15);
    z-index:2;
}

.register-box h2{
    color:white;
    text-align:center;
    margin-bottom:20px;
    font-size:30px;
}

input{
    width:100%;
    padding:14px;
    margin:10px 0;
    border:none;
    outline:none;
    border-radius:12px;
    background:rgba(255,255,255,.18);
    color:white;
    font-size:15px;
}

input::placeholder{
    color:#e5e7eb;
}

button{
    width:100%;
    padding:14px;
    margin-top:12px;
    border:none;
    border-radius:12px;
    background:linear-gradient(135deg,#2563eb,#60a5fa);
    color:white;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
}

button:hover{
    transform:translateY(-2px);
}

p{
    color:#e5e7eb;
    text-align:center;
    margin-top:18px;
}

a{
    color:#93c5fd;
    text-decoration:none;
    font-weight:600;
}
</style>
</head>
<body>

<div class="register-box">

<h2>👕 Register Toko Baju</h2>

<form method="POST">

<input
type="email"
name="email"
placeholder="Masukkan Email"
required
>

<input
type="password"
name="password"
placeholder="Masukkan Password"
required
>

<button
type="submit"
name="register"
>
Daftar Sekarang
</button>

</form>

<p>
Sudah punya akun?
<a href="index.php">Login di sini</a>
</p>

</div>

</body>
</html>