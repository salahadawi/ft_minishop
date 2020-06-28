<?php
session_start();

function del_user($login)
{
	
	$filename = '../private/passwd';
    $str = file_get_contents($filename);
	$array = unserialize($str);
	$i = 0;
	$success = 0;

    foreach ($array as $key => $value)
    {
        if ($value['login'] === $login)
		{	
			array_splice($array, $i, 1);
			$success = 1;
			break ;
		}
		$i++;
	}
	$data = serialize($array);
	file_put_contents($filename, $data);
	if ($success)
		echo "User $login deleted.";
	else
		echo "User $login couldn't be deleted. Check spelling.";
}

function change_username($olduser, $newuser)
{
	$filename = '../../private/passwd';
    $str = file_get_contents($filename);
	$array = unserialize($str);
	$i = 0;
	$success = 0;

    foreach ($array as $key => $value)
    {
		if ($value['login'] === $newuser)
		{
			$success = 2;
			break ;
		}
		if ($value['login'] === $olduser)
		{	
			$array[$i]['login'] = $newuser;
			$success = 1;
			break ;
		}
		$i++;
	}
	$data = serialize($array);
	file_put_contents($filename, $data);
	if ($success === 1)
		echo "Username $olduser changed to $newuser.";
	else if ($success === 2)
		echo "Username $newuser already exists. Choose another username.";
	else
		echo "Username $olduser couldn't be changed. Check spelling";
}

function make_admin($login)
{
	$filename = '../../private/passwd';
    $str = file_get_contents($filename);
	$array = unserialize($str);
	$i = 0;
	$success = 0;

    foreach ($array as $key => $value)
    {
		if ($value['login'] === $login)
		{	
			$array[$i]['level'] = 2;
			$success = 1;
			break ;
		}
		$i++;
	}
	$data = serialize($array);
	file_put_contents($filename, $data);
	if ($success === 1)
		echo "Username $login upgraded to admin.";
	else
		echo "Username $login couldn't be upraded.";
}

if ($_POST['submit'] === "Delete" && $_POST['login'])
	del_user($_POST['login']);
if ($_POST['submit'] === "Change" && $_POST['olduser'] && $_POST['newuser'])
	change_username($_POST['olduser'], $_POST['newuser']);
if ($_POST['submit'] === "Upgrade" && $_POST['login'])
	make_admin($_POST['login']);
?>

<!DOCTYPE html>
<html>
<body>
	<h2>Edit user</h2>
	<form action="" method="post">
		<p>Delete user</p>
		Username: <input type="text" name="login" value="" />
		<input type="submit" name="submit" value="Delete" />
	</form>
	<br />
	<form action="" method="post">
		<p>Change username</p>
		Username: <input type="text" name="olduser" value="" />
		New username: <input type="text" name="newuser" value="" />
		<input type="submit" name="submit" value="Change" />
	</form>
	<br />
	<form action="" method="post">
		<p>Upgrade user to admin</p>
		Username: <input type="text" name="login" value="" />
		<input type="submit" name="submit" value="Upgrade" />
	</form>
	<br />
</body>
</html>