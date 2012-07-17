<?php
  session_start();
  if(!isset($_SESSION['adm_fak'])){
    header("Location:../index.php");
  }
  
  if(isset($_GET['idl'])){
    require_once('../mysqli_connect.php');
	$idl = $_GET['idl'];
	$q = "delete from lokalmaster where id_lkl=$idl";
	mysqli_query($dbc, $q);
  }
?>