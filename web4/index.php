<?php
$errors = [];
$fio = isset($_POST['fio']) ? $_POST['fio'] : (isset($_COOKIE['fio']) ? $_COOKIE['fio'] : '');
$phone = isset($_POST['phone']) ? $_POST['phone'] : (isset($_COOKIE['phone']) ? $_COOKIE['phone'] : '');
$email = isset($_POST['email']) ? $_POST['email'] : (isset($_COOKIE['email']) ? $_COOKIE['email'] : '');
$birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : (isset($_COOKIE['birthdate']) ? $_COOKIE['birthdate'] : '');
$gender = isset($_POST['gender']) ? $_POST['gender'] : (isset($_COOKIE['gender']) ? $_COOKIE['gender'] : '');
$bio = isset($_POST['bio']) ? $_POST['bio'] : (isset($_COOKIE['bio']) ? $_COOKIE['bio'] : '');
$programming_languages = isset($_POST['programming_languages']) ? $_POST['programming_languages'] : (isset($_COOKIE['programming_languages']) ? $_COOKIE['programming_languages'] : []);

if (isset($_POST['submit'])) {
    if (empty($fio)) {
        $errors[] = 'Поле "ФИО" обязательно для заполнения.';
    } elseif (!preg_match('/^[а-яА-ЯёЁa-zA-Z ]+$/', $fio)) {
        $errors[] = 'Поле "ФИО" должно содержать только буквы и пробелы.';
    } elseif (strlen($fio) > 150) {
        $errors[] = 'Поле "ФИО" не должно быть длиннее 150 символов.';
    }
    if (empty($phone)){
        $errors[] = 'Поле "Телефон" обязательно для заполнения.';
    } elseif (!preg_match("/^\+?[0-9\s]+$/", $phone)) {
        $errors[] = 'Телефон должен содержать только цифры и пробелы';
    }
    if (empty($email)){
        $errors[] = 'Поле "E-mail" обязательно для заполнения.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'E-mail должен быть действительным адресом электронной почты';
    }
    if (empty($birthdate) || !preg_match("/^\d{4}-\d{2}-\d{2}$/", $birthdate)) {
        $errors[] = 'Дата рождения должна быть в формате ГГГГ-ММ-ДД';
    }
    if (!isset($gender) || !in_array($gender, ["male", "female"])) {
        $errors[] = "Выберите пол";
    }
    if (empty($programming_languages)) {
        $errors[] = "Выберите хотя бы один любимый язык программирования";
    }
    foreach ($programming_languages as $language_id) {
        if (!in_array($language, ["pascal", "c", "cpp", "javascript", "php", "python", "java", "haskell", "clojure", "prolog", "scala"])) {
            $errors[] = "Неверный язык программирования";
        }
    }
    if (empty($bio)) {
        $errors[] = "Заполните биографию";
    }

    if (empty($errors)) {
        // Сохранение значений в Cookies
        setcookie('fio', $fio, time() + (3600 * 24 * 365)); // 1 year
        setcookie('phone', $phone, time() + (3600 * 24 * 365)); // 1 year
        setcookie('email', $email, time() + (3600 * 24 * 365)); // 1 year
        setcookie('birthdate', $birthdate, time() + (3600 * 24 * 365)); // 1 year
        setcookie('gender', $gender, time() + (3600 * 24 * 365)); // 1 year
        setcookie('bio', $bio, time() + (3600 * 24 * 365)); // 1 year
        setcookie('programming_languages', serialize($programming_languages), time() + (3600 * 24 * 365)); // 1 year

        // Обработка формы
        $user = 'u67311';
        $pass = '5681522';
        $db = new PDO('mysql:host=127.0.0.1;dbname=u67311', $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $db->prepare("INSERT INTO users(fio, phone, email, birthdate, gender, bio) VALUES(:fio, :phone, :email, :birthdate, :gender, :bio)");
            $stmt->execute(array('fio' => $fio, 'phone' => $phone, 'email' => $email, 'birthdate' => $birthdate, 'gender' => $gender, 'bio' => $bio));
            $user_id = $db->lastInsertId();
            foreach ($programming_languages as $language_id) {
                $query = "INSERT INTO user_programming_languages(user_id, programming_language_id) VALUES(?, ?)";
                $stmt = $db->prepare($query);
                $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
                $stmt->bindParam(2, $language_id, PDO::PARAM_INT);
                $stmt->execute();
            }
            // Успешная отправка формы, перенаправление на другую страницу
            header("Location: success.php");
            exit();
        } catch (PDOException $e) {
            $errors[] = 'Ошибка при добавлении пользователя: ' . $e->getMessage();
        }
    }
}

// Если есть ошибки, сохраняем их в Cookies
if (!empty($errors)) {
    setcookie('form_errors', serialize($errors), 0); // до конца сессии
    setcookie('form_values', serialize($_POST), 0); // до конца сессии
    // Перезагрузка страницы методом GET
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
// Отображение сообщений об ошибках над формой
if (!empty($errors)) {
    echo '<div style="color: red; margin-bottom: 10px;">';
    echo 'Пожалуйста, исправьте следующие ошибки:';
    echo '<ul>';
    foreach ($errors as $error) {
        echo '<li>' . $error . '</li>';
    }
    echo '</ul>';
    echo '</div>';
}
// Функция для подсветки полей с ошибками
function highlightError($field_name) {
    if (isset($_COOKIE['form_errors']) && is_array($_COOKIE['form_errors']) && in_array($field_name, $_COOKIE['form_errors'])) {
        echo ' style="border: 1px solid red;"';
    }
}
