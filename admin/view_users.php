<!DOCTYPE html>
<html>
	<head>
	<link rel="stylesheet" href="https://use.typekit.net/qst8qoi.css">
	<style>
		li {
			display: inline;
		}
		ul li {
			background-color: beige;
			width: 150px;
			height: 25px;
			border: 1px solid black;
			text-align: center;
			float: left;
			position: relative;
			margin-right: 1%;
			color: black;
			padding: auto;
			font-family: roboto, sans-serif;
		}
		</style>
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