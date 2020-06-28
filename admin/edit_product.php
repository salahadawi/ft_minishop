<?php
session_start();

// Open the file for reading
if (($h = fopen("products.csv", "r")) !== FALSE) 
{
  // Convert each line into the local $data variable
  while (($data = fgetcsv($h, 1000, ",")) !== FALSE) 
  {		
    // Read the data from a single line
  }

  // Close the file
  fclose($h);
}

?>
<!DOCTYPE html>
<html>
<body>
	<h2>Edit product</h2>
	<form action="admin.php">
        <input type="submit" value="Return" />
	</form>
</body>
</html>