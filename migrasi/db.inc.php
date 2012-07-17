<?php
	try
	{
		$pdo = new PDO('mysql:host=localhost;dbname=penjadwalan', 'root', 'banatahta');
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->exec('SET NAMES "utf8"');
	}
	catch (PDOException $e)
	{
		$error = 'Unable to connect to the database server. <pre>'.$e->getMessage().'</pre>';
		include 'error.html.php';
		exit();
	}
?>
