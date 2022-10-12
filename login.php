<?php
session_start(); //Запускаем сессию
require_once('config.php'); //Подключаем конфигурационный файл
if(isset($_SESSION['login'])) //Если пользователь уже вошёл перебрасываем в црмку
{
header('Location: /crm.php');
exit;
}
?>
<!doctype html>
<head>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<script src="js/jquery-2.2.4.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<title>Apron Вход</title>
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
<li class="topbar header-username">Вы вошли как Гость</li>
<li class="topbar"><a class="btn btn-info logout-button" href="/demo">Демо</a></li>
</ul>
<br>
</div>
<center><form action="login.php" method="POST">
  <div class="mb-3">
  <?php
if(isset($_POST['submit']))
{
if (isset($_POST['login'])) { $login = $_POST['login']; if ($login == '') { unset($login);} } //заносим введенный пользователем логин в переменную $login, если он пустой, то уничтожаем переменную
if (isset($_POST['password'])) { $password=$_POST['password']; if ($password =='') { unset($password);} }
if (empty($login)) //если пользователь не ввел логин, то выдаем ошибку и останавливаем скрипт
{
exit ('<div id="loginHelp" class="form-text">Введите логин.</div>');
}
if (empty($password)) //если пользователь не ввел пароль, то выдаем ошибку и останавливаем скрипт
{
exit ('<div id="passwordHelp" class="form-text">Введите пароль.</div>');
}
$login = stripslashes($login);
$login = htmlspecialchars($login);
$password = stripslashes($password);
$password = htmlspecialchars($password);
$login = trim($login);
$password = trim($password); 
//$password = md5($password);//шифруем пароль
//$password = strrev($password);// для надежности добавим реверс
//$password = $password."6y8gyh9";
$result = mysql_query("SELECT * FROM users WHERE login='$login' AND password='$password'",$db); //извлекаем из базы все данные о пользователе с введенным логином
$myrow = mysql_fetch_array($result);
if (empty($myrow['password']))
{
exit ('<div id="passwordHelp" class="form-text">Не верный логин или пароль.</div>');
}
else {
//если существует, то сверяем пароли
if ($myrow['password']==$password) {
//если пароли совпадают, то запускаем пользователю сессию! Можете его поздравить, он вошел!
$_SESSION['login']=$myrow['login'];
$_SESSION['id']=$myrow['id'];
header("Location: /crm.php"); 
exit;
}
else {
//если пароли не сошлись
exit ('<div id="passwordHelp" class="form-text">Не верный пароль.</div>');
}
}
}
  ?>
    <label for="exampleInputLogin1" class="form-label">Логин</label>
    <input name="login" type="text" class="form-control" id="exampleInputLogin1" aria-describedby="loginHelp">
    <!-- <div id="loginHelp" class="form-text">Введите ваш логин для доступа к системе apron.</div> -->
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Пароль</label>
    <input name="password" type="password" class="form-control" id="exampleInputPassword1" aria-describedby="passwordHelp">
  </div>
  <br>
  <button name="submit" type="submit" class="btn btn-primary">Войти</button>
</form></center>
<footer>
<hr>
<center>© 2022 Apron. <a href="https://github.com/zavsc/apron">Скачать</a></center>
</footer>
</body>
</html>