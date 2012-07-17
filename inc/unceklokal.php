<?php
  session_start();
  if(!isset($_SESSION['adm_fak']) or !isset($_GET['lkVal']) or !isset($_SESSION['id_ta'])){
    header("Location:../index.php");
	exit();
  }else{
  
    require_once('../mysqli_connect.php');
    list($idl, $lokal, $muat) = explode('-', $_GET['lkVal']);
	mysqli_query($dbc, "delete from lokal where id_lkl=$idl and id_TA={$_SESSION['id_ta']}");
	if(mysqli_affected_rows($dbc)>0){
	  echo"Lokal $lokal Non-Aktif";
	}else{
	  echo'error';
	}
	
  }
?>