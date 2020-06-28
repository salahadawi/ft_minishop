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
			<input type="submit" name="connect" value="Log in" />
			</form>
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

		$products = csv_to_array2("products.csv");
		echo "Shopping Cart: <br />";
	if ($_SESSION['logged_on_user'])
	{
		$cart = csv_to_array2("shopping_carts/".$_SESSION['logged_on_user']."_cart.csv");
		$total_price = 0;
		foreach ($cart as $item)
		{
			echo $item['name'].", quantity: "
			?>
				<form action="index.php" method="GET">
					<input type="number" name="new_quantity" min="0" max=<?= get_item_max_quantity2($products, $item); ?> value=<?= $item['quantity'] ?>>
					<button type="submit" name="submit" value=<?= $item['name'] ?>>Update quantity</button>
					<input type="hidden" name="page" value="product_page">
				</form>
				<br />
			<?php
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
					<input type="number" name="new_quantity" min="0" max=<?= $item['quantity'] ?> value=<?= $_SESSION['basket'][$item['name']] ?>>
					<button type="submit" name="submit" value=<?= $item['name'] ?>>Update quantity</button>
					<input type="hidden" name="page" value="product_page">
				</form>
				<br />
				<?php
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
		<button type="submit" name="submit" value="clear">Clear basket</button>
		<input type="hidden" name="page" value="product_page">
		</form>
		<form action="index.php" method="GET">
		<button type="submit" name="submit" value="order"><?= $order_button ?>
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