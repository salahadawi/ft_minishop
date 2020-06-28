<?php

session_start();
$user = $_SESSION['logged_on_user'];

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

// $_SESSION['logged_on_user'] = "sadawi";

$cart_directory = "shopping_carts";
$orders_directory = "orders";

function save_item_to_cart($item, $products, $amount)
{
	if ($_SESSION['logged_on_user'])
	{
		//print_r(str_getcsv(file("products.csv")));
		if (file_exists($cart_directory) === FALSE)
            mkdir($cart_directory);
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
$products = csv_to_array("products.csv");

foreach ($products as $item)
{
	if ($_GET['submit'] === $item['name'])
		save_item_to_cart($item, $products, $_GET['quantity']);
}

if ($_GET['submit'] === "clear")
{
	foreach ($products as $item)
	{
		unset($_SESSION['basket'][$item['name']]);
	}
	if ($_SESSION['logged_on_user'])
	{
		unlink("shopping_carts/".$_SESSION['logged_on_user']."_cart.csv");
	}
}

if ($_GET['category'])
{
	$_SESSION['category'] = $_GET['category'];
	if ($_GET['category'] == "all")
		unset($_SESSION['category']);
}

if ($_GET['submit'] === "order")
{
	if (file_exists($orders_directory) === FALSE)
            mkdir($orders_directory);
	if ($_SESSION['logged_on_user'])
	{
		copy("shopping_carts/".$_SESSION['logged_on_user']."_cart.csv",
		"orders/".$_SESSION['logged_on_user']."_order.csv");
		
	}
	else
	echo "<script>alert('You must be be logged in to place an order!');</script>";
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

$categories = [];

foreach ($products as $item)
{
	$item_categories = explode(";", $item['category']);
	foreach ($item_categories as $category)
		if (!in_array($category, $categories))
			$categories[] = $category;
}

?>
<html><body>
	<?php
	echo "Hello $user!";
	?>
	<br />
	<form action="product_page.php">
	Category:
	<select name="category">
		<option value="all" selected>All</option>
		<?php
		foreach ($categories as $category)
			echo "<option value=".$category.">".$category."</option>";
		?>
	</select>
	<input type="submit">
	</form>
	<?php
	foreach ($products as $item)
	{
		if (!$_SESSION['category'] || strpos($item['category'], $_SESSION['category']) !== false)
		{
			echo $item['name']."<br />";
			echo "Price: ".$item['price']."\n";
			echo "Quantity: ".$item['quantity']."\n";
			echo "Category: ".$item['category']."\n";
			?>
			<form action="product_page.php" method="GET">
			Amount: 
			<input type="number" name="quantity" min="1" max=<?= $item['quantity'] ?> value="1">
			<button type="submit" name="submit" value=<?= $item['name'] ?>>Add to basket</button>
			</form>
		<?php
		}
	}
	echo "Shopping Cart: <br />";
	if ($_SESSION['logged_on_user'])
	{
		$cart = csv_to_array("shopping_carts/".$_SESSION['logged_on_user']."_cart.csv");
		$total_price = 0;
		foreach ($cart as $item)
		{
			echo $item['name'].", quantity: "
			?>
				<form action="product_page.php" method="GET">
					<input type="number" name="new_quantity" min="0" max=<?= get_item_max_quantity($products, $item); ?> value=<?= $item['quantity'] ?>>
					<button type="submit" name="submit" value=<?= $item['name'] ?>>Update quantity</button>
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
				<form action="product_page.php" method="GET">
					<input type="number" name="new_quantity" min="0" max=<?= $item['quantity'] ?> value=<?= $_SESSION['basket'][$item['name']] ?>>
					<button type="submit" name="submit" value=<?= $item['name'] ?>>Update quantity</button>
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
		<form action="product_page.php" method="GET">
		<button type="submit" name="submit" value="clear">Clear basket</button>
		</form>
		<form action="product_page.php" method="GET">
		<button type="submit" name="submit" value="order"><?= $order_button ?>
		</button>
		</form>
		<form action="index.php">
			<input type="submit" value="Return" />
		</form>
		<?php
	?>
</body></html>