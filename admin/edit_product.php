<!DOCTYPE html>
<html>
<body>
	<h2>Add new product</h2>

<?php

include("functions.php");
session_start();


$products = csv_to_array_with_format("products.csv");
$format = array_shift($products);

if ($_GET['new_item'])
{
	$found = 0;
	foreach ($products as $item)
	{
		if ($_GET['name'] === $item['name'])
		{
			echo "<script>alert('Product already exists!');</script>";
			$found = 1;
		}
	}
	if (!$found)
	{
		$new_item['name'] = $_GET['name'];
		$new_item['price'] = $_GET['price'];
		$new_item['quantity'] = $_GET['quantity'];
		$new_item['category'] = $_GET['category'];
		$new_item['image'] = $_GET['image'];
		$products[] = $new_item;
		$handle = fopen("products.csv", "w");
		fputcsv($handle, $format);
		foreach ($products as $item)
			fputcsv($handle, $item);
	}
}

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
				$item['image'] = $_GET['image'];
			}
		}
	}
	unset($item);
	$handle = fopen("products.csv", "w");
	fputcsv($handle, $format);
	foreach ($products as $item)
		fputcsv($handle, $item);
}
?>

<form action="index.php" method="GET">
		<br /> Name: 
		<input type="text" name="name">
		<br /> Price: 
		<input type="number" name="price" min="1" max="99999">
		<br /> Quantity in stock:
		<input type="number" name="quantity" min="1" max="99999">
		<br /> Category:
		<input type="text" name="category">
		<br /> Image link:
		<input type="text" name="image">
		<input type="hidden" name="page" value="admin/edit_product">
		<button type="submit" name="new_item" value="OK">Add product</button>
</form>
<br />

<h2>Manage products</h2>
<?php


foreach ($products as $item)
	{
		?>
		<form action="index.php" method="GET">
		<br /> Name: 
		<input type="text" name="name" value= <?= $item['name'] ?>>
		<br /> Price: 
		<input type="number" name="price" min="1" max="99999" value= <?= $item['price'] ?>>
		<br /> Quantity in stock:
		<input type="number" name="quantity" min="0" max="99999" value= <?= $item['quantity'] ?>>
		<br /> Category:
		<input type="text" name="category" value= <?= $item['category'] ?>>
		<br /> Image link:
		<input type="text" name="image" value= <?= $item['image'] ?>>
		<input type="hidden" name="page" value="admin/edit_product">
		<button type="submit" name="submit" value=<?= $item['name'] ?>>Update product</button>
		</form>
		<br />
	<?php
	}
?>
</body>
</html>