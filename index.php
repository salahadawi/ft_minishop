<?php
session_start();
require_once 'admin/is_admin.php';

$user = $_SESSION['logged_on_user'] ? $_SESSION['logged_on_user'] : "";
?>

<!DOCTYPE html>
<html>
	<head>
		<title>The Candy Shop</title>
		<style>
			* {
				box-sizing: border-box;
			}
			#main {
				display: flex;
				min-height: calc(100vh - 40vh);
			}
			#main > .middle {
				padding: 1em;
				flex: 1;
			}
			#main > .side {
				padding: 1em;
				flex: 0 0 20vw;
				background: beige;
			}
			header, footer {
				padding: 1em;
				background-color: pink;
			}
		</style>
	</head>
	<body>
	<header>
		<p>header</p>
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
			<?php
			if (is_admin($user))
			{
				?>
				<h3>The Admin Panel</h3>
				<a href="index.php?page=admin/view_orders">View orders</a>
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
			<form action="logout.php">
				<input type="submit" value="Log out" />
			</form>
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
		<div class="side">side</div>
	</div>
	<footer>
		<p>footer</p>
	</footer>
	</body>
</html>