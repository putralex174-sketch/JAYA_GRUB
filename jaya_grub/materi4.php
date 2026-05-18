<form action="" method="POST">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    Nama: <input type="text" name="nama" required><br>
    Email: <input type="email" name="email" required><br>
    <input type="submit" name="kirim" value="Submit">
</form>

<?php
include "koneksi.php";

/* ================= UPDATE ================= */

if (isset($_POST['update'])) {

    $id       = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama     = $_POST['nama'];
    $email    = $_POST['email'];

    edit_data($koneksi, $id, $username, $password, $nama, $email);

    header("Location: materi4.php");
    exit();
}

/* ================= TAMBAH DATA ================= */

if (isset($_POST['kirim'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama     = $_POST['nama'];
    $email    = $_POST['email'];

    tambah_data($koneksi, $username, $password, $nama, $email);
}

/* ================= TAMPIL DATA ================= */

function tampil_data($koneksi)
{
    $sql = "SELECT * FROM user";
    $query = mysqli_query($koneksi, $sql);

    while ($data = mysqli_fetch_array($query)) {

        echo "<tr>";

        echo "<td>" . $data['username'] . "</td>";
        echo "<td>" . $data['password'] . "</td>";
        echo "<td>" . $data['nama'] . "</td>";
        echo "<td>" . $data['email'] . "</td>";

        echo "<td>";
        echo "<a href='materi4.php?edit=".$data['id']."'>Edit</a> | ";
        echo "<a href='materi4.php?hapus=".$data['id']."'>Delete</a>";
        echo "</td>";

        echo "</tr>";
    }
}

/* ================= TAMBAH DATA ================= */

function tambah_data($koneksi, $username, $password, $nama, $email)
{
    $sql = "INSERT INTO user(username, password, nama, email)
            VALUES('$username','$password','$nama','$email')";

    if (mysqli_query($koneksi, $sql)) {

        echo "Data berhasil ditambahkan!";

    } else {

        echo "Data gagal ditambahkan: " . mysqli_error($koneksi);

    }
}

/* ================= HAPUS DATA ================= */

if (isset($_GET['hapus'])) {

    $id = $_GET['hapus'];

    delete_data($koneksi, $id);

    header("Location: materi4.php");
    exit();
}

/* ================= EDIT DATA ================= */

function edit_data($koneksi, $id, $username, $password, $nama, $email)
{
    $sql = "UPDATE user SET
            username='$username',
            password='$password',
            nama='$nama',
            email='$email'
            WHERE id='$id'";

    if (mysqli_query($koneksi, $sql)) {

        echo "Data berhasil diupdate!";

    } else {

        echo "Data gagal diupdate: " . mysqli_error($koneksi);

    }
}

/* ================= FORM EDIT ================= */

if (isset($_GET['edit'])) {

    $id = $_GET['edit'];

    $sql = "SELECT * FROM user WHERE id='$id'";
    $query = mysqli_query($koneksi, $sql);

    $data = mysqli_fetch_array($query);

    echo "<hr>";
    echo "<h3>Edit Data</h3>";

    echo "<form action='' method='POST'>";

    echo "<input type='hidden' name='id'
          value='".$data['id']."'>";

    echo "Username:
          <input type='text' name='username'
          value='".$data['username']."' required><br>";

    echo "Password:
          <input type='password' name='password'
          value='".$data['password']."' required><br>";

    echo "Nama:
          <input type='text' name='nama'
          value='".$data['nama']."' required><br>";

    echo "Email:
          <input type='email' name='email'
          value='".$data['email']."' required><br>";

    echo "<input type='submit'
          name='update'
          value='Update'>";

    echo "</form>";
}

/* ================= DELETE DATA ================= */

function delete_data($koneksi, $id)
{
    $sql = "DELETE FROM user WHERE id='$id'";

    if (mysqli_query($koneksi, $sql)) {

        echo "Data berhasil dihapus!";

    } else {

        echo "Data gagal dihapus: " . mysqli_error($koneksi);

    }
}
?>

<table border="1" cellpadding="10" cellspacing="0">

<tr>
    <th>Username</th>
    <th>Password</th>
    <th>Nama</th>
    <th>Email</th>
    <th>Aksi</th>
</tr>

<?php tampil_data($koneksi); ?>

</table>