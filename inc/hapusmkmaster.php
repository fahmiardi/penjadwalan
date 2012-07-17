<?php
  session_start();
  if(!isset($_SESSION['adm_prodi']) and !isset($_SESSION['adm_fak'])){
    header("Location:../index.php");
  }
  
  if(isset($_GET['idm'])){
    require_once('../mysqli_connect.php');
	$idm = $_GET['idm'];
	$q = "delete from matkulmaster where id_matkul=$idm";
	mysqli_query($dbc, $q);
	$qj = "delete from jadwal where id_matkul=$idm";
	mysqli_query($dbc, $qj);
  }
?>