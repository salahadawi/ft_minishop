<?php
function    is_admin($user)
{
    $filename = '../private/passwd';
    $str = file_get_contents($filename);
    $array = unserialize($str);

    foreach ($array as $key => $value)
    {
        if ($value['login'] === $user && $value['level'] === 2)
            return (TRUE);
    }
    return (FALSE);
}

?>