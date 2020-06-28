<!DOCTYPE html>
<html>
    <body>
    <h2>Change password</h2>
        <form action="" method="post">
            Old password: <input type="text" name="oldpw" value="" />
            New password: <input type="text" name="newpw" value="" />
            <input type="submit" name="submit" value="OK" />
        </form>
    </body>
</html>

<?php
if ($_POST['submit'] === 'OK' && $_POST['newpw'])
{
    $filename = 'private/passwd';
    $login = $_SESSION['logged_on_user'];
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