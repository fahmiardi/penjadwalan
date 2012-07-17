<?php
  session_start();
  if(!isset($_SESSION['adm_prodi']) and !isset($_SESSION['adm_fak'])){
    header("Location:../index.php");
  }
  
  if(isset($_GET['nod'])){
    require_once('../mysqli_connect.php');
	$nod = $_GET['nod'];
	$q = "delete from dosen where nod=$nod";
	mysqli_query($dbc, $q);
	$qj = "delete from jadwal where nod=$nod";
	mysqli_query($dbc, $qj);
  }
?>