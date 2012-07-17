<?php
  session_start();
  if(!isset($_SESSION['adm_fak'])){
    header("Location:../index.php");
	exit();
  }
  
  if(isset($_POST['submitted'])){
  
    if(!empty($_POST['nama']) and !empty($_POST['muat'])){
	
	require_once('../mysqli_connect.php');
    $nama = mysqli_real_escape_string($dbc, strip_tags($_POST['nama']));
	$muat = mysqli_real_escape_string($dbc, strip_tags($_POST['muat']));
	
	$q = "select * from lokalmaster where nama='$nama' and id_fak={$_SESSION['adm_fak']}";
	$r = mysqli_query($dbc, $q);
	
	if(!preg_match('/^[0-9]+$/', $muat)){
	  echo'kapasitas';
	}elseif(mysqli_num_rows($r)>0){
	  echo'struck';
	}else{
	  $q = "insert into lokalmaster(nama, muat, id_fak, id_univ) values('$nama', $muat, {$_SESSION['adm_fak']}, {$_SESSION['idfu']})";
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