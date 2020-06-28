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
			height: 20px;
			border: 1px solid #434446;
			text-align: center;
			float: left;
			position: relative;
			color: #434446;
			margin-right: 1%;
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
	<li>Level:</li>
	<li>Created:</li>
	</ul><br /><?php

foreach ($array as $key => $value)
{
	?><ul><li style="background-color: pink"><?=$value['login']?></li>
	<li><?=$value['level']?></li>
	<li><?=$value['date']?></li>
	</ul><br /><?php
}
?>
</body>
</html>