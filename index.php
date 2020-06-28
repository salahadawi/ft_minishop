<?php
session_start();
require_once 'admin/is_admin.php';

$user = $_SESSION['logged_on_user'] ? $_SESSION['logged_on_user'] : "";
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
		<h1>The Candy Shop</h1>
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
			echo "Hello $user!<br>";
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
				<h3>The Admin Panel</h3>
				<a href="index.php?page=admin/view_orders">View orders</a>
				<br />
				<a href="index.php?page=admin/view_users">View users</a>
				<br />
				<a href="index.php?page=admin/edit_user">Edit or remove user</a>
				<br />
				<a href="index.php?page=admin/edit_product">Edit or remove product</a>
				<br />
				<a href="index.php?page=admin/edit_category">Edit or remove category</a>
				<br />
				<?php
			}
			?>
			<br />
			<?php
		}
		?>
		<a href="index.php?page=product_page">Basket</a>
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
		<div class="side"></div>
	</div>
	<footer>
		<p></p>
	</footer>
	</body>
</html>