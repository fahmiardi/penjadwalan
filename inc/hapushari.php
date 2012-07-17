<?php
  session_start();
  if(!isset($_SESSION['adm_fak']) and !isset($_SESSION['id_ta'])){
    header("Location:../index.php");
  }
  
  if(isset($_GET['idh'])){
    require_once('../mysqli_connect.php');
	$idh = $_GET['idh'];
	$q = "delete from hariaktif where id_hari=$idh and id_fak={$_SESSION['adm_fak']} and id_TA={$_SESSION['id_ta']}";
	mysqli_query($dbc, $q);
  }
?>