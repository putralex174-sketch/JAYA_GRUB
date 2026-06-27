<?php

function rupiah($angka)
{
    return "Rp ".number_format($angka,0,",",".");
}

function clean($data)
{
    return htmlspecialchars(trim($data));
}

function redirect($url)
{
    header("Location: ".$url);
    exit;
}

function isLogin()
{
    return isset($_SESSION['user']);
}