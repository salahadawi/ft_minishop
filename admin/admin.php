<?php
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Admin panel</title>
	</head>
	<body>
		<h1>The Admin Panel</h1>
		<form action="view_orders.php">
		    <input type="submit" value="View orders" />
		</form>
		<form action="edit_user.php">
		    <input type="submit" value="Edit or remove user" />
		</form>
		<form action="edit_product.php">
		    <input type="submit" value="Edit or remove product" />
		</form>
		<form action="edit_category.php">
		    <input type="submit" value="Edit or remove category" />
		</form>
	</body>
</html>