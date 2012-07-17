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
	$idl = $_POST['idl'];
	
	$q = "select * from lokalmaster where nama='$nama' and id_fak={$_SESSION['adm_fak']} and id_lkl!=$idl";
	$r = mysqli_query($dbc, $q);
	
	if(!preg_match('/^[0-9]+$/', $muat)){
	  echo'kapasitas';
	}elseif(mysqli_num_rows($r)>0){
	  echo'struck';
	}else{
	  $q = "update lokalmaster set nama='$nama', muat=$muat, id_fak={$_SESSION['adm_fak']}, id_univ={$_SESSION['idfu']} where id_lkl=$idl";
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