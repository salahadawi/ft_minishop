<?php

include("functions.php");

session_start();
$user = $_SESSION['logged_on_user'];

// $_SESSION['logged_on_user'] = "sadawi";

$cart_directory = "shopping_carts";
$orders_directory = "orders";


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