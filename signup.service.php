<?php

require_once('./helper.php');
require_once('./config.php');

session_start();

// cek http request register
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return redirectTo('sign-up.php', 'no post');
}

$name = htmlspecialchars($_POST['name']);
$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);
$passconfirm = htmlspecialchars($_POST['password1']);

// validasi form

if (empty($name) || empty($email) || empty($password) || empty($passconfirm)) {
    return redirectTo('sign-up.php', 'all fields are required');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return redirectTo('sign-up.php', 'no email');
    exit;
}

if ($password !== $passconfirm) {
    return redirectTo('sign-up.php', 'no pass');
    exit;
}

$user = getUserByEmail($email);
if ($user !== null) {
    $_SESSION['msg'] = [
        'type' => 'error',
        'text' => 'Email sudah di ambil'
    ];
    redirectTo('sign-up.php');
}



// hash password

$password = password_hash($password, PASSWORD_BCRYPT);

$id = uuid();

$SQL = "INSERT INTO users VALUES ('$id','$name','$email','$password','user')";

$result = getConnection()->query($SQL);

if ($result) {
    return redirectTo('sign-in.php', 'Registration successful!');
} else {
    echo 'Failed to register.';
}

getConnection()->close();
