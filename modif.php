<?php
if ($_POST['submit'] === 'OK' && $_POST['newpw'])
{
    $filename = '../private/passwd';
    $login = $_POST['login'];
    $oldpw = hash('whirlpool', $_POST['oldpw']);
    $newpw = hash('whirlpool', $_POST['newpw']);
    $str = file_get_contents($filename);
    $array = unserialize($str);
    $i = 0;
    foreach ($array as $key => $value)
    {
        if ($value['login'] === $login)
        {
            if ($oldpw === $value['passwd'])
            {
                $array[$i]['passwd'] = $newpw;
                $data = serialize($array);
                file_put_contents($filename, $data);
                echo "Password successfully changed.";
                $success = 1;
            }
        }
        $i++;
    }
    if (!$success)
        echo "ERROR: Wrong password.";
}
else if ($_POST['submit'] && (!$_POST['oldpw'] || !$_POST['newpw']))
    echo "ERROR: Make sure that all fields are filled.";
?>

<!DOCTYPE html>
<html>
    <body>
        <form action="" method="post">
            Username: <input type="text" name="login" value="" />
            <br />
            Old password: <input type="text" name="oldpw" value="" />
            New password: <input type="text" name="newpw" value="" />
            <input type="submit" name="submit" value="OK" />
        </form>
        <form action="index.php">
            <input type="submit" value="Return" />
        </form>
    </body>
</html>