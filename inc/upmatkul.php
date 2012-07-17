<?php
  session_start();
  if(!isset($_SESSION['adm_prodi']) or !isset($_SESSION['id_krklm'])){
    header("Location:../index.php");
	exit();
  }
  
  
  if(isset($_POST['submitted'])){
  
    if(!empty($_POST['nama'])){
	
	require_once('../mysqli_connect.php');
    $nama = mysqli_real_escape_string($dbc, strip_tags($_POST['nama']));
	$sks = mysqli_real_escape_string($dbc, strip_tags($_POST['sks']));
	$idm = $_POST['idm'];
	
	$q = "select * from matkul where nama='$nama' and id_prodi={$_SESSION['adm_prodi']} and id_krklm={$_SESSION['id_krklm']} and id_matkul!=$idm";
	$r = mysqli_query($dbc, $q);
	
	if(mysqli_num_rows($r)>0){
	  echo'struck';
	}else{
	  $q = "update matkul set nama='$nama', sks=$sks, id_prodi={$_SESSION['adm_prodi']}, id_fak={$_SESSION['idpf']}, id_univ={$_SESSION['idpu']} where id_matkul=$idm";
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