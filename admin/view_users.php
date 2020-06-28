<!DOCTYPE html>
<html>
<body>
<h2>View users</h2>
<?php
$filename = 'private/passwd';
$str = file_get_contents($filename);
$array = unserialize($str);

foreach ($array as $key => $value)
	echo $value['login']."<br />";
?>
</body>
</html>