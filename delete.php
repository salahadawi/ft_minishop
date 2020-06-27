<?php
require_once 'auth.php';
header("Location: logout.php");
session_start();
if ($_POST['submit'] === 'Delete' && auth($_SESSION['logged_on_user'], $_POST['passwd']))
{
	echo "here\n";
	$filename = '../private/passwd';
    $str = file_get_contents($filename);
	$array = unserialize($str);
	$i = 0;

    foreach ($array as $key => $value)
    {
        if ($value['login'] === $_SESSION['logged_on_user'])
		{	
			array_splice($array, $i, 1);
			break ;
		}
		$i++;
	}
	$data = serialize($array);
	file_put_contents($filename, $data);
}
?>