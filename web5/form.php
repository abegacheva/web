<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
            color: #333333;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 50px;
            color: #444444;
        }

        .form-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        textarea:focus,
        select:focus {
            border-color: #007bff;
            outline: none;
        }

        .error-field {
            border-color: red !important;
        }

        .error-message {
            color: red;
            font-size: 12px;
        }

        .submit {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>

    <?php
    $values = isset($values) ? $values : array();
    $values['programming_languages'] = isset($values['programming_languages']) ? $values['programming_languages'] : array();
    if (!empty($messages)) {
        print('<div id="messages">');
        foreach ($messages as $message) {
            print($message);
        }
        print('</div>');
    }
    ?>

    <div class="form-container">
        <form action="index.php" method="post">
            <label for="fio">ФИО:</label>
            <input type="text" id="fio" name="fio" required class="<?php if (isset($_COOKIE['fio_error'])) echo 'error-field'; ?>">
            <?php if (isset($_COOKIE['fio_error'])) { ?>
                <span class="error-message"><?php echo $_COOKIE['fio_error']; ?></span>
            <?php } ?>
            <br><br>

            <label for="phone">Телефон:</label>
            <input type="text" id="phone" name="phone" required class="<?php if (isset($_COOKIE['tel_error'])) echo 'error-field'; ?>">
            <?php if (isset($_COOKIE['tel_error'])) { ?>
                <span class="error-message"><?php echo $_COOKIE['tel_error']; ?></span>
            <?php } ?>
            <br><br>

            <label for="email">E-mail адрес:</label>
            <input type="text" id="email" name="email" required class="<?php if (isset($_COOKIE['email_error'])) echo 'error-field'; ?>">
            <?php if (isset($_COOKIE['email_error'])) { ?>
                <span class="error-message"><?php echo $_COOKIE['email_error']; ?></span>
            <?php } ?>
            <br><br>

            <label for="date">Дата рождения:</label>
            <input type="date" id="date" name="date" required class="<?php if (isset($_COOKIE['date_error'])) echo 'error-field'; ?>">
            <?php if (isset($_COOKIE['date_error'])) { ?>
                <span class="error-message"><?php echo $_COOKIE['birthdate_error']; ?></span>
            <?php } ?>
            <br><br>

            <label>Пол:</label><br>
            <input type="radio" id="male" name="gen" value="male" required>
            <label for="male">Мужской</label>
            <input type="radio" id="female" name="gen" value="female" required>
            <label for="female">Женский</label><br><br>

            <label for="programming_languages">Любимый язык программирования:</label><br>
            <select id="programming_languages" name="programming_languages[]" multiple required class="<?php if (isset($_COOKIE['languages_errorr'])) echo 'error-field'; ?>">
                <option value="Pascal" <?php if (in_array('Pascal', $values['programming_languages'])) { print 'selected'; } ?>>Pascal</option>
                <option value="C" <?php if (in_array('C', $values['programming_languages'])) { print 'selected'; } ?>>C</option>
                <option value="C++" <?php if (in_array('C++', $values['programming_languages'])) { print 'selected'; } ?>>C++</option>
                <option value="JavaScript" <?php if (in_array('JavaScript', $values['programming_languages'])) { print 'selected'; } ?>>JavaScript</option>
                <option value="PHP" <?php if (in_array('PHP', $values['programming_languages'])) { print 'selected'; } ?>>PHP</option>
                <option value="Python" <?php if (in_array('Python', $values['programming_languages'])) { print 'selected'; } ?>>Python</option>
                <option value="Java" <?php if (in_array('Java', $values['programming_languages'])) { print 'selected'; } ?>>Java</option>
                <option value="Haskell" <?php if (in_array('Haskell', $values['programming_languages'])) { print 'selected'; } ?>>Haskell</option>
                <option value="Clojure" <?php if (in_array('Clojure', $values['programming_languages'])) { print 'selected'; } ?>>Clojure</option>
                <option value="Prolog" <?php if (in_array('Prolog', $values['programming_languages'])) { print 'selected'; } ?>>Prolog</option>
                <option value="Scala" <?php if (in_array('Scala', $values['programming_languages'])) { print 'selected'; } ?>>Scala</option>
            </select>
            <?php if (isset($_COOKIE['languages_errorr'])) { ?>
                <span class="error-message"><?php echo $_COOKIE['languages_errorr']; ?></span>
            <?php } ?>
            <br><br>

            <label for="bio">Биография:</label><br>
            <textarea id="bio" name="bio" rows="4" cols="50" class="<?php if (isset($_COOKIE['bio_error'])) echo 'error-field'; ?>"><?php echo isset($values['bio']) ? $values['bio'] : ''; ?></textarea>
            <?php if (isset($_COOKIE['bio_error'])) { ?>
                <span class="error-message"><?php echo $_COOKIE['bio_error']; ?></span>
            <?php } ?>
            <br><br>

            <input type="checkbox" id="contract_agree" name="contract" required>
            <label for="contract_agree">С контрактом ознакомлен(а)</label><br><br>
            <?php if (isset($_COOKIE['contract_error'])) { ?>
                <span class="error-message"><?php echo $_COOKIE['contract_error']; ?></span>
            <?php } ?>

            <input class="submit" type="submit" value="Сохранить">
        </form>
    </div>

    <div class="container">
    <?php
      if (!empty($_COOKIE[session_name()]) && !empty($_SESSION['login']))
        print('<a href="login.php" class = "enter-exit" title = "Log out">Выйти</a>');
      else
        print('<a href="login.php" class = "enter-exit"  title = "Log in">Войти</a>');
    ?>
    </div>

    <div class="container">
        <label>Таблица логинов и паролей</label><br>
        <img src="img/5.png" alt="Image 1">
        <br>
        <label>Успешная отправка формы и создание аккаунта</label><br>
        <img src="img/3.png" alt="Image 2">
        <br>
        <label>Попытка войти в несуществующий аккаунт</label><br>
        <img src="img/4.png" alt="Image 3">
        <br>
        <label>Ошибка в пароле</label><br>
        <img src="img/2.png" alt="Image 4">
        <br>
        <label>Вход в аккаунт и возможность выйти из него</label><br>
        <img src="img/1.png" alt="Image 5">
        <br>
    </div>
</body>
</html>
