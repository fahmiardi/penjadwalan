<?php
  session_start();
  if(!isset($_SESSION['adm_univ'])){
    header("Location:../index.php");
  }
  
  if(isset($_GET['idf'])){
    require_once('../mysqli_connect.php');
	$idf = $_GET['idf'];
	$q = "delete from fakultas where id_fak=$idf";
	mysqli_query($dbc, $q);
	$qp = "delete from prodi where id_fak=$idf";
	mysqli_query($dbc, $qp);
	$qd = "delete from dosen where id_fak=$idf";
	mysqli_query($dbc, $qd);
	$qj = "delete from jadwal where id_fak=$idf";
	mysqli_query($dbc, $qj);
	$qm = "delete from matkul where id_fak=$idf";
	mysqli_query($dbc, $qm);
	$qt = "delete from tabeljam where id_fak=$idf";
	mysqli_query($dbc, $qt);
	$qh = "delete from hariaktif where id_fak=$idf";
	mysqli_query($dbc, $qh);
	$ql = "delete from lokal where id_fak=$idf";
	mysqli_query($dbc, $ql);
	$qk = "delete from kelas where id_fak=$idf";
	mysqli_query($dbc, $qk);
	$rp = mysqli_query($dbc, "select id_prodi from prodi where id_fak=$idf");
	if(mysqli_num_rows($rp)>0){
	  while(list($idp)=mysqli_fetch_row($rp)){
	    mysqli_query($dbc, "delete from status where id_prodi=$idp");
	  }
	}
  }
?>