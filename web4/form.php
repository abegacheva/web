<!DOCTYPE html>
<html>
<head>
  <title>Контактная форма</title>
</head>
<body>
  <form action="index.php" method="post" accept-charset="UTF-8" class="login">
    <label for="fio">ФИО:</label>
    <input type="text" id="fio" name="fio" required>
    <br>
    <label for="phone">Телефон:</label>
    <input type="tel" id="phone" name="phone" required>
    <br>
    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email" required>
    <br>
    <label for="birthdate">Дата рождения:</label>
    <input type="date" id="birthdate" name="birthdate" required>
    <br>
    <label for="gender">Пол:</label>
    <input type="radio" id="gender-male" name="gender" value="male" required>
    <label for="gender-male">Мужской</label>
    <input type="radio" id="gender-female" name="gender" value="female" required>
    <label for="gender-female">Женский</label>
    <br>
    <label for="languages">Любимый язык программирования:</label>
    <select multiple id="languages" name="languages[]" required>
      <option value="pascal">Pascal</option>
      <option value="c">C</option>
      <option value="cpp">C++</option>
      <option value="javascript">JavaScript</option>
      <option value="php">PHP</option>
      <option value="python">Python</option>
      <option value="java">Java</option>
      <option value="haskell">Haskell</option>
      <option value="clojure">Clojure</option>
      <option value="prolog">Prolog</option>
      <option value="scala">Scala</option>
    </select>
    <br>
    <label for="bio">Биография:</label>
    <textarea id="bio" name="bio" required></textarea>
    <input type="submit" value="Сохранить">
  </form>
</body>
</html>
<style>
    body {
      font-family: Arial, sans-serif;
    }

    .login {
      width: 500px;
      margin: 0 auto;
    }

    label {
      display: block;
      margin-bottom: 5px;
    }

    input[type="text"], input[type="email"], input[type="date"] {
      width: 100%;
      padding: 5px;
      margin-bottom: 5px;
    }

    input[type="radio"] {
      margin-right: 5px;
    }

    select {
      width: 100%;
      padding: 5px;
      margin-bottom: 5px;
    }

    textarea {
      width: 100%;
      height: 100px;
      padding: 5px;
      margin-bottom: 5px;
    }

    input[type="checkbox"] {
      margin-right: 5px;
    }

    input[type="submit"] {
      width: 100%;
      padding: 5px;
      margin-top: 5px;
      background-color: #008CBA;
      color: #fff;
      border: none;
    }
</style>
