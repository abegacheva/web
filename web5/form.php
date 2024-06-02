<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style>
        body {
			background-color: #ffe6f2;
			color: #663399;
			text-align: center;
			display: flex;
			flex-direction: column;
			flex-wrap: nowrap;
			align-content: stretch;
			justify-content: space-evenly;
			align-items: center;
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

        .about-form {
            background-color: #ffffff;
            color: #663399;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            display: inline-block;
            text-align: left;
        }

        .about-form input[type="text"],
        .about-form input[type="date"],
        .about-form textarea,
        .about-form select {
            background-color: #ffffff;
            color: #663399;
            padding: 8px;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            border: 1px solid #cc99ff;
        }

        .about-form input[type="text"]:focus,
        .about-form input[type="date"]:focus,
        .about-form textarea:focus,
        .about-form select:focus {
            border: 1px solid transparent;
            outline: none;
        }

        .about-form .error-container {
            border: 1px solid red;
        }

        .about-form .submit {
            background-color: #ff66b3;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .about-form .submit:hover {
            background-color: #ff3399;
        }

        .logout-button {
            padding: 10px 20px;
            color: #663399;
            border: 1px solid #cc99ff;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            transform: scale(1.05);
            text-shadow: 1px 1px 2px white;
            margin: 20px 10px;
        }

        .logout-button:hover {
            background-color: #cc99ff;
            transform: scale(1.05);
            color: white;
        }
    </style>
</head>
<body>
    <?php
    if (!empty($messages)) {
        print('<div id="messages">');
        foreach ($messages as $message) {
            print($message);
        }
        print('</div>');
    }
	if (isset($_GET['save_after_change']) && $_GET['save_after_change'] == '1')
	{
		echo '<div class="success-message"> <p>Ваши данные были успешно изменены</p>';
	}
	session_start();
	$loginned_status = FALSE;
	if (empty($_SESSION['login'])) {
		echo "Вы не были авторизованы";
		session_unset();
		session_destroy();
	} else {
		echo "Вы авторизованы под следующим логином " . $_SESSION['login'];
		echo "<form action='logout.php' method='post'>";
		echo "<button type='submit' class='logout-button' id='logoutBtn'>Выйти</button>";
		echo "</form>";
		$loginned_status = TRUE;
	}
	if (!$loginned_status && isset($_GET['save']) && $_GET['save'] == '1' && isset($_GET['success_message'])) {
		echo '<div class="success-message">' . html_entity_decode($_GET['success_message']) . '</div>';
	}
	if ($loginned_status) {
		$user = 'u67311'; 
		$pass = '5681522'; 
		$host = 'localhost';
		$dbname = 'u67311';
		$db = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
		$login = $_SESSION['login'];
		$stmt = $db->prepare("SELECT ID FROM Users WHERE Login = ?");
		$stmt->execute([$login]);
		$user_row = $stmt->fetch(PDO::FETCH_ASSOC);
		$user_id = $user_row['ID'];
		$stmt = $db->prepare("SELECT * FROM App WHERE ID = ?");
		$stmt->execute([$user_id]);
		$application_data = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($application_data) {
			$fio = $application_data['FIO'];
			$phone = $application_data['Phone'];
			$email = $application_data['Email'];
			$birthdate = $application_data['Birthdate'];
			$gender = $application_data['Gender'];
			$bio = $application_data['Bio'];
			$contract = $application_data['Contract'];
		} else {
			echo "Данные текущего пользователя отсутствуют.";
		}
	}
	?>
    <div class="about-form">
    <form action="index.php" method="post">
        <label for="fio">ФИО</label>
		<input type="text" id="fio" name="fio" value="<?php echo isset($fio) ? $fio : ''; ?>" <?php if (isset($errors['fio']) && $errors['fio']) { print 'class="error-container"'; } ?> required>
		<?php if (isset($errors['fio']) && $errors['fio']) { ?>
			<span class="error-message">Ошибка в поле ФИО</span>
		<?php } ?>
		<br><br>
		<label for="phone">Телефон</label>
		<input type="text" id="phone" name="phone" value="<?php echo isset($phone) ? $phone : ''; ?>" <?php if (isset($errors['phone']) && $errors['phone']) { print 'class="error-container"'; } ?> required>
		<?php if (isset($errors['phone']) && $errors['phone']) { ?>
			<span class="error-message">Ошибка в поле Телефон</span>
		<?php } ?>
		<br><br>
		<label for="email">E-mail</label>
		<input type="text" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" <?php if (isset($errors['email']) && $errors['email']) { print 'class="error-container"'; } ?> required>
		<?php if (isset($errors['email']) && $errors['email']) { ?>
			<span class="error-message">Ошибка в поле E-mail</span>
		<?php } ?>
		<br><br>
		<label for="birthdate">Дата рождения</label>
		<input type="date" id="birthdate" name="birthdate" value="<?php echo isset($birthdate) ? $birthdate : ''; ?>" <?php if (isset($errors['birthdate']) && $errors['birthdate']) { print 'class="error-container"'; } ?> required>
		<?php if (isset($errors['birthdate']) && $errors['birthdate']) { ?>
			<span class="error-message">Ошибка в поле Дата рождения</span>
		<?php } ?>
		<br><br>
		<label>Пол</label>
		<input type="radio" id="male" name="gender" value="male" <?php if (isset($gender) && $gender == 'male') { print 'checked'; } ?> required>
		<label for="male">Мужской</label>
		<input type="radio" id="female" name="gender" value="female" <?php if (isset($gender) && $gender == 'female') { print 'checked'; } ?> required>
		<label for="female">Женский</label><br><br>
		<label for="programming_languages">Любимый язык программирования</label><br>
		<select id="programming_languages" name="programming_languages[]" multiple>
			<option value="Pascal" <?php if (isset($programming_languages) && in_array('Pascal', $programming_languages)) { print 'selected'; } ?>>Pascal</option>
			<option value="C" <?php if (isset($programming_languages) && in_array('C', $programming_languages)) { print 'selected'; } ?>>C</option>
			<option value="C++" <?php if (isset($programming_languages) && in_array('C++', $programming_languages)) { print 'selected'; } ?>>C++</option>
			<option value="JavaScript" <?php if (isset($programming_languages) && in_array('JavaScript', $programming_languages)) { print 'selected'; } ?>>JavaScript</option>
			<option value="PHP" <?php if (isset($programming_languages) && in_array('PHP', $programming_languages)) { print 'selected'; } ?>>PHP</option>
			<option value="Python" <?php if (isset($programming_languages) && in_array('Python', $programming_languages)) { print 'selected'; } ?>>Python</option>
			<option value="Java" <?php if (isset($programming_languages) && in_array('Java', $programming_languages)) { print 'selected'; } ?>>Java</option>
			<option value="Haskell" <?php if (isset($programming_languages) && in_array('Haskell', $programming_languages)) { print 'selected'; } ?>>Haskell</option>
			<option value="Clojure" <?php if (isset($programming_languages) && in_array('Clojure', $programming_languages)) { print 'selected'; } ?>>Clojure</option>
			<option value="Prolog" <?php if (isset($programming_languages) && in_array('Prolog', $programming_languages)) { print 'selected'; } ?>>Prolog</option>
			<option value="Scala" <?php if (isset($programming_languages) && in_array('Scala', $programming_languages)) { print 'selected'; } ?>>Scala</option>
		</select><br><br>
		<label for="bio">Биография</label><br>
		<textarea id="bio" name="bio" rows="4" cols="50"><?php print isset($bio) ? $bio : ''; ?></textarea><br><br>
		<input type="checkbox" id="contract_agree" name="contract" <?php if (isset($errors['contract']) && $errors['contract']) { print 'class="error-container"'; } ?> <?php if (isset($contract) && $contract) { print 'checked'; } ?> required>
		<label for="contract_agree">С контрактом ознакомлен(а)</label><br><br>
		<input class="submit" type="submit" value="Сохранить">
		</form>
	</div>
</body>
</html>
