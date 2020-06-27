<?php
header("Location: index.html");

if ($_POST['submit'] === 'OK')
{
    $directory = '../private';
    $filename = '../private/passwd';
    if (file_exists($directory) === FALSE)
        mkdir($directory);
    if ($_POST['login'] && $_POST['passwd'])
    {
        $login = $_POST['login'];
        $passwd = hash('whirlpool', $_POST['passwd']);
        if (file_exists($filename))
        {
            $str = file_get_contents($filename);
            $array = unserialize($str);
            foreach ($array as $key => $value)
            {
                if ($value['login'] === $login)
                    exit("ERROR\n");
            }
        }
        $info['login'] = $login;
        $info['passwd'] = $passwd;
        $array[] = $info;
        $data = serialize($array);
        file_put_contents($filename, $data);
        echo "OK\n";
    }
    else
        exit("ERROR\n");
}
else
    exit("ERROR\n");
?>