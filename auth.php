<?php
function    auth($login, $passwd)
{
    $filename = '../private/passwd';
    $str = file_get_contents($filename);
    $array = unserialize($str);

    foreach ($array as $key => $value)
    {
        if ($value['login'] === $login && $value['passwd'] === hash('whirlpool', $passwd))
            return (TRUE);
    }
    return (FALSE);
}

?>