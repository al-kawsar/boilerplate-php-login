<?php

function getConnection(): mysqli
{
    return new mysqli('localhost', 'root', '', 'latihan_buatboilerplate_login_php');
}

function isLogin(): bool
{
    if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
        return true;
    }

    if (isset($_COOKIE['X-LOGIN']) && isset($_COOKIE['X-USER']) && !empty($_COOKIE['X-LOGIN']) && !empty($_COOKIE['X-USER'])) {
        $userId = $_COOKIE['X-USER'];
        $x_login = $_COOKIE['X-LOGIN'];

        $checkUser = getUserById($userId);
        if ($checkUser && ($x_login === hash('sha256', $checkUser['email']))) {
            $_SESSION['login'] = true;
            $_SESSION['nama'] = $checkUser['nama'];
            $_SESSION['email'] = $checkUser['email'];
            $_SESSION['role'] = $checkUser['role'];
            return true;
        }
    }

    return false;
}

function getAllUsers(): array
{
    $sql = "SLECT * FROM users";
    $result = getConnection()->query($sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}

function getUserById($id): array
{
    $sql = "SELECT * FROM users WHERE id = '$id'";

    $result = getConnection()->query($sql);

    if ($result->num_rows !== 1) throw new \Exception("Pengguna tidak ditemukan");

    $user = $result->fetch_assoc();
    return $user;
}

function getUserByEmail(string $email): ?array
{
    $sql = "SELECT * FROM users WHERE email = '$email'";

    $result = getConnection()->query($sql);

    if ($result->num_rows !== 1) return null;

    $user = $result->fetch_assoc();
    return $user;
}
