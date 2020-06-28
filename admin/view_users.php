<!DOCTYPE html>
<html>
<body>
<h3>View users</h3>
<?php
$filename = '../private/passwd';
$str = file_get_contents($filename);
$array = unserialize($str);

foreach ($array as $key => $value)
	echo $value['login']."\n";
?>
</body>
</html>