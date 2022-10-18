<?php
session_start(); //Запускаем сессию
require_once('config.php'); //Подключаем конфигурационный файл
if (empty($_SESSION['login']) or empty($_SESSION['id'])) //Если пользователь не вошёл в систему перебрасывем на главную
{
header('Location: /');
exit;
}
$id = $_SESSION['id']; //Достаём айди пользователя из сессии
$result = mysql_query("SELECT * FROM users WHERE id='$id'",$db); //Выборка пользователя из сессии
$myrow = mysql_fetch_array($result);
$count = mysql_query("SELECT count(*) FROM users",$db); //считаем кол-во юзеров
if ($myrow['status'] == '0') { 
header('Location: /');
exit;
}
define ("MAX_IDLE_TIME", "3"); //Время в минутах, которое сессия считается "онлайн"
define ("SESSION_PATH", "/tmp"); //Путь для сессий без слеша в конце
define ("SESSION_PREFIX","sess_"); //Начало имен всех файлов сессий, обычно такое и есть
if (SESSION_PATH!='') session_save_path (SESSION_PATH);
function getOnlineUsers() {
 if ($directory = opendir(session_save_path())) {
  $online = 0;
  $n = strlen(SESSION_PREFIX);
  while (false !== ($file = readdir($directory))) {
   if (substr($file,0,$n)==SESSION_PREFIX) {
    if (time()-fileatime(session_save_path().'/'.$file) < MAX_IDLE_TIME*60) { $online++; }
   } 
  }
  closedir ($directory);
  return $online;
 } 
 else { return false; }
}
?>
<!doctype html>
<head>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<script src="js/jquery-2.2.4.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<title>Apron CRM</title>
<meta charset="utf-8">
</head>
<body>
<style>
input[type=text]{
   width:220px;
}
input[type=password]{
   width:220px;
}
</style>
<div class="topbar">
<ul class="topbar">
<li class="topbar header-location">Apron</li>
<li class="topbar header-location"> Добро пожаловать, <?php echo $myrow['name']; ?></li>
<li class="topbar header-username">Вы вошли с правами 
<?php 
if ($myrow['status'] == '0') { 
echo "Пользователя";
}
if ($myrow['status'] == '1') { 
echo "Администратора";
}
if ($myrow['status'] == '2') { 
echo "Техподдержки";
}
?></li>
<li class="topbar"><a class="btn btn-info logout-button" href="/logout.php">Выход</a></li
<?php 
if ($myrow['status'] == '0') { 
echo '';
}
if ($myrow['status'] == '1') { 
echo '<li class="topbar"><a class="btn btn-info logout-button" href="/adduser.php">Добавить пользователя</a></li>';
}
if ($myrow['status'] == '2') { 
echo '<li class="topbar"><a class="btn btn-info logout-button" href="/support.php">HelpDesk</a></li>';
}
?>
</ul>
<br>
</div>
<p>
Всего пользователей (<?php echo mysql_result($count, 0); ?>)
 Онлайн пользователей (<?php echo getOnlineUsers(); ?>)
</p>
<?php
if(isset($_POST['submit']))
{
$name = $_POST['name']; //Получаем фио юзера
$login = $_POST['login']; //Получаем логин юзера
$password = $_POST['password']; //Получаем пароль юзера
$status = $_POST['status']; //Получаем роль юзера
if (empty($name)) //если пользователь не ввел фио, то выдаем ошибку и останавливаем скрипт
{
exit ('Введите ФИО пользователя.');
}
if (empty($login)) //если пользователь не ввел логин, то выдаем ошибку и останавливаем скрипт
{
exit ('Введите логин пользователя.');
}
if (empty($password)) //если пользователь не ввел пароль, то выдаем ошибку и останавливаем скрипт
{
exit ('Введите пароль пользователя.');
}
/*if (empty($status)) //если пользователь не выбрал роль, то выдаем ошибку и останавливаем скрипт
{
exit ('Не выбрана роль пользователя.');
}*/
$name = stripslashes($name); //удаляем лишнее
$name = htmlspecialchars($name); //защита от html тегов
$login = stripslashes($login); //удаляем лишнее
$login = htmlspecialchars($login); //защита от html тегов
$password = stripslashes($password);
$password = htmlspecialchars($password);
$status = stripslashes($status);
$status = htmlspecialchars($status);
$result2 = mysql_query("INSERT INTO users (login,password,name,status) VALUES('$login','$password','$name','$status')",$db);
// Проверяем, есть ли ошибки
if ($result2=='TRUE')
{
echo '<div class="alert alert-success" role="alert">Пользователь добавлен! Логин: '.$login.' Пароль: '.$password.'</div>';
}
else {
echo '<div class="alert alert-danger" role="alert">Ошибка :c<br>';
echo mysql_errno($db) . ": " . mysql_error($db) . "\n <br><br></div>";
}
}
?>
<form action="adduser.php" method="POST">
  <div class="mb-3">
    <label for="exampleInputName1" class="form-label">ФИО</label>
    <input name="name" type="text" class="form-control" id="exampleInputName1" aria-describedby="nameHelp">
    <div id="nameHelp" class="form-text">Например Иванов Иван Иванович</div>
  </div>
  <div class="mb-3">
    <label for="exampleInputLogin1" class="form-label">Логин</label>
    <input name="login" type="text" class="form-control" id="exampleInputLogin1" aria-describedby="loginHelp">
	<div id="loginHelp" class="form-text">Например ivanov_786</div>
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Пароль</label>
    <input name="password" type="password" class="form-control" id="exampleInputPassword1" aria-describedby="passHelp">
	<div id="passHelp" class="form-text">Например u87d9s9h0s</div>
  </div>
  <div class="form-check">
<div id="passHelp" class="form-text">Права пользователя:</div>
  <input class="form-check-input" type="radio" name="status" value="0" id="flexRadioDefault1" aria-describedby="roleHelp" checked>
  <label class="form-check-label" for="flexRadioDefault1">
    Пользователь
  </label>
  </div>
  <div class="form-check">
  <input class="form-check-input" type="radio" name="status" value="1" id="flexRadioDefault2">
  <label class="form-check-label" for="flexRadioDefault2">
    Администратор
  </label>
  </div>
  <div class="form-check">
  <input class="form-check-input" type="radio" name="status" value="2" id="flexRadioDefault3">
  <label class="form-check-label" for="flexRadioDefault3">
    Техподдержка
  </label>
  </div>
  <br>
  <button name="submit" type="submit" class="btn btn-primary">Добавить</button>
</form>
<footer>
<hr>
<center>© 2022 Apron. <a href="https://github.com/zavsc/apron">Скачать</a></center>
</footer>
</body>
</html>
