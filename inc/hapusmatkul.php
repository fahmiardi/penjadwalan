<?php
  session_start();
  if(!isset($_SESSION['adm_prodi']) and !isset($_SESSION['adm_fak'])){
    header("Location:../index.php");
  }
  
  if(isset($_GET['idm'])){
    require_once('../mysqli_connect.php');
	list($smstr, $idkr, $idm) = explode('-', $_GET['idm']);
	$q = "delete from matkul where id_matkul=$idm and smstr=$smstr and id_krklm=$idkr";
	mysqli_query($dbc, $q);
	//$qj = "delete from jadwal where id_matkul=$idm";
	//mysqli_query($dbc, $qj);
  }
?>