<?php
session_start();
require_once 'admin/is_admin.php';

$user = $_SESSION['logged_on_user'] ? $_SESSION['logged_on_user'] : "";
if (!$_GET['page'])
	$_GET['page'] = "product_page";
?>

<!DOCTYPE html>
<html>
	<head>
		<title>The Candy Shop</title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body>
	<header>
		<img class="fruits" src="img/fuits.png">
		<h1>Fruits For You!</h1>
	</header>
	<div id="main">
		<div class="side">
		<?php
		if (!$_SESSION['logged_on_user'] || $_SESSION['logged_on_user'] == "")
		{
			?>
			<form action="index.php?page=login" method="post">
			<p>Login</p>
			Username: <input type="text" name="login" value="" />
			<br />
			Password: <input type="text" name="passwd" value="" />
			<input class="button" type="submit" name="connect" value="Log in" />
			</form>
			<br />
			<a href="index.php?page=create">Create account</a>
			<br />
		<?php
		}
		else
		{
			echo "<h2>Hello $user!<br></h2>";
			?>
			<a href="index.php?page=modif">Change password</a>
			<br />
			<a href="index.php?page=delete">Delete user</a>
			<br />
			<a href="index.php?page=logout">Log out</a>
			<?php
			if (is_admin($user))
			{
				?>
				<h2>The Admin Panel</h2>
				<a href="index.php?page=admin/view_orders">View orders</a>
				<br />
				<a href="index.php?page=admin/view_users">View users</a>
				<br />
				<a href="index.php?page=admin/edit_user">Manage users</a>
				<br />
				<a href="index.php?page=admin/edit_product">Manage products</a>
				<br />
				<?php
			}
			?>
			<br />
			<?php
		}
		?>
		<br />
		<a href="index.php?page=product_page">Products</a>
		</div>
		<div class="middle">
		<?php
		if(isset($_GET['page']) && $_GET['page'] != '' )
			$page = $_GET['page']; // page being requested
		else
			$page = 'home'; // default page
		include($page.'.php');
		?>	
		</div>
		<div class="side">
		<?php

function csv_to_array2($filename)
{
	$arr = array_map("str_getcsv", file($filename));
	array_walk($arr, function(&$item) use ($arr)
	{
		$item = array_combine($arr[0], $item);
	});
	array_shift($arr);
	return ($arr);
}

function	get_item_max_quantity2($products, $cart_item)
{
	foreach ($products as $item)
	{
		if ($cart_item['name'] == $item['name'])
			return ($item['quantity']);
	}
	return (0);
}

function csv_to_array_with_format2($filename)
{
	$arr = array_map("str_getcsv", file($filename));
	array_walk($arr, function(&$item) use ($arr)
	{
		$item = array_combine($arr[0], $item);
	});
	return ($arr);
}

function save_item_to_cart2($item, $products, $amount)
{
	if ($_SESSION['logged_on_user'])
	{
		if (file_exists($cart_directory) === FALSE)
			mkdir("shopping_carts");
		if (!file_exists("shopping_carts/".$_SESSION['logged_on_user']."_cart.csv"))
		{
			$handle = fopen("products.csv", "r");
			file_put_contents("shopping_carts/".$_SESSION['logged_on_user']."_cart.csv", fgets($handle));
			fclose($handle);
		}
		$cart = csv_to_array_with_format2("shopping_carts/".$_SESSION['logged_on_user']."_cart.csv");
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

function replace_basket($products)
{
	$found = 0;
	foreach ($products as $item)
	{
		if ($_SESSION['basket'][$item['name']])
			$found = 1;
	}
	if ($found)
	{
		unlink("shopping_carts/".$_SESSION['logged_on_user']."_cart.csv");
		foreach ($products as $item)
		{
			if ($_SESSION['basket'][$item['name']])
				save_item_to_cart2($item, $products, $_SESSION['basket'][$item['name']]);
		}
	}
	unset($_SESSION['basket']);
}

	$products = csv_to_array2("products.csv");
	echo "<h2>Shopping Cart: </h2><br />";
	if ($_SESSION['logged_on_user'])
	{	
		replace_basket($products);
		$cart = csv_to_array2("shopping_carts/".$_SESSION['logged_on_user']."_cart.csv");
		$total_price = 0;
		foreach ($cart as $item)
		{
			echo $item['name'].", quantity: "
			?>
				<form action="index.php" method="GET">
					<input type="number" name="new_quantity" min="0" max=<?= get_item_max_quantity2($products, $item); ?> value=<?= $item['quantity'] ?>>
					<button class="button" type="submit" name="submit" value=<?= $item['name'] ?>>Update quantity</button>
					<input type="hidden" name="page" value="product_page">
				</form>
			<?php
			echo "Price: ".($item['price'] * $item['quantity'])."$ <br /><br />";
			$total_price += $item['price'] * $item['quantity'];
		}
		echo "Total price: ".$total_price."$";
	}
	else
	{
		$total_price = 0;
		foreach ($products as $item)
		{
			if ($_SESSION['basket'][$item['name']])
			{
				echo $item['name'].", quantity: " ?>
				<form action="index.php" method="GET">
					<input class="button" type="number" name="new_quantity" min="0" max=<?= $item['quantity'] ?> value=<?= $_SESSION['basket'][$item['name']] ?>>
					<button class="button" type="submit" name="submit" value=<?= $item['name'] ?>>Update quantity</button>
					<input type="hidden" name="page" value="product_page">
				</form>
				<?php
				echo "Price: ".($item['price'] * $_SESSION['basket'][$item['name']])."$ <br /><br />";
				$total_price += $item['price'] * $_SESSION['basket'][$item['name']];
			}
		}
		echo "Total price: ".$total_price."$";
	}
	if (!file_exists("orders/".$_SESSION['logged_on_user']."_order.csv"))
		$order_button = "Send order";
	else
		$order_button = "Update my order";
	?>
		<form action="index.php" method="GET">
		<button class="delete" type="submit" name="submit" value="clear">Clear basket</button>
		<input type="hidden" name="page" value="product_page">
		</form>
		<form action="index.php" method="GET">
		<button class="add" type="submit" name="submit" value="order"><?= $order_button ?>
		</button>
		<input type="hidden" name="page" value="product_page">
		</form>
		</div>
	</div>
	<footer>
		<p></p>
	</footer>
	</body>
</html>