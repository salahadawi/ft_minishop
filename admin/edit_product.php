<!DOCTYPE html>
<html>
<body>
	<h2>Manage products</h2>

<?php

include("functions.php");
session_start();


$products = csv_to_array_with_format("products.csv");
$format = array_shift($products);

if ($_GET['submit'])
{
	foreach ($products as &$item)
	{
		if ($_GET['submit'] === $item['name'])
		{
			if ($_GET['quantity'] == 0)
				$item = NULL;
			else
			{
				$item['name'] = $_GET['name'];
				$item['price'] = $_GET['price'];
				$item['quantity'] = $_GET['quantity'];
				$item['category'] = $_GET['category'];
			}
		}
	}
	unset($item);
	$handle = fopen("../products.csv", "w");
	fputcsv($handle, $format);
	foreach ($products as $item)
	{
		fputcsv($handle, $item);
	}
}

foreach ($products as $item)
	{
		?>
		<form action="edit_product.php" method="GET">
		<br /> Name: 
		<input type="text" name="name" value= <?= $item['name'] ?>>
		<br /> Price: 
		<input type="number" name="price" min="1" max="99999" value= <?= $item['price'] ?>>
		<br /> Quantity in stock:
		<input type="number" name="quantity" min="0" max="99999" value= <?= $item['quantity'] ?>>
		<br /> Category:
		<input type="text" name="category" value= <?= $item['category'] ?>>
		<button type="submit" name="submit" value=<?= $item['name'] ?>>Update product</button>
		</form>
		<br />
	<?php
	}
?>
</body>
</html>