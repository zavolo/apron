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
$devres = mysql_query("SELECT * FROM devices ORDER BY id",$db); //Выборка таблицы с устройствами
$devices = mysql_fetch_array($devres);
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
?></li>
<li class="topbar"><a class="btn btn-info logout-button" href="/logout.php">Выход</a></li>
</ul>
<br>
</div>

<p>Список устройств: 
<?php 
if ($myrow['status'] == '0') { 
echo '';
}
if ($myrow['status'] == '1') { 
echo '<a class="btn btn-primary" href="/add.php">Добавить</a>';
}
if ($myrow['status'] == '2') { 
echo '<a class="btn btn-primary" href="/add.php">Добавить</a>';
}
?>
</p>

<table class="table table-hover">
    <tr>
        <th>Системный ID</th>
        <th>Название</th>
        <th>Номер устройства</th>
        <th>Краткое описание</th>
        <th>Владелец</th>
	<th colspan=7>Дата</th>
    </tr>
<?php
if(mysql_num_rows($devres)==0){ //Если в таблице пусто выводим ошибку
    echo '
<div class="alert alert-danger" role="alert">Устройства не добавлены в систему!</div>';
}else{ //Если в таблице что-то есть выводим список
echo '<td>'.$devices['id'].'</td><td>'.$devices['name'].'</td><td>'.$devices['number'].'</td><td>'.$devices['about'].'</td><td>'.$devices['owner'].'</td><td>'.$devices['date'].'</td>';
}
?>
<!--<td>1</td><td>Oklick 105S</td><td>0000111234221</td><td>Мышь проводная 800 dpi, светодиодный, USB Type-A, кнопки - 3</td><td>Иванов Александр Сергеевич</td><td>12.10.2022 16:06</td>-->
</table>
<footer>
<hr>
<center>© 2022 Apron. <a href="https://github.com/zavsc/apron">Скачать</a></center>
</footer>
</body>
</html>