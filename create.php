<?php
header("Location: index.php");

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
                    ?>
                    <p>User <?$login?> already exists.</p>
                    <form action="create.html">
		            <input type="submit" value="Try again" />
		            </form>
                    <?php
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
            ?>
            <p>User <?$login?> created.</p>
            <form action="index.php">
		    <input type="submit" value="Return" />
		    </form>
            <?php
        }
    }
    else
    {
        ?>
        <p>ERROR: Login and/or password missing</p>
        <form action="create.html">
		<input type="submit" value="Try again" />
		</form>
        <?php
    }
}
?>