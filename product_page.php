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
	<br />
	<form action="index.php">
	Category:
	<select name="category">
		<option value="all" selected>All</option>
		<?php
		foreach ($categories as $category)
			echo "<option value=".$category.">".$category."</option>";
		?>
	</select>
	<input type="hidden" name="page" value="product_page">
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
			<form action="index.php" method="GET">
			Amount: 
			<input type="number" name="quantity" min="1" max=<?= $item['quantity'] ?> value="1">
			<button type="submit" name="submit" value=<?= $item['name'] ?>>Add to basket</button>
			<input type="hidden" name="page" value="product_page">
			</form>
		<?php
		}
	}
	
	?>
</body></html>