<?php
session_start(); //Запускаем сессию
require_once('config.php'); //Подключаем конфигурационный файл
if (empty($_SESSION['login']) or empty($_SESSION['id'])) //Если пользователь не вошёл в систему перебрасывем на главную
{
header('Location: /');
exit;
}
$id = $_SESSION['id']; //Достаём айди пользователя из сессии
$result = mysql_query("SELECT * FROM users WHERE id='$id'",$db);  //Выборка пользователя из сессии
$myrow = mysql_fetch_array($result);
if ($myrow['status'] == '0') { //Если пользователь не администратор то перебрасывем в црмку
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
<li class="topbar header-username">Вы вошли как 
<?php 
if ($myrow['status'] == '0') { 
echo "Пользователь";
}
if ($myrow['status'] == '1') { 
echo "Администратор";
}
if ($myrow['status'] == '2') { 
echo "Техподдержка";
}
?>
</li>
<li class="topbar"><a class="btn btn-info logout-button" href="/logout.php">Выход</a></li>
</ul>
<br>
</div>
<?php
if(isset($_POST['submit']))
{
$name = $_POST['name']; //Получаем имя устройства
$number = $_POST['number']; //Получаем номер устройства
$about = $_POST['about']; //Получаем краткое описание устройства
$owner = $_POST['owner']; //Получаем фио владельца устройства
if (empty($name)) //если пользователь не ввел имя устройства, то выдаем ошибку и останавливаем скрипт
{
exit ('Введите имя устройства.');
}
if (empty($number)) //если пользователь не ввел номер устройства, то выдаем ошибку и останавливаем скрипт
{
exit ('Введите номер устройства.');
}
if (empty($about)) //если пользователь не ввел краткое описание устройства, то выдаем ошибку и останавливаем скрипт
{
exit ('Введите краткое описание устройства.');
}
if (empty($owner)) //если пользователь не ввел фио владельца устройства, то выдаем ошибку и останавливаем скрипт
{
exit ('Введите фио владельца устройства.');
}
$name = stripslashes($name); //удаляем лишнее
$name = htmlspecialchars($name); //защита от html тегов
$number = stripslashes($number);
$number = htmlspecialchars($number);
$about = stripslashes($about);
$about = htmlspecialchars($about);
$owner = stripslashes($owner);
$owner = htmlspecialchars($owner);
date_default_timezone_set('Europe/Moscow'); //Ставим часовой пояс Москва
$date = date('d.m.Y H:i'); //Дата и время добавления устройства в систему
$result2 = mysql_query("INSERT INTO devices (name,number,about,owner,date) VALUES('$name','$number','$about','$owner','$date')",$db);
// Проверяем, есть ли ошибки
if ($result2=='TRUE')
{
echo '<div class="alert alert-success" role="alert">Устройство добавлено.</div>';
}
else {
echo '<div class="alert alert-danger" role="alert">Ошибка! Устройство не добавлено.<br>';
echo mysql_errno($db) . ": " . mysql_error($db) . "\n <br><br></div>";
}
}
?>
<form action="add.php" method="POST">
  <div class="mb-3">
    <label for="exampleInputName1" class="form-label">Название устройства</label>
    <input name="name" type="text" class="form-control" id="exampleInputName1" aria-describedby="nameHelp">
    <div id="nameHelp" class="form-text">Например Oklick 105S</div>
  </div>
  <div class="mb-3">
    <label for="exampleInputNumber1" class="form-label">Номер устройства</label>
    <input name="number" type="text" class="form-control" id="exampleInputNumber1" aria-describedby="numberHelp">
    <div id="numberHelp" class="form-text">Например 0000111234221</div>
  </div>
  <div class="mb-3">
    <label for="exampleInputAbout1" class="form-label">Краткое описание</label>
    <input name="about" type="text" class="form-control" id="exampleInputAbout1" aria-describedby="aboutHelp">
	<div id="aboutHelp" class="form-text">Например Мышь проводная 800 dpi, светодиодный, USB Type-A, кнопки - 3</div>
  </div>
  <div class="mb-3">
    <label for="exampleInputOwner1" class="form-label">Владелец</label>
    <input name="owner" type="text" class="form-control" id="exampleInputOwner1" aria-describedby="ownerHelp">
	<div id="ownerHelp" class="form-text">Например Иванов Александр Сергеевич</div>
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