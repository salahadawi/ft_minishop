<!DOCTYPE html>
<html>
	<head>
</head>
<body>
<h2>View orders</h2>
<?php

include("functions.php");
session_start();

if ($_GET['delete'])
{
	unlink("orders/".$_GET['delete']);
}

$directory = "orders";
$dir = array_diff(scandir("orders"), array('..', '.'));

foreach ($dir as $file)
{
	$name = substr($file, 0, strpos($file, "_order"));
	?>
	<p style="background-color: pink; border: 1px solid black; padding-left: 1%"><?=$name?>'s order:</p>
	<?php
	$cart = csv_to_array($directory."/".$file);
	$total_price = 0;
	foreach ($cart as $item)
	{
		echo $item['name'].", quantity: ".$item['quantity']."<br />";
		$total_price += $item['price'] * $item['quantity'];
	}
	echo "<br /> Total price: ".$total_price."$ <br />";
	?>
	<form action="index.php" method="GET">
	<button type="submit" name="delete" value=<?= $file ?>>Delete order</button>
	<input type="hidden" name="page" value="admin/view_orders">
	</form>
	<br /><br />
	<?php
}
if (empty($dir))
	print("No orders have been placed yet!")
?>
</body>
</html>