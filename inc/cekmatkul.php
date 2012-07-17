<?php
  session_start();
  if((!isset($_SESSION['adm_prodi']) or !isset($_SESSION['id_krklm'])) and (!isset($_SESSION['adm_fak']) or !isset($_SESSION['id_krklm']))){
    header("Location:../index.php");
	exit();
  }
  
  if(isset($_POST['submitted'])){
  
    if($_POST['idm']!='-' and $_POST['smstr']!='-'){
	
	require_once('../mysqli_connect.php');
    $idm = $_POST['idm'];
	$smstr = $_POST['smstr'];
	
	$q = "select * from matkul where id_matkul=$idm and id_krklm={$_SESSION['id_krklm']} and smstr=$smstr";
	$r = mysqli_query($dbc, $q);
	
	if(mysqli_num_rows($r)>0){
	  echo'struck';
	}else{
	  $q = "insert into matkul(id_matkul, id_krklm, smstr) values($idm, {$_SESSION['id_krklm']}, $smstr)";
	  $r = mysqli_query($dbc, $q);
	  if(mysqli_affected_rows($dbc)>0){
	    echo'success';
	  }else{
	    echo'error';
	  }
	}
	
	}else{
	  echo'kosong';
	}
	
  }
?>