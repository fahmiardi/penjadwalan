<?php
session_start();

  if(!isset($_SESSION['adm_super']) and !isset($_SESSION['adm_univ']) and !isset($_SESSION['adm_fak']) and !isset($_SESSION['adm_prodi']) and !isset($_SESSION['nod'])){
  
    $url='../index.php';
	header("Location: $url");
	exit();
	
  }else{
  
    $_SESSION = array();
	session_destroy();
	
    header("Location:../index.php");
    exit();
	
  }
  
  
?>