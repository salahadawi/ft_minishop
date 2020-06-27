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
                ?>
                <p>Password successfully changed.</p>
                <form action="index.php">
		        <input type="submit" value="Return" />
		        </form>
                <?php
                $success = 1;
            }
        }
        $i++;
    }
    if (!$success)
    {
        ?>
        <p>ERROR: Wrong password</p>
        <form action="modif.html">
		<input type="submit" value="Try again" />
		</form>
        <?php
    }
}
else
{
    ?>
        <p>ERROR: Make sure that all fields are filled.</p>
        <form action="modif.html">
		<input type="submit" value="Try again" />
		</form>
    <?php
}
?>