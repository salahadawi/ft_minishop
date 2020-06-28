<?php
// header("Location: index.php");

if ($_POST['submit'] === 'OK')
{
    $directory = '../private';
    $filename = '../private/passwd';
    if ($_POST['login'] && $_POST['passwd'])
    {
        if (file_exists($directory) === FALSE)
            mkdir($directory);
        $login = $_POST['login'];
        $passwd = hash('whirlpool', $_POST['passwd']);
        $level = (int)$_POST['level'];
        $success = 1;
        if (file_exists($filename))
        {
            $str = file_get_contents($filename);
            $array = unserialize($str);
            foreach ($array as $key => $value)
            {
                if ($value['login'] === $login)
                {
                    $success = 0;
                    echo "User $login already exists. Try with another username.\n";
                }
            }
        }
        if ($success)
        {
            $info['login'] = $login;
            $info['passwd'] = $passwd;
            $info['level'] = $level;
            $array[] = $info;
            $data = serialize($array);
            file_put_contents($filename, $data);
            echo "User $login created.";
        }
    }
    else
        echo "ERROR: Login and/or password missing.\n";
}
?>

<!DOCTYPE html>
<html>
    <body>
        <form action="" method="post">
            Username: <input type="text" name="login" value="" />
            <br />
            Password: <input type="text" name="passwd" value="" />
            Level: <input type="text" name="level" value="" />
            <input type="submit" name="submit" value="OK" />
        </form>
    </body>
</html>