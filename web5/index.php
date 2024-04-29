<?php
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $messages = array();
    if (!empty($_COOKIE['save'])) {
        setcookie('save', '', 100000);
        setcookie('login', '', 100000);
        setcookie('pass', '', 100000);
        $messages[] = 'Спасибо, ваши данные сохранены';
        if (!empty($_COOKIE['pass'])) {
            $messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
                strip_tags($_COOKIE['login']),
                strip_tags($_COOKIE['pass']));
        }
    }
    $errors = array();
    $errors['fio'] = !empty($_COOKIE['fio_error']);
    $errors['tel'] = !empty($_COOKIE['tel_error']);
    $errors['email'] = !empty($_COOKIE['email_error']);
    $errors['date'] = !empty($_COOKIE['date_error']);
    $errors['gen'] = !empty($_COOKIE['gen_error']);
    $errors['checkbox'] = !empty($_COOKIE['checkbox_error']);

    if ($errors['fio']) {
        setcookie('fio_error', '', 100000);
        $messages[] = '<div class="error">Ошибка в поле "ФИО"</div>';
    }
    if ($errors['tel']) {
        setcookie('tel_error', '', 100000);
        $messages[] = '<div class="error">Ошибка в поле "Телефон"</div>';
    }
    if ($errors['email']) {
        setcookie('email_error', '', 100000);
        $messages[] = '<div class="error">Ошибка в поле "E-mail"</div>';
    }
    if ($errors['date']) {
        setcookie('date_error', '', 100000);
        $messages[] = '<div>Ошибка в поле "дата"</div>';
    }
    if ($errors['gen']) {
        setcookie('gen_error', '', 100000);
        $messages[] = '<div>Выберите пол</div>';
    }
    if ($errors['checkbox']) {
        setcookie('checkbox_error', '', 100000);
        $messages[] = '<div>Проверьте чекбокс</div>';
    }
    $values = array();
    $values['fio'] = empty($_COOKIE['fio_value']) ? '' : strip_tags($_COOKIE['fio_value']);
    $values['tel'] = empty($_COOKIE['tel_value']) ? '' : strip_tags($_COOKIE['tel_value']);
    $values['email'] = empty($_COOKIE['email_value']) ? '' : strip_tags($_COOKIE['email_value']);
    $values['date'] = empty($_COOKIE['date_value']) ? '' : $_COOKIE['date_value'];
    $values['gen'] = empty($_COOKIE['gen_value']) ? '' : $_COOKIE['gen_value'];
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
        $user = '****'; 
        $passw = '****'; 
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
}
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
    if (empty($_POST['date'])) {
        setcookie('date_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('date_value', $_POST['date'], time() + 12 * 30 * 24 * 60 * 60);
    }
    if (empty($_POST['gen'])) {
        setcookie('gen_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('gen_value', $_POST['gen'], time() + 12 * 30 * 24 * 60 * 60);
    }
    if (empty($_POST['checkbox'])) {
        setcookie('checkbox_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        setcookie('checkbox_value', $_POST['checkbox'], time() + 12 * 30 * 24 * 60 * 60);
    }
    if (isset($_POST['language'])) {
        $language = $_POST['language'];
    } else {
        $language = array(); 
    }
    if (!empty($_POST['bio'])) {
        setcookie ('bio_value', $_POST['bio'], time() + 12 * 30 * 24 * 60 * 60);
    }
    if (!empty($_POST['language'])) {
        $json = json_encode($_POST['language']);
        setcookie ('language_value', $json, time() + 12 * 30 * 24 * 60 * 60);
    }

    if ($errors) {
        header('Location: index.php');
        exit();
    } else {
        setcookie('fio_error', '', 100000);
        setcookie('tel_error', '', 100000);
        setcookie('email_error', '', 100000);
        setcookie('date_error', '', 100000);
        setcookie('gen_error', '', 100000);
        setcookie('checkbox_error', '', 100000);
    }

    if (!empty($_COOKIE[session_name()]) &&
        session_start() && !empty($_SESSION['login'])) {
        $db = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');
        
        $stmt = $db->prepare("UPDATE App SET FIO = ?, Tel = ?, Email = ?, Birthdate = ?, Gen = ?, Bio = ? WHERE ID = ?");
        $stmt->execute([$_POST['fio'], $_POST['tel'], $_POST['email'], $_POST['date'], $_POST['gen'], $_POST['bio'], $_SESSION['uid']]);

        $stmt = $db->prepare("DELETE FROM App_Ability WHERE ApplicationID = ?");
        $stmt->execute([$_SESSION['uid']]);

        $ability = $_POST['language'];

        foreach ($language as $item) {
            $stmt = $db->prepare("INSERT INTO App_Ability (ApplicationID, AbilityID) VALUES (?, ?)");
            $stmt->execute([$_SESSION['uid'], $item]);
        }
    } else {
        $chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
        $max=rand(8,16);
        $size=StrLen($chars)-1;
        $pass=null;
        while($max--)
            $pass.=$chars[rand(0,$size)];
        $login = $chars[rand(0,25)] . strval(time());
        setcookie('login', $login);
        setcookie('pass', $pass);
        $db = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');

        $stmt = $db->prepare("INSERT INTO App (FIO, Tel, Email, Birthdate, Gen, Bio, Contact) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['fio'], $_POST['Tel'], $_POST['email'], $_POST['date'], $_POST['gen'], $_POST['bio'], $_POST['checkbox']]);
        
        $res = $db->query("SELECT MAX(ID) FROM App");
        $row = $res->fetch();
        $count = (int) $row[0];
        $ability = $_POST['language'];
        foreach ($language as $item) {
            $stmt = $db->prepare("INSERT INTO App_Ability (ApplicationID, AbilityID) VALUES (?, ?)");
            $stmt->execute([$count, $item]);
        }
        // Запись в таблицу login_pass
        $stmt = $db->prepare("INSERT INTO login_pass (id, login, pass) VALUES (?, ?, ?)");
        $stmt->execute([$count, $login, md5($pass)]);
    }
    setcookie('save', '1');
    header('Location: ./');
}
?>
