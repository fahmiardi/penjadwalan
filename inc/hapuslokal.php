<?php
  session_start();
  if(!isset($_SESSION['adm_fak']) and !isset($_SESSION['id_ta'])){
    header("Location:../index.php");
  }
  
  if(isset($_GET['idl'])){
    require_once('../mysqli_connect.php');
	$idl = $_GET['idl'];
	$q = "delete from lokal where id_lkl=$idl";
	mysqli_query($dbc, $q);
  }
?>