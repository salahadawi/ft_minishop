<?php
require_once 'auth.php';
session_start();

if (auth($_POST['login'], $_POST['passwd']) === TRUE)
{
    $_SESSION['logged_on_user'] = $_POST['login'];
    ?>
    <form>
        <p>You are logged in!</p>
        <input name="logout" formaction="logout.php" type="submit" value="Log out" />
    </form>
    <?php
}
else
{
    $_SESSION['logged_on_user'] = "";
    exit("ERROR\n");
}
?>