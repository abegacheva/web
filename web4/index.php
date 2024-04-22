<?php
header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $messages = array();
    if (!empty($_COOKIE['save'])) {
        setcookie('save', '', 100000);
        $messages[] = 'Спасибо, ваши данные сохранены';
    }
    $errors = array();
    $errors['fio'] =!empty($_COOKIE['fio_error']);
    $errors['email'] =!empty($_COOKIE['email_error']);
    $errors['gen'] =!empty($_COOKIE['gen_error']);
    $errors['bio'] =!empty($_COOKIE['bio_error']);
    $errors['tel'] =!empty($_COOKIE['tel_error']);
    $errors['date'] =!empty($_COOKIE['date_error']);
    $errors['symbolfio'] =!empty($_COOKIE['symbolfio_error']);
    $errors['symboltel'] =!empty($_COOKIE['symboltel_error']);
    $errors['languages'] =!empty($_COOKIE['languages_error']);
    $errors['symbemail'] =!empty($_COOKIE['symbemail_error']);
    $errors['languages_unknown'] =!empty($_COOKIE['languages_unknown']);
    $errors['date_value'] =!empty($_COOKIE['date_value_error']);
    $errors['bio_value'] =!empty($_COOKIE['bio_value_error']);
    if ($errors['fio']) {
        setcookie('fio_error', '', 100000);
        $messages[] = '<div class="error">Ошибка в поле "ФИО"</div>';
    }
    if ($errors['email']) {
        setcookie('email_error', '', 100000);
        $messages[] = '<div class="error">Ошибка в поле "E-mail"</div>';
    }
    if ($errors['gen']) {
        setcookie('gen_error', '', 100000);
        $messages[] = '<div class="error">Выберите пол</div>';
    }
    if ($errors['bio']) {
        setcookie('bio_error', '', 100000);
        $messages[] = '<div class="error">Ошибка в поле "Биография"</div>';
    }
    if ($errors['tel']) {
        setcookie('tel_error', '', 100000);
        $messages[] = '<div class="error">Ошибка в поле "Телефон"</div>';
    }
    if ($errors['date']) {
        setcookie('date_error', '', 100000);
        $messages[] = '<div class="error">Ошибка даты</div>';
    }
    if ($errors['symbolfio']) {
        setcookie('symbolfio_error', '', 100000);
        $messages[] = '<div class="error">В поле "ФИО" содержатся недопустимые символы</div>';
    }
    if ($errors['symboltel']) {
        setcookie('symboltel_error', '', 100000);
        $messages[] = '<div class="error">Укажите номер телефона в формате +7 (XXX) XXX-XX-XX.</div>';
    }
    if ($errors['languages']) {
        setcookie('languages_error', '', 100000);
        $messages[] = '<div class="error">Выберите языки</div>';
    }
    if ($errors['symbemail']) {
        setcookie('symbemail_error', '', 100000);
        $messages[] = '<div class="error">Укажите корректный электронный адрес</div>';
    }
    if ($errors['languages_unknown']) {
        setcookie('languages_unknown', '', 100000);
        $messages[] = '<div class="error">Ошибка при добавлении языка</div>';
    }
    if ($errors['date_value']) {
        setcookie('date_value_error', '', 100000);
        $messages[] = '<div class="error">Поле "дата" не в формате d.m.y.</div>';
    }
    if ($errors['bio_value']) {
        setcookie('bio_value_error', '', 100000);
        $messages[] = '<div class="error">В поле "Биография" содержатся недопустимые символы </div>';
    }
    $values = array();
    $values['fio'] = empty($_COOKIE['fio_value']) ? '' : $_COOKIE['fio_value'];
    $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
    $values['tel'] = empty($_COOKIE['tel_value']) ? '' : $_COOKIE['tel_value'];
    $values['gen'] = empty($_COOKIE['gen_value']) ? '' : $_COOKIE['gen_value'];
    $values['bio'] = empty($_COOKIE['bio_value']) ? '' : $_COOKIE['bio_value'];
    $values['date'] = empty($_COOKIE['date_value']) ? '' : $_COOKIE['date_value'];
    include('form.php');
} else {
    $errors = FALSE;
    if (!preg_match("/^[а-я А-Я]+$/u", $_POST['fio'])) {
        setcookie('fio_error', 'ФИО содержит недопустимые символы', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else if (empty($_POST['fio'])) {
        setcookie('fio_error', 'ФИО содержит недопустимые символы', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    setcookie('fio_value', $_POST['fio'], time() + 30 * 24 * 60 * 60);
    if (empty($_POST['email'])) {
        setcookie('email_error', 'Укажите корректный e-mail адрес', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else if (!preg_match("/\b[\w\.-]+@[\w\.-]+\.\w{2,4}\b/", $_POST['email']) or (empty($_POST['email']))) {
        setcookie('email_error', 'Укажите корректный e-mail адрес', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
    if (!preg_match('/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/', $_POST['tel'])) {
        setcookie('tel_error', 'Укажите номер телефона в формате +7 (XXX) XXX-XX-XX', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else if (empty($_POST['tel'])) {
        setcookie('tel_error', 'Укажите номер телефона в формате +7 (XXX) XXX-XX-XX', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    setcookie('tel_value', $_POST['tel'], time() + 30 * 24 * 60 * 60);
    if (empty($_POST['gen']) || ($_POST['gen'] != "f" && $_POST['gen'] != 'm')) {
        setcookie('gen_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    setcookie('gen_value', $_POST['gen'], time() + 365 * 24 * 60 * 60);
    if (empty($_POST['bio'])) {
        setcookie('bio_error', 'В поле "Биография" содержатся недопустимые символы', time() + 24 * 60 * 60);
    } else if (!preg_match('/^[a-zA-Zа-яА-Я0-9,.!? ]+$/', $_POST['bio'])) {
        setcookie('bio_error', 'В поле "Биография" содержатся недопустимые символы', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    setcookie('bio_value', $_POST['bio'], time() + 30 * 24 * 60 * 60);
    $date_format = 'd.m.y';
    $date_timestamp = strtotime($_POST['date']);
    $date_valid = date($date_format, $date_timestamp) === $_POST['date'];
    if (empty($_POST['date'])) {
        setcookie('date_error', 'Указана недопустимая дата', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else if ($date_valid) {
        setcookie('date_error', 'Указана недопустимая дата', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    setcookie('date_value', $_POST['date'], time() + 30 * 24 * 60 * 60);

    if (empty($_POST['languages'])) {
        setcookie('languages_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    } else {
        foreach ($_POST['languages'] as $language) {
            $stmt = $db->prepare("SELECT id FROM languages WHERE id= :id");
            $stmt->bindParam(':id', $language);
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                setcookie('languages_unknown', '1', time() + 24 * 60 * 60);
                $errors = TRUE;
            }
        }
    }
    $languages = $_POST['languages'];
    $languagesString = serialize($languages);
    setcookie('languages', $languagesString, time() + 3600, '/');
    if ($errors) {
        header('Location: index.php');
        exit();
    }
}
if (!$errors) {
    $user = '****'; 
    $pass = '****'; 
    $db = new PDO('mysql:host=localhost;dbname=u67311', $user, $pass);
    $stmt = $db->prepare("INSERT INTO App (FIO, Phone, Email, Birthdate, Gender, Bio, Contract) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $phone, $email, $birthdate, $gender, $bio, $contract]);
    $last_id = $db->lastInsertId();
    if (isset($_POST['programming_languages']) && !empty($_POST['programming_languages'])) {
        foreach ($_POST['programming_languages'] as $language) {
            $stmt = $db->prepare("SELECT ID FROM Ability WHERE ProgrammingLanguage = ?");
            $stmt->execute([$language]);
            $ability_id = $stmt->fetchColumn();
            if ($ability_id !== false && is_numeric($ability_id)) {
                $stmt = $db->prepare("INSERT INTO App_Ability (ApplicationID, AbilityID) VALUES (?, ?)");
                $stmt->execute([$last_id, $ability_id]);
            } else {
                error_log("Язык '$language' Не найден в таблице Abilities");
            }
        }
    }
    setcookie('save', '1');
		header('Location: form.php?save=1');
		exit();
}
?>
