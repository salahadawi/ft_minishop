<?php
header("Location: index.html");

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
                echo "OK\n";
                exit;
            }
        }
        $i++;
    }
}
else
    exit("ERROR\n");
?>