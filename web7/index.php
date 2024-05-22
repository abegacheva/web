<?php
function chToken($token){
	return password_verify($_SESSION['token'], $token);
}
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $messages = array();
    if(!empty($_COOKIE['hack'])){
        $messages[]='<div class="error">Ваш аккаунт пытались взломать :( </div>';
        setcookie('hack', '', 100000);
      }
    if (!empty($_COOKIE['save'])) {.
        setcookie('save', '', 100000);
        setcookie('login', '', 100000);
        setcookie('pass', '', 100000);
        $messages[] = 'Спасибо, ваши результаты были сохранены.';.
        if (!empty($_COOKIE['pass'])) {
            $messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
                strip_tags($_COOKIE['login']),
                strip_tags($_COOKIE['pass']));
        }
    }
    $errors = array();
    $errors['name'] = !empty($_COOKIE['name_error']);
    $errors['phone'] = !empty($_COOKIE['phone_error']);
    $errors['email'] = !empty($_COOKIE['email_error']);
    $errors['year'] = !empty($_COOKIE['year_error']);
    $errors['gender'] = !empty($_COOKIE['gender_error']);
    $errors['checkbox'] = !empty($_COOKIE['checkbox_error']);

    if ($errors['name']){
        setcookie('name_error', '', 100000);
        $messages[] = '<div>Заполните имя.</div>';
    }
    if ($errors['phone']) {
        setcookie('phone_error', '', 100000);
        $messages[] = '<div>Некорректный телефон.</div>';
    }
    if ($errors['email']) {
        setcookie('email_error', '', 100000);
        $messages[] = '<div>Некорректный email.</div>';
    }
    if ($errors['year']) {
        setcookie('year_error', '', 100000);
        $messages[] = '<div>Выберите год рождения.</div>';
    }
    if ($errors['gender']) {
        setcookie('gender_error', '', 100000);
        $messages[] = '<div>Выберите пол.</div>';
    }
    if ($errors['checkbox']) {
        setcookie('checkbox_error', '', 100000);
        $messages[] = '<div>Поставьте галочку.</div>';
    }
    $values = array();
    $values['name'] = empty($_COOKIE['name_value']) ? '' : strip_tags($_COOKIE['name_value']);
    $values['phone'] = empty($_COOKIE['phone_value']) ? '' : strip_tags($_COOKIE['phone_value']);
    $values['email'] = empty($_COOKIE['email_value']) ? '' : strip_tags($_COOKIE['email_value']);
    $values['year'] = empty($_COOKIE['year_value']) ? '' : $_COOKIE['year_value'];
    $values['gender'] = empty($_COOKIE['gender_value']) ? '' : $_COOKIE['gender_value'];
    $values['bio'] = empty($_COOKIE['bio_value']) ? '' : strip_tags($_COOKIE['bio_value']);
    $values['checkbox'] = empty($_COOKIE['checkbox_value']) ? '' : $_COOKIE['checkbox_value']; 
    if (empty($_COOKIE['language_value'])) {
        $values['language'] = array();
    } else {
        $values['language'] = json_decode($_COOKIE['language_value'], true);  
    }
    $language = isset($language) ? $language : array();
    session_start();
    if (!empty($_COOKIE[session_name()]) && !empty($_SESSION['login'])) {
        $db = new PDO('mysql:host=localhost;dbname=u67311', 'u67311', '5681522', array(PDO::ATTR_PERSISTENT => true));
        $stmt = $db->prepare("SELECT * FROM App WHERE ID = ?");
        $stmt->execute([$_SESSION['uid']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $values['name'] = strip_tags($row['FIO']);
        $values['phone'] = strip_tags($row['Phone']);
        $values['email'] = strip_tags($row['Email']);
        $values['year'] = $row['Birthdate'];
        $values['gender'] = $row['Gender'];
        $values['bio'] = strip_tags($row['Bio']);
        $values['checkbox'] = $row['Contact'];
        $stmt = $db->prepare("SELECT * FROM App_Ability JOIN Ability ON App_Ability.AbilityID = Ability.ID WHERE ApplicationID = ?");
        $stmt->execute([$_SESSION['uid']]);
        $language = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($language, strip_tags($row['ProgrammingLanguage']));
        }
        $values['language'] = $language;
        printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
    }
    include('form.php');
}
else {
    $errors = FALSE;
	if (!preg_match("/^[a-zA-Zа-яА-Я ]+$/u", $_POST['fio'])) {
		setcookie('fio_error', '1', time() + 24 * 60 * 60);
		$errors = TRUE;
	} else {
		setcookie('fio_value', $_POST['fio'], time() + 30 * 24 * 60 * 60);
	}
	if (!preg_match("/^\+?[0-9]+$/", $_POST['phone'])) {
		setcookie('phone_error', '1', time() + 24 * 60 * 60);
		$errors = TRUE;
	} else {
		setcookie('phone_value', $_POST['phone'], time() + 30 * 24 * 60 * 60);
	}
	if (!preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $_POST['email'])) {
		setcookie('email_error', '1', time() + 24 * 60 * 60);
		$errors = TRUE;
	} else {
		setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
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
    }$errors = FALSE;
	if (!preg_match("/^[a-zA-Zа-яА-Я ]+$/u", $_POST['fio'])) {
		setcookie('fio_error', '1', time() + 24 * 60 * 60);
		$errors = TRUE;
	} else {
		setcookie('fio_value', $_POST['fio'], time() + 30 * 24 * 60 * 60);
	}
	if (!preg_match("/^\+?[0-9]+$/", $_POST['phone'])) {
		setcookie('phone_error', '1', time() + 24 * 60 * 60);
		$errors = TRUE;
	} else {
		setcookie('phone_value', $_POST['phone'], time() + 30 * 24 * 60 * 60);
	}
	if (!preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $_POST['email'])) {
		setcookie('email_error', '1', time() + 24 * 60 * 60);
		$errors = TRUE;
	} else {
		setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
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
    }$errors = FALSE;
	if (!preg_match("/^[a-zA-Zа-яА-Я ]+$/u", $_POST['fio'])) {
		setcookie('fio_error', '1', time() + 24 * 60 * 60);
		$errors = TRUE;
	} else {
		setcookie('fio_value', $_POST['fio'], time() + 30 * 24 * 60 * 60);
	}
	if (!preg_match("/^\+?[0-9]+$/", $_POST['phone'])) {
		setcookie('phone_error', '1', time() + 24 * 60 * 60);
		$errors = TRUE;
	} else {
		setcookie('phone_value', $_POST['phone'], time() + 30 * 24 * 60 * 60);
	}
	if (!preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $_POST['email'])) {
		setcookie('email_error', '1', time() + 24 * 60 * 60);
		$errors = TRUE;
	} else {
		setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
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
    } else {
        setcookie('name_error', '', 100000);
        setcookie('phone_error', '', 100000);
        setcookie('email_error', '', 100000);
        setcookie('year_error', '', 100000);
        setcookie('gender_error', '', 100000);
        setcookie('checkbox_error', '', 100000);
    }
    $token = $_POST['token']? $_POST['token'] : '';
    if(!checkToken($token)&&!empty($_COOKIE[session_name()])){
        setcookie('hack', 1);
        setcookie('name_value', '', 100000);
        setcookie('phone_value', '', 100000);
        setcookie('email_value', '', 100000);
        setcookie('year_value', '', 100000);
        setcookie('gender_value', '', 100000);      
        setcookie('language_value', '', 100000);
        setcookie('bio_value', '', 100000);
        setcookie('checkbox_value', '', 100000);
        header("Location: login.php?do=logout"); 
        exit;
    }
    if (!empty($_COOKIE[session_name()]) &&
        session_start() && !empty($_SESSION['login'])) {
        $db = new PDO('mysql:host=localhost;dbname=u67311', 'u67311', '5681522', array(PDO::ATTR_PERSISTENT => true));
        $stmt = $db->prepare("UPDATE App SET FIO = ?, Phone = ?, Email = ?, Birthdate = ?, Gender = ?, Bio = ?, Contact = ? WHERE ID = ?");
        $stmt->execute([$_POST['name'], $_POST['phone'], $_POST['email'], $_POST['year'], $_POST['gender'], $_POST['bio'], isset($_POST['checkbox']) ? 1 : 0, $_SESSION['uid']]);
        $stmt = $db->prepare("DELETE FROM App_Ability WHERE ApplicationID = ?");
        $stmt->execute([$_SESSION['uid']]);
        foreach ($language as $item) {
            $stmt = $db->prepare("INSERT INTO App_Ability (ApplicationID, AbilityID) VALUES (?, (SELECT ID FROM Ability WHERE ProgrammingLanguage = ? LIMIT 1))");
            $stmt->execute([$_SESSION['uid'], $item]);
        }
    } else {
        $chars = "qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
        $max = rand(8, 16);
        $size = StrLen($chars) - 1;
        $pass = null;
        while ($max--)
            $pass .= $chars[rand(0, $size)];
        $login = $chars[rand(0, 25)] . strval(time());
        setcookie('login', $login);
        setcookie('pass', $pass);
        $db = new PDO('mysql:host=localhost;dbname=u67311', 'u67311', '5681522', array(PDO::ATTR_PERSISTENT => true));
        $stmt = $db->prepare("INSERT INTO App (FIO, Phone, Email, Birthdate, Gender, Bio, Contact) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['name'], $_POST['phone'], $_POST['email'], $_POST['year'], $_POST['gender'], $_POST['bio'], isset($_POST['checkbox']) ? 1 : 0]);
        $res = $db->query("SELECT max(ID) FROM App");
        $row = $res->fetch();
        $count = (int) $row[0];

        foreach ($language as $item) {
            $stmt = $db->prepare("INSERT INTO App_Ability (ApplicationID, AbilityID) VALUES (?, (SELECT ID FROM Ability WHERE ProgrammingLanguage = ? LIMIT 1))");
            $stmt->execute([$count, $item]);
        }

        $stmt = $db->prepare("INSERT INTO Users (ID, Login, Password) VALUES (?, ?, ?)");
        $stmt->execute([$count, $login, md5($pass)]);

        $stmt = $db->prepare("INSERT INTO login_pass (id, login, pass) VALUES (?, ?, ?)");
        $stmt->execute([$count, $login, md5($pass)]);
    }
    etcookie('save_after_change', '1');
    header('Location: form.php?save_after_change=1');
}
?>
