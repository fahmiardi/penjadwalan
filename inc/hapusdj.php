<?php
  session_start();
  if(!isset($_SESSION['adm_super'])){
    header("Location:../index.php");
  }
  
  if(isset($_GET['ta'])){
    require_once('../mysqli_connect.php');
	$id_ta = $_GET['ta'];
	$qt = "delete from ta where id_TA=$id_ta";
	mysqli_query($dbc, $qt);
	$qm = "delete from matkul where id_TA=$id_ta";
	mysqli_query($dbc, $qm);
	$qh = "delete from hariaktif where id_TA=$id_ta";
	mysqli_query($dbc, $qh);
	$qk = "delete from kelas where id_TA=$id_ta";
	mysqli_query($dbc, $qk);
	$ql = "delete from lokal where id_TA=$id_ta";
	mysqli_query($dbc, $ql);
	$qtj = "delete from tabeljam where id_TA=$id_ta";
	mysqli_query($dbc, $qtj);
	$qact = "delete from status where id_TA=$id_ta";
	mysqli_query($dbc, $qact);
  }
?>