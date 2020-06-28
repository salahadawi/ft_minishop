<?php

function csv_to_array($filename)
{
	$arr = array_map("str_getcsv", file($filename));
	array_walk($arr, function(&$item) use ($arr)
	{
		$item = array_combine($arr[0], $item);
	});
	array_shift($arr);
	return ($arr);
}

function csv_to_array_with_format($filename)
{
	$arr = array_map("str_getcsv", file($filename));
	array_walk($arr, function(&$item) use ($arr)
	{
		$item = array_combine($arr[0], $item);
	});
	return ($arr);
}

function save_item_to_cart($item, $products, $amount)
{
	if ($_SESSION['logged_on_user'])
	{
		//print_r(str_getcsv(file("products.csv")));
		if (file_exists($cart_directory) === FALSE)
			mkdir("shopping_carts");
		if (!file_exists("shopping_carts/".$_SESSION['logged_on_user']."_cart.csv"))
		{
			$handle = fopen("products.csv", "r");
			file_put_contents("shopping_carts/".$_SESSION['logged_on_user']."_cart.csv", fgets($handle));
			fclose($handle);
		}
		$cart = csv_to_array_with_format("shopping_carts/".$_SESSION['logged_on_user']."_cart.csv");
		$item_found = 0;
		foreach ($cart as &$cart_item)
		{
			if ($cart_item['name'] == $item['name'])
			{
				if (isset($_GET['new_quantity']))
				{
					if ($_GET['new_quantity'] == 0)
						$cart_item = NULL;
					else
						$cart_item['quantity'] = $_GET['new_quantity'];
				}
				else
				{
					if ($cart_item['quantity'] + $amount <= $item['quantity'])
					{
						$cart_item['quantity'] += $amount;
						$item_found = 1;
					}
					else
					{
						echo "<script>alert('Sorry, we are out of stock!');</script>";
						$item_found = 1;
					}
				}
				$item_found = 1;
			}
		}
		if (!$item_found)
		{
			if ($item['quantity'] >= $amount)
			{
				$item['quantity'] = $amount;
				$cart[] = $item;
			}
			else
				echo "<script>alert('Sorry, we are out of stock!');</script>";
		}
		unset($cart_item);
		$handle = fopen("shopping_carts/".$_SESSION['logged_on_user']."_cart.csv", "w");
		foreach ($cart as $cart_item)
		{
			fputcsv($handle, $cart_item);
		}
		fclose($handle);
	}
	else
	{
		if (isset($_GET['new_quantity']))
		{
			if ($_GET['new_quantity'] == 0)
				unset($_SESSION['basket'][$item['name']]);
			else
				$_SESSION['basket'][$item['name']] = $_GET['new_quantity'];
		}
		else
		{
			if ($_SESSION['basket'][$item['name']] < $item['quantity'])
				$_SESSION['basket'][$item['name']]++;
			else
				echo "<script>alert('Sorry, we are out of stock!');</script>";
		}
	}
}
function	get_item_max_quantity($products, $cart_item)
{
	foreach ($products as $item)
	{
		if ($cart_item['name'] == $item['name'])
			return ($item['quantity']);
	}
	return (0);
}

?>