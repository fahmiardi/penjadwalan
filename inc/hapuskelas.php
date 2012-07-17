<?php
  session_start();
  if((!isset($_SESSION['adm_prodi']) or !isset($_SESSION['id_ta'])) and (!isset($_SESSION['adm_fak']) or !isset($_SESSION['id_ta']))){
    header("Location:../index.php");
  }
  
  if(isset($_GET['idk'])){
    require_once('../mysqli_connect.php');
	$idk = $_GET['idk'];
	$q = "delete from kelas where id_kls=$idk";
	mysqli_query($dbc, $q);
  }
?>