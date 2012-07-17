<?php
  session_start();
  if(!isset($_SESSION['adm_fak']) or !isset($_GET['lkVal']) or !isset($_SESSION['id_ta'])){
    header("Location:../index.php");
	exit();
  }else{
  
    require_once('../mysqli_connect.php');
    list($idl, $lokal, $muat) = explode('-', $_GET['lkVal']);
	mysqli_query($dbc, "insert into lokal(id_lkl, id_univ, id_fak, nama, muat, id_TA) values($idl, {$_SESSION['idfu']}, {$_SESSION['adm_fak']}, '$lokal', $muat, {$_SESSION['id_ta']})");
	if(mysqli_affected_rows($dbc)>0){
	  echo"Lokal $lokal Aktif";
	}else{
	  echo'error';
	}
	
  }
?>