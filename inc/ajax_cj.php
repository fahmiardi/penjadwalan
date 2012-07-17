<?php
session_start();
if(!isset($_SESSION['adm_fak']) or !isset($_SESSION['id_ta'])){
  header('Location:../index.php');
}else{

  require_once('../mysqli_connect.php');
  $minJ = 1;
  $rj = mysqli_query($dbc, "select MAX(jam_kul) from tabeljam where id_fak={$_SESSION['adm_fak']} and id_TA={$_SESSION['id_ta']}");
  if(mysqli_num_rows($rj)>0){
	list($maxJ) = mysqli_fetch_row($rj);
	$pJ = $minJ + $maxJ;
	echo $pJ;
  }else{
	$pJ = $minJ;
	echo $pJ;
  }
  
}
?>