<?php
header('Content-Type: text/html; charset=UTF-8');

$user = '*****'; 
$pass = '*****'; // ваш пароль
$host = '*****';
$dbname = '*****';
$db = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
$session_started = false;
if (isset($_COOKIE[session_name()]) && session_start()) {
  $session_started = true;
  if (!empty($_SESSION['login'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
      session_unset();
      session_destroy();
      header('Location: ./login.php'); 
      exit();
    } else {
      header('Location: ./');
      exit();
    }
  }
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задание 5</title>
    <style>
        body {
            background-color: #ffe6f2;
            color: #663399;
            text-align: center;
        }

        header {
            background-color: #ff99cc;
            padding: 10px;
            margin-bottom: 20px;
        }

        footer {
            background-color: #ff99cc;
            padding: 10px;
            margin-top: 20px;
        }

        .login-form {
            background-color: #ffffff;
            color: #663399;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            display: inline-block;
            text-align: left;
        }

        .login-form input[type="text"],
        .login-form input[type="password"],
        .login-form input[type="submit"] {
            background-color: #ffffff;
            color: #663399;
            padding: 8px;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            border: 1px solid #cc99ff;
        }

        .login-form input[type="text"]:focus,
        .login-form input[type="password"]:focus {
            border: 1px solid transparent;
            outline: none;
        }

        .login-form .submit {
            background-color: #ff66b3;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .login-form .submit:hover {
            background-color: #ff3399;
        }
    </style>
</head>

<body>
    <header>
        <h1>Задание 5</h1>
    </header>
    <div class="login-form">
        <form action="login.php" method="post">
            <label for="login">Логин</label><br>
            <input type="text" id="login" name="login" placeholder="Логин" required /><br>
            <label for="passbyform">Пароль</label><br>
            <input type="password" id="passbyform" name="passbyform" placeholder="Пароль" required /><br>
            <input class="submit" type="submit" value="Войти" />
        </form>
    </div>
    <footer>
        <p>Задание выполнено :)</p>
    </footer>
</body>

</html>
<?php
} else {
    $login = $_POST['login'];
    $passbyform = $_POST['passbyform'];
    $quer = "SELECT * FROM Users WHERE Login = '$login'";

    $stmt = $db->prepare("SELECT Password FROM Users WHERE Login = ?");
    $stmt->execute([$login]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($passbyform, $row['Password'])) {
        setcookie('login', $login, time() + 30 * 24 * 60 * 60); // Логин сохраняется на месяц
        setcookie('pass', $hashed_password, time() + 30 * 24 * 60 * 60); // Хэшированный пароль сохраняется на месяц  
        session_start();
        $_SESSION['login'] = $login;
        $_SESSION['uid'] = $row['ID'];
        setcookie('fio_error', '', 100000);
        setcookie('phone_error', '', 100000);
        setcookie('email_error', '', 100000);
        setcookie('birthdate_error', '', 100000);
        setcookie('gender_error', '', 100000);
        setcookie('bio_error', '', 100000);
        setcookie('contract_error', '', 100000);
        header('Location: ./');
    } else {
        echo "Неверный логин или пароль.";
    }
}
?>
