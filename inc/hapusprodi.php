<?php
  session_start();
  if(!isset($_SESSION['adm_fak'])){
    header("Location:../index.php");
  }
  
  if(isset($_GET['idp'])){
    require_once('../mysqli_connect.php');
	$idp = $_GET['idp'];
	$qp = "delete from prodi where id_prodi=$idp";
	mysqli_query($dbc, $qp);
	$qd = "delete from dosen where id_prodi=$idp";
	mysqli_query($dbc, $qd);
	$qj = "delete from jadwal where id_prodi=$idp";
	mysqli_query($dbc, $qj);
	$qm = "delete from matkul where id_prodi=$idp";
	mysqli_query($dbc, $qm);
	$qk = "delete from kelas where id_prodi=$idp";
	mysqli_query($dbc, $qk);
	$qtj = "delete from tabeljam where id_prodi=$idp";
	mysqli_query($dbc, $qtj);
	$qact = "delete from status where id_prodi=$idp";
	mysqli_query($dbc, $qact);
  }
?>