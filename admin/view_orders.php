<!DOCTYPE html>
<html>
<body>
<h3>View orders</h3>
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
	print($name."'s order: <br />");
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