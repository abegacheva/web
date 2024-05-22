<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контактная форма</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    body {
  background-color: #fff;
  color: black;
}

.container {
  margin: 0 auto;
  padding: 20px;
  max-width: 600px;
  border-radius: 10px;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
  transition: all 0.3s ease;
}

input[type="text"],
input[type="date"],
input[type="tel"],
input[type="email"],
textarea,
select {
  background-color: #ffffff;
  color: #000000;
  padding: 8px;
  border-radius: 5px;
  margin-bottom: 10px;
  transition: all 0.3s ease;
  border: 1px solid black;
  width: 100%;
  box-sizing: border-box;
}

input[type="text"]:focus,
input[type="date"]:focus,
input[type="tel"]:focus,
input[type="email"]:focus,
textarea:focus,
select:focus {
  border: 1px solid transparent;
  outline: none;
}

.error-container {
  border: 2px solid red;
}

.submit {
  background-color: #008CBA;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.submit:hover {
  background-color: #005f75;
}

.logout-button {
  padding: 10px 20px;
  color: black;
  border: 1px solid #ccc;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.3s ease, transform 0.3s ease;
  transform: scale(1.05);
  text-shadow: 1px 1px 2px white;
  margin: 20px 10px;
}

.logout-button:hover {
  background-color: black;
  transform: scale(1.05);
  color: white;
}

.group.error {
  border: 2px solid red;
}

.radio {
  margin-right: 10px;
}

.messages {
  background-color: #f0f0f0;
  color: #333;
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 20px;
}

.enter-exit {
  display: inline-block;
  margin-top: 20px;
  padding: 10px 20px;
  background-color: #f0f0f0;
  color: black;
  text-decoration: none;
  border-radius: 5px;
  transition: background-color 0.3s ease;
}

.enter-exit:hover {
  background-color: #ccc;
}
</style>
<body>
  <?php  
    if (!empty($messages)) {
      print('<div class="messages">');
      foreach ($messages as $message) {
        print($message);
      }
      print('</div>');
    }
  ?>

  <div class="container">
    <h2>Контактная форма</h2>
    <form action="" method="POST">
      Имя:<br><input type="text" name="name" <?php if ($errors['name']) {print 'class="group error"';} else print 'class="group"'; ?> value="<?php print $values['name']; ?>">
      <br>
      Телефон:<br><input type="tel" name="phone" <?php if ($errors['phone']) {print 'class="group error"';} else print 'class="group"'; ?> value="<?php print $values['phone']; ?>">
      <br>
      E-mail:<br><input type="text" name="email" <?php if ($errors['email']) {print 'class="group error';} else print 'class="group"'; ?> value="<?php print $values['email']; ?>">
      <br>
      <div class="form-group">
        <legend for="year"class="group" style="color: white;">Дата рождения:</legend>
        <input type="date" id="year" size="3" name="year" <?php if ($errors['year']) {print 'class="group error"';} else print 'class="group"';?> value="<?php print $values['year']; ?>">
      </div>
      <div <?php if ($errors['gender']) {print 'class="error"';} ?>>
        Пол:<br>
        <input class="radio" type="radio" name="gender" value="M" <?php if ($values['gender'] == 'M') {print 'checked';} ?>> Мужской
        <input class="radio" type="radio" name="gender" value="W" <?php if ($values['gender'] == 'W') {print 'checked';} ?>> Женский
      </div>
      Любимый язык программирования:<br>
      <select class="group" name="languages[]" size="11" multiple>
        <option value="Pascal" <?php if (in_array("Pascal", $values['language'])) {print 'selected';} ?>>Pascal</option>
        <option value="C" <?php if (in_array("C", $values['language'])) {print 'selected';} ?>>C</option>
        <option value="C_plus_plus" <?php if (in_array("C++", $values['language'])) {print 'selected';} ?>>C++</option>
        <option value="JavaScript" <?php if (in_array("JavaScript", $values['language'])) {print 'selected';} ?>>JavaScript</option>
        <option value="PHP" <?php if (in_array("PHP", $values['language'])) {print 'selected';} ?>>PHP</option>
        <option value="Python" <?php if (in_array("Python", $values['language'])) {print 'selected';} ?>>Python</option>
        <option value="Java" <?php if (in_array("Java", $values['language'])) {print 'selected';} ?>>Java</option>
        <option value="Haskel" <?php if (in_array("Haskel", $values['language'])) {print 'selected';} ?>>Haskel</option>
        <option value="Clojure" <?php if (in_array("Clojure", $values['language'])) {print 'selected';} ?>>Clojure</option>
        <option value="Prolog" <?php if (in_array("Prolog", $values['language'])) {print 'selected';} ?>>Prolog</option>
        <option value="Scala" <?php if (in_array("Scala", $values['language'])) {print 'selected';} ?>>Scala</option>
      </select>
      <br>
      Биография:<br><textarea class="group" name="bio" rows="3" cols="30"><?php print $values['bio']; ?></textarea>
      <div  <?php if ($errors['checkbox']) {print 'class="error"';} ?>>
        <input type="checkbox" name="checkbox" <?php if ($values['checkbox']) {print 'checked';} ?>> С контрактом ознакомлен(a) 
      </div>
      <input type="submit" id="send" value="ОТПРАВИТЬ">
    </form>
  </div>
  
  <div class="container">
    <?php
      if (!empty($_COOKIE[session_name()]) && !empty($_SESSION['login']))
        print('<a href="login.php" class = "enter-exit" title = "Log out">Выйти</a>');
      else
        print('<a href="login.php" class = "enter-exit"  title = "Log in">Войти</a>');
      print('<a href="admin.php" class = "enter-exit">Админка</a>');
    ?>
  </div>
</body>
</html>
