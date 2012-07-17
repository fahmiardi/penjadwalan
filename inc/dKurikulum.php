<?php
  session_start();
  if(!isset($_SESSION['adm_prodi']) and !isset($_SESSION['adm_fak'])){
    header("Location:../index.php");
  }
  
  if(isset($_GET['kr'])){
    require_once('../mysqli_connect.php');
	$kr = $_GET['kr'];
	$q = "delete from kurikulum where id_krklm=$kr";
	mysqli_query($dbc, $q);
	$qm = "delete from matkul where id_krklm=$kr";
	mysqli_query($dbc, $qm);
	$r = mysqli_query($dbc, "select id_matkul from matkul where id_krklm=$kr");
	while(list($idm)=mysqli_fetch_row($r)){
	  mysqli_query($dbc, "delete from jadwal where id_matkul=$idm");
	}
  }
?>