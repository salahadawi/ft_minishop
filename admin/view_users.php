<!DOCTYPE html>
<html>
	<head>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<h2>View users</h2>
<?php
$filename = 'private/passwd';
$str = file_get_contents($filename);
$array = unserialize($str);

?><ul><li style="background-color: pink">Username:</li>
	<li>Full name:</li>
	<li style="background-color: pink">Level:</li>
	<li>Created:</li>
	</ul><br /><?php

foreach ($array as $key => $value)
{
	?><ul><li style="background-color: pink"><?=$value['login']?></li>
	<li><?=$value['fullname']?></li>
	<li style="background-color: pink"><?=$value['level']?></li>
	<li><?=$value['date']?></li>
	</ul><br /><?php
}
?>
</body>
</html>