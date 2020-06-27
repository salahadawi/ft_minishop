<?php
require_once 'auth.php';
session_start();

if (auth($_POST['login'], $_POST['passwd']) === TRUE)
{
    $_SESSION['logged_on_user'] = $_POST['login'];
    header("Location: index.php");
}
else
{
    $_SESSION['logged_on_user'] = "";
    echo "ERROR: Wrong username or password\n";
    echo "<a href='index.php'>Go back</a>";
}
?>