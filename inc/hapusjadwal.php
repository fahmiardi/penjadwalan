<?php
  session_start();
  if((!isset($_SESSION['adm_prodi']) and !isset($_SESSION['id_ta'])) and (!isset($_SESSION['adm_fak']) or !isset($_SESSION['id_ta']))){
    header("Location:../index.php");
  }
  
  if(isset($_GET['idjad'])){
    require_once('../mysqli_connect.php');
	$idjad = $_GET['idjad'];
	mysqli_query($dbc, "delete from jadwal where id_jad=$idjad");
  }
?>