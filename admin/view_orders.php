<!DOCTYPE html>
<html>
<body>
<p>View orders</p>
<?php

include("../functions.php");
session_start();
$directory = "../orders";
$dir = array_diff(scandir("../orders"), array('..', '.'));
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
		echo "<br /> Total price: ".$total_price."$ <br /><br /><br />";
}
if (empty($dir))
	print("No orders have been placed yet!")
?>
</body>
</html>