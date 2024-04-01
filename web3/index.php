<?php
if (isset($_POST['submit'])) {
  $fio = $_POST['fio'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];
  $birthdate = $_POST['birthdate'];
  $gender = $_POST['gender'];
  $bio = $_POST['bio'];
  $programming_languages = $_POST['programming_languages'];
  $errors = [];
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
    if (!in_array($language_id, ["pascal", "c", "cpp", "javascript", "php", "python", "java", "haskell", "clojure", "prolog", "scala"])) {
        $errors[] = "Неверный язык программирования";
    }
}
if (empty($bio)) {
    $errors[] = "Заполните биографию";
}
if (empty($errors)) {
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
    } catch (PDOException $e) {
        print('Ошибка при добавлении пользователя: ' . $e->getMessage());
        exit();
    }
} else {
    print_r($errors);
}
}
?>
