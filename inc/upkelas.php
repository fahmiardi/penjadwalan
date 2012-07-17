<?php
  session_start();
  if((!isset($_SESSION['adm_prodi']) or !isset($_SESSION['id_ta'])) and (!isset($_SESSION['adm_fak']) or !isset($_SESSION['id_ta']))){
    header("Location:../index.php");
	exit();
  }
  
  
  if(isset($_POST['submitted'])){
    
	if(isset($_SESSION['adm_prodi'])){
	
    if(!empty($_POST['nama'])){
	
	require_once('../mysqli_connect.php');
    $nama = mysqli_real_escape_string($dbc, strip_tags($_POST['nama']));
	$mhs = mysqli_real_escape_string($dbc, strip_tags($_POST['mhs']));
	$smstr = $_POST['smstr'];
	$idk = $_POST['idk'];
	
	$q = "select * from kelas where nama='$nama' and smstr=$smstr and id_prodi={$_SESSION['adm_prodi']} and id_TA={$_SESSION['id_ta']} and id_kls!=$idk";
	$r = mysqli_query($dbc, $q);
	
	if(!preg_match('/^[0-9]+$/', $mhs)){
	  echo'mhs';
	}elseif(mysqli_num_rows($r)>0){
	  echo'struck';
	}else{
	  $q = "update kelas set nama='$nama', mhs=$mhs, smstr=$smstr, id_prodi={$_SESSION['adm_prodi']}, id_fak={$_SESSION['idpf']}, id_univ={$_SESSION['idpu']} where id_kls=$idk";
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
	
	}elseif(isset($_SESSION['adm_fak'])){
	
	if(!empty($_POST['nama'])){
	
	require_once('../mysqli_connect.php');
    $nama = mysqli_real_escape_string($dbc, strip_tags($_POST['nama']));
	$mhs = mysqli_real_escape_string($dbc, strip_tags($_POST['mhs']));
	$smstr = $_POST['smstr'];
	$idk = $_POST['idk'];
	
	$q = "select * from kelas where nama='$nama' and smstr=$smstr and id_prodi={$_SESSION['idpx']} and id_TA={$_SESSION['id_ta']} and id_kls!=$idk";
	$r = mysqli_query($dbc, $q);
	
	if(!preg_match('/^[0-9]+$/', $mhs)){
	  echo'mhs';
	}elseif(mysqli_num_rows($r)>0){
	  echo'struck';
	}else{
	  $q = "update kelas set nama='$nama', mhs=$mhs, smstr=$smstr, id_prodi={$_SESSION['idpx']}, id_fak={$_SESSION['adm_fak']}, id_univ={$_SESSION['idfu']} where id_kls=$idk";
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
 
  }
  
?>