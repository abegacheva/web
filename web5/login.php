<?php
header('Content-Type: text/html; charset=UTF-8');
$user = '*****';
$pass = '*****';
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
<form action="login.php" method="post">
  <input name="login" placeholder="Логин" />
  <input name="passbyform" type="password" placeholder="Пароль" />
  <input type="submit" value="Войти" />
  <button type="submit" name="logout">Выход</button>
</form>
<?php
}
else
	{
	  $login = $_POST['login'];
	  $passbyform = $_POST['passbyform'];
	  $quer = "SELECT * FROM Users WHERE Login = '$login'";
	  $stmt = $db->prepare("SELECT Password FROM Users WHERE Login = ?");
	  $stmt->execute([$login]);
	  $row = $stmt->fetch(PDO::FETCH_ASSOC);
	  if ($row && password_verify($passbyform, $row['Password'])) {
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
		  echo "Неверный логин или пароль";
	  }
	}
?>
