<?php
$db = new PDO('mysql:host=localhost;dbname=******', '******', '******', array(PDO::ATTR_PERSISTENT => true))
$stmt = $db->prepare("SELECT * FROM Users WHERE ID = ?");
$stmt->execute([1]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] != $row['Login'] ||
    md5($_SERVER['PHP_AUTH_PW']) != $row['Password']) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
}
$counts = [];
for ($i = 1; $i <= 11; $i++) {
    $stmt = $db->prepare("SELECT COUNT(*) FROM App_Ability WHERE AbilityID = ?");
    $stmt->execute([$i]);
    $counts[$i] = $stmt->fetchColumn();
}
$stmt = $db->query("SELECT MAX(ID) FROM App");
$row = $stmt->fetch();
$max_user_id = (int) $row[0]; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        if ($_POST['select_user'] == 0) {
            header('Location: admin.php');
        }
        $user_id = (int) $_POST['select_user'];
        $stmt = $db->prepare("DELETE FROM App_Ability WHERE ApplicationID = ?");
        $stmt->execute([$user_id]);
        $stmt = $db->prepare("DELETE FROM App WHERE ID = ?");
        $stmt->execute([$user_id]);
        header('Location: admin.php');
    }
    if (isset($_POST['edit'])) {
        $user_id = (int) $_COOKIE['user_id'];
        $stmt = $db->prepare("UPDATE App SET FIO = ?, Phone = ?, Email = ?, Birthdate = ?, Gender = ?, Bio = ? WHERE ID = ?");
        $stmt->execute([$_POST['name'], $_POST['phone'], $_POST['email'], $_POST['year'], $_POST['gender'], $_POST['bio'], $user_id]);
        $stmt = $db->prepare("DELETE FROM App_Ability WHERE ApplicationID = ?");
        $stmt->execute([$user_id]);
        $languages = $_POST['languages'];
        foreach ($languages as $language_id) {
            $stmt = $db->prepare("INSERT INTO App_Ability (ApplicationID, AbilityID) VALUES (?, ?)");
            $stmt->execute([$user_id, $language_id]);
        }
        header('Location: admin.php');
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="container">
  <h1>Панель администратора</h1>
  <h2>Статистика по языкам программирования:</h2>
  <?php
  $languages = [
      1 => 'Pascal',
      2 => 'C',
      3 => 'C++',
      4 => 'JavaScript',
      5 => 'PHP',
      6 => 'Python',
      7 => 'Java',
      8 => 'Haskel',
      9 => 'Clojure',
      10 => 'Prolog',
      11 => 'Scala'
  ];
  foreach ($languages as $id => $name) {
      echo "<p>{$name}: {$counts[$id]}</p><br>";
  }
  ?>

  <h3>Выберите пользователя:</h3>
  <form action="" method="POST">
    <select name="select_user" class="group list" id="selector_user">
      <option selected disabled value="0">Выбрать пользователя</option>
      <?php
      for ($index = 1; $index <= $max_user_id; $index++) {
          $stmt = $db->prepare("SELECT * FROM App WHERE ID = ?");
          $stmt->execute([$index]);
          $user = $stmt->fetch(PDO::FETCH_ASSOC);
          if ($user) {
              echo "<option value='{$index}'>id: {$user['ID']}, Имя: {$user['FIO']}</option>";
          }
      }
      ?>
    </select><br> 
    <input name="delete" type="submit" class="send" value="УДАЛИТЬ ПОЛЬЗОВАТЕЛЯ" />
    <input name="editing" type="submit" class="send" value="РЕДАКТИРОВАТЬ ПОЛЬЗОВАТЕЛЯ" />
  </form>
  <?php
  if (isset($_POST['editing']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
      if ($_POST['select_user'] == 0) {
          header('Location: admin.php');
      }
      $user_id = (int) $_POST['select_user'];
      setcookie('user_id', $user_id);
      $stmt = $db->prepare("SELECT * FROM App WHERE ID = ?");
      $stmt->execute([$user_id]);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $values = [
          'name' => htmlspecialchars($row['FIO']),
          'phone' => htmlspecialchars($row['Phone']),
          'email' => htmlspecialchars($row['Email']),
          'year' => $row['Birthdate'],
          'gender' => $row['Gender'],
          'bio' => htmlspecialchars($row['Bio']),
          'languages' => []
      ];

      $stmt = $db->prepare("SELECT * FROM App_Ability WHERE ApplicationID = ?");
      $stmt->execute([$user_id]);
      while ($lang_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $values['languages'][] = $lang_row['AbilityID'];
      }
  ?>
  <br>

  <h3>Режим редактирования:</h3>
  <form action="" method="POST">
    Имя:<br><input type="text" name="name" class="group" value="<?php echo $values['name']; ?>"><br>
    Телефон:<br><input type="tel" name="phone" class="group" value="<?php echo $values['phone']; ?>"><br>
    E-mail:<br><input type="email" name="email" class="group" value="<?php echo $values['email']; ?>"><br>
    Дата рождения:<br><input type="date" name="year" class="group" value="<?php echo $values['year']; ?>"><br>
    Пол:<br>
    <input type="radio" name="gender" value="male" <?php if ($values['gender'] == 'male') echo 'checked'; ?>> Мужской
    <input type="radio" name="gender" value="female" <?php if ($values['gender'] == 'female') echo 'checked'; ?>> Женский<br>
    Любимый язык программирования:<br>
    <select name="languages[]" class="group" size="11" multiple>
      <?php
      foreach ($languages as $id => $name) {
          $selected = in_array($id, $values['languages']) ? 'selected' : '';
          echo "<option value='{$id}' {$selected}>{$name}</option>";
      }
      ?>
    </select><br>
    Биография:<br><textarea name="bio" class="group" rows="3" cols="30"><?php echo $values['bio']; ?></textarea><br>
    <input type="submit" name="edit" class="send" value="СОХРАНИТЬ ИЗМЕНЕНИЯ">
  </form>
  <?php
  }
  ?>
</div>
</body>
</html>
