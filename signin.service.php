<?php
require_once('./helper.php');
require_once('./config.php');

// cek http request login
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectTo('sign-in.php');
}
session_start();


$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);
$checkRemember = isset($_POST['remember']) ? htmlspecialchars($_POST['remember']) : false;

if (empty($email) || empty($password)) {
    redirectTo('sign-in.php', 'all fields are required');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirectTo('sign-in.php', 'no email');
    exit;
}

try {
    // cek user berdasarkan email
    $user = getUserByEmail($email);
    if ($user) {
        // check password
        if (password_verify($password, $user['password'])) {

            $expires = time() + ((60 * 60 * 24) * 7);
            // cek user ketika klik remember
            if (isset($checkRemember) && $checkRemember) {
                setcookie('X-USER', $user['id'], $expires);
                setcookie('X-LOGIN', hash('sha256', $user['email']), $expires);
            }

            $_SESSION['login'] = true;
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            redirectTo('./', 'login berhasil');
        } else {
            redirectTo('sign-in.php', 'password salah');
            exit;
        }
    } else {
        redirectTo('sign-in.php', 'Email yang anda masukkan tidak terdaftar');
        exit;
    }
} catch (\Exception $e) {
    redirectTo('sign-in.php', $e->getMessage());
}


/**
  			if($remember)
			{
				$expires = time() + ((60*60*24) * 7);
				$salt = "*&salt#@";
				
				$token_key = hash('sha256', (time() . $salt));
				$token_value = hash('sha256', ('Logged_in' . $salt));

				setcookie('SES',$token_key.':'.$token_value,$expires);
				
				$id = $row['id'];
				$query = "update users set token_key = '$token_key', token_value = '$token_value' ";
				$query .= " where id = '$id' limit 1";
				query($query);
			}

            if($cookie && strstr($cookie, ":")){
		$parts = explode(":", $cookie);
		$token_key = $parts[0];
		$token_value = $parts[1];

		$query = "select * from users where token_key = '$token_key' limit 1";
		$row = query($query);
		if($row)
		{
			$row = $row[0];
			if($token_value == $row['token_value'])
			{
				$_SESSION['SES'] = $row;
				return true;
			}
		}
	}

 
 */
