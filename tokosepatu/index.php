<?php
session_start();
include 'koneksi.php';

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query($conn,
    "SELECT * FROM users
     WHERE email='$email'
     AND password='$password'");

    $user = mysqli_fetch_assoc($query);

    if($user){
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        if($user['role']=='admin'){
            header("refresh:2;url=admin/dashboard.php");
        }else{
            header("refresh:2;url=pembeli/dashboard.php");
        }

        $success = true;
    }else{
        $error = "Email atau password salah";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login Toko Baju</title>

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

.login-box{
    width:380px;
    padding:35px;
    border-radius:24px;
    backdrop-filter:blur(18px);
    background:rgba(255,255,255,.12);
    box-shadow:0 10px 40px rgba(0,0,0,.35);
    border:1px solid rgba(255,255,255,.15);
    z-index:2;
}

.login-box h2{
    color:white;
    text-align:center;
    margin-bottom:20px;
    font-size:30px;
}

.login-box p{
    color:#e2e8f0;
    text-align:center;
    margin-top:12px;
}

input{
    width:100%;
    padding:14px;
    margin:10px 0;
    border:none;
    outline:none;
    border-radius:12px;
    background:rgba(255,255,255,.2);
    color:white;
}

input::placeholder{
    color:#e5e7eb;
}

button{
    width:100%;
    padding:14px;
    margin-top:10px;
    border:none;
    border-radius:12px;
    background:linear-gradient(135deg,#2563eb,#60a5fa);
    color:white;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
}

button:hover{
    transform:translateY(-2px);
}

a{
    color:#93c5fd;
    text-decoration:none;
}

/* loading screen */
.loading{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:#020617;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    color:white;
    z-index:9999;
}

.spinner{
    width:60px;
    height:60px;
    border:5px solid rgba(255,255,255,.2);
    border-top:5px solid #60a5fa;
    border-radius:50%;
    animation:spin 1s linear infinite;
    margin-bottom:20px;
}

@keyframes spin{
    100%{
        transform:rotate(360deg);
    }
}
</style>
</head>
<body>

<?php if(isset($success)): ?>
<div class="loading">
<div class="spinner"></div>
<h2>Loading...</h2>
<p>Masuk ke Toko Baju 👕</p>
</div>
<?php endif; ?>

<div class="login-box">
<h2>👕 Toko Baju</h2>

<?php
if(isset($error)){
echo "<p style='color:#fecaca'>$error</p>";
}
?>

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

<button type="submit" name="login">
Login
</button>
</form>

<p>
Belum punya akun?
<a href="register.php">Daftar di sini</a>
</p>

</div>

</body>
</html>