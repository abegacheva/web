<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = 'u67311'; 
    $pass = '5681522'; 
    $db = new PDO('mysql:host=localhost;dbname=u67311', $user, $pass);
    $name = $_POST['fio'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $birthdate = $_POST['birthdate'];
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $bio = $_POST['bio'];
    echo "<div class='error-message-container'>";
    $errors = [];

    if (!preg_match("/^[a-zA-Zа-яА-Я ]+$/u", $name)) {
        $errors[] = "Введите корректное имя.";
    }
    if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $birthdate)) {
        $errors[] = "Дата рождения должна быть в формате ДЕНЬ-МЕСЯЦ-ГОД";
    }
    if (!preg_match("/^[a-zA-Zа-яА-Я.,! ]+$/u", $bio)) {
        $errors[] = "Поле Биография не может содержать спец.символы";
    }

    if (!preg_match("/^\+?[0-9]{1,4}[0-9]{10}$/", $phone)) {
        $errors[] = "Введите корректный номер телефона";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Введите корректный email";
    }
    if (empty($gender)) {
        $errors[] = "Выберите пол.";
    }
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<div error-message style='color: red;'>$error</div>";
        }
    } else {
        $stmt = $db->prepare("INSERT INTO App (FIO, Phone, Email, Birthdate, Gender, Bio) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $phone, $email, $birthdate, $gender, $bio]);
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
                    error_log("Язык '$language' Не найден в таблице Ability");
                }
            }
        }
        header('Location: form.php?save=1');
        exit();
    }
}

include('form.php');
?>
