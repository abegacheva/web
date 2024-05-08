<?php
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $messages = array();
    if (!empty($_COOKIE['save'])) {
        setcookie('save', '', 100000);
        $messages[] = 'Спасибо, ваши данные были сохранены';
    }
    $errors = array();
    $errors['fio'] = !empty($_COOKIE['fio_error']);
    $errors['phone'] = !empty($_COOKIE['tel_error']);
    $errors['email'] = !empty($_COOKIE['email_error']);
    $errors['birthdate'] = !empty($_COOKIE['date_error']);
    $errors['contract'] = !empty($_COOKIE['contract_error']);

    if ($errors['fio']) {
        $messages[] = '<div class="error">Ошибка в поле "ФИО"</div>';
    }
    if ($errors['tel']) {
        $messages[] = '<div class="error">Ошибка в поле "Телефон"</div>';
    }
    if ($errors['email']) {
        $messages[] = '<div class="error">Ошибка в поле "E-mail"</div>';
    }
    if ($errors['date']) {
        $messages[] = '<div>Ошибка в поле "дата"</div>';
    }
    if ($errors['gen']) {
        $messages[] = '<div>Выберите пол</div>';
    }
    if ($errors['checkbox']) {
        $messages[] = '<div>Проверьте чекбокс</div>';
    }

    $values = array();
    $values['fio'] = empty($_COOKIE['fio_value']) ? '' : $_COOKIE['fio_value'];
    $values['phone'] = empty($_COOKIE['phone_value']) ? '' : $_COOKIE['phone_value'];
    $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
    $values['birthdate'] = empty($_COOKIE['birthdate_value']) ? '' : $_COOKIE['birthdate_value'];
    $values['gender'] = empty($_COOKIE['gender_value']) ? '' : $_COOKIE['gender_value'];
    $values['programming_languages'] = empty($_COOKIE['programming_languages_value']) ? array() : $_COOKIE['programming_languages_value'];
    $values['bio'] = empty($_COOKIE['bio_value']) ? '' : $_COOKIE['bio_value'];
    $values['contract'] = !empty($_COOKIE['contract_value']);
    include('form.php');

    if (empty($_COOKIE['language_value'])) {
        $values['language'] = array();
    } else {
        $values['language'] = json_decode($_COOKIE['language_value'], true);  
    }

    $language = isset($language) ? $language : array();
    session_start();

    if (!empty($_COOKIE[session_name()]) && !empty($_SESSION['login'])) {
        $user = '***'; 
        $passw = '***'; 
        $db = new PDO('mysql:host=localhost;dbname=г67311', $user, $passw);
        
        $stmt = $db->prepare("SELECT * FROM App WHERE ID = ?");
        $stmt->execute([$_SESSION['uid']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $values['fio'] = strip_tags($row['FIO']);
        $values['tel'] = strip_tags($row['Tel']);
        $values['email'] = strip_tags($row['Email']);
        $values['date'] = $row['Birthdate'];
        $values['gen'] = $row['Gen'];
        $values['bio'] = strip_tags($row['Bio']);
        $values['checkbox'] = $row['Contact']; 

        $stmt = $db->prepare("SELECT * FROM App_Ability WHERE ApplicationID = ?");
        $stmt->execute([$_SESSION['uid']]);
        $ability = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($language, strip_tags($row['AbilityID']));
        }
        $values['language'] = $language;

        printf('Вход со следующим логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
    }

    include('form.php');
else {
    $errors = FALSE;
	if (empty(htmlentities($_POST['fio']))) {
        setcookie('fio_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('fio_value', $_POST['fio'], time() + 12 * 30 * 24 * 60 * 60);
    }
	if (!preg_match('/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/', $_POST['tel'])) {
        setcookie('tel_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('tel_value', $_POST['tel'], time() + 30 * 24 * 60 * 60);
    }
	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        setcookie('email_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('email_value', $_POST['email'], time() + 12 * 30 * 24 * 60 * 60);
    }
	if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $_POST['birthdate'])) {
		setcookie('birthdate_error', '1', time() + 24 * 60 * 60);
		$errors = TRUE;
	} else {
		setcookie('birthdate_value', $_POST['birthdate'], time() + 30 * 24 * 60 * 60);
	}
	$gender = $_POST['gender'];
	if (!in_array($gender, ['male', 'female', 'other'])) {
		setcookie('gender_error', '1', time() + 24 * 60 * 60);
		$errors = TRUE;
	} else {
		setcookie('gender_value', $gender, time() + 30 * 24 * 60 * 60);
	}
	$bio = $_POST['bio'];
	if (!preg_match("/^[a-zA-Zа-яА-Я.,! ]+$/u", $bio)) {
		setcookie('bio_error', '1', time() + 24 * 60 * 60);
		$errors = TRUE;
	} else {
		setcookie('bio_value', $bio, time() + 30 * 24 * 60 * 60);
	}
	$contract = isset($_POST['contract']) ? $_POST['contract'] : 0;
	if (!$contract) {
		setcookie('contract_error', '1', time() + 24 * 60 * 60);
		$errors = TRUE;
	} else {
		setcookie('contract_value', '1', time() + 30 * 24 * 60 * 60);
	}
    if ($errors) {
        header('Location: index.php');
        exit();
    }
    else {
		$name = $_POST['fio'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];
		$birthdate = $_POST['birthdate'];
		$gender = $_POST['gender'];
		$bio = $_POST['bio'];
		$contract = isset($_POST['contract']) ? 1 : 0;
		$user = '*****'; 
		$pass = '*****'; 
		$host = '*****';
		$dbname = '*****';
		$db = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
		session_start();
		if (isset($_SESSION['login'])) {
			echo $_SESSION['login'];
			$stmt = $db->prepare("SELECT ID FROM Users WHERE Login = ?");
			$stmt->execute([$_SESSION['login']]);
			$user_row = $stmt->fetch(PDO::FETCH_ASSOC);
			$user_id = $user_row['ID'];
			$stmt = $db->prepare("UPDATE App SET FIO = ?, Phone = ?, Email = ?, Birthdate = ?, Gender = ?, Bio = ?, Contract = ? WHERE UserID = ?");
			$stmt->execute([$name, $phone, $email, $birthdate, $gender, $bio, $contract, $user_id]);
			setcookie('save_after_change', '1');
			header('Location: form.php?save_after_change=1');
			exit();
		} else {
			$login = substr(md5(uniqid(mt_rand(), true)), 0, 8);
			$password = substr(md5(uniqid(mt_rand(), true)), 0, 8);
			$hashed_password = password_hash($password, PASSWORD_DEFAULT);
			echo "Сгенерированный логин: " . $login . "<br>";
			echo "Сгенерированный пароль: " . $password . "<br>";
			echo "Захешированный сгенерированный пароль: " . $hashed_password . "<br>";
			$stmt = $db->prepare("INSERT INTO Users (Login, Password) VALUES (?, ?)");
			$stmt->execute([$login, $hashed_password]);
			$stmt = $db->prepare("SELECT ID FROM Users WHERE Login = ?");
			$stmt->execute([$login]);
			$user_row = $stmt->fetch(PDO::FETCH_ASSOC);
			$user_id = $user_row['ID'];
			$stmt = $db->prepare("INSERT INTO App (FIO, Phone, Email, Birthdate, Gender, Bio, Contract, ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
			$stmt->execute([$name, $phone, $email, $birthdate, $gender, $bio, $contract, $user_id]);
			$last_id = $db->lastInsertId();
			if (isset($_POST['programming_languages'])) {
				foreach ($_POST['programming_languages'] as $language) {
					$stmt_select = $db->prepare("SELECT ID FROM Ability WHERE ProgrammingLanguage = ?");
					$stmt_select->execute([$language]);
					$ability_row = $stmt_select->fetch(PDO::FETCH_ASSOC);
					$ability_id = $ability_row['ID'];
					$stmt_insert = $db->prepare("INSERT INTO App_Ability (ApplicationID, AbilityID) VALUES (?, ?)");
					$stmt_insert->execute([$last_id, $ability_id]);
				}
			}
			if (!isset($_COOKIE['login']) || !isset($_COOKIE['pass'])) {
				setcookie('login', $login, time() + 30 * 24 * 60 * 60);
				setcookie('pass', $hashed_password, time() + 30 * 24 * 60 * 60); 
				$success_message = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong> и паролем <strong>%s</strong> для изменения данных.',
					strip_tags($login),
					strip_tags($password));
					setcookie('save', '1');
				header('Location: form.php?save=1&success_message=' . urlencode($success_message));
				exit();
			} else {
				setcookie('save_after_change', '1');
				header('Location: form.php?save_after_change=1');
				exit();
			}
		}
	}
}
