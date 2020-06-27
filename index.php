<?php
session_start();

$user = $_SESSION['logged_on_user'] ? $_SESSION['logged_on_user'] : "";
?>

<!DOCTYPE html>
<html>
	<head>
		<title>The Candy Shop</title>
		<style>

		</style>
	</head>
	<body>
		<?php
		if (!$_SESSION['logged_on_user'] || $_SESSION['logged_on_user'] == "")
		{
			?>
			<form action="login.php" method="post">
			<p>Login</p>
			Username: <input type="text" name="login" value="" />
			<br />
			Password: <input type="text" name="passwd" value="" />
			<input type="submit" name="connect" value="Connect" />
			</form>
			<form action="create.html">
			<input type="submit" value="Create account" />
			</form>
		<?php
		}
		else
		{
			echo "Hello $user!<br>";
			?>
			<form action="logout.php">
				<input type="submit" value="Log out" />
			</form>
			<form action="modif.html">
				<input type="submit" value="Change password" />
			</form>
			<?php
		}
		?>
		<form action="product_page.php">
			<input type="submit" value="Basket" />
		</form>
	</body>
</html>