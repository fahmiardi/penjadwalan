<?php
  session_start();
  if(!isset($_SESSION['adm_fak']) and !isset($_SESSION['id_ta'])){
    header("Location:../index.php");
  }
  
  if(isset($_GET['idj'])){
    require_once('../mysqli_connect.php');
	$idj = $_GET['idj'];
	$q = "delete from tabeljam where id_jam=$idj";
	mysqli_query($dbc, $q);
  }
?>