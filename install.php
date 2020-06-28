#!/usr/bin/php
<?php
	session_start();

	mkdir('orders');
	mkdir('shopping_carts');
	mkdir('private');

    $directory = 'private';
    $filename = 'private/passwd';

    if (file_exists('private') === FALSE)
        mkdir($directory);

    $info['login'] = 'admin';
    $info['passwd'] = hash('whirlpool', 'admin');
    $info['level'] = 2;
    $array[] = $info;
    $data = serialize($array);
    file_put_contents($filename, $data);
?>