<?php
  session_start();
  if(!isset($_SESSION['adm_univ'])){
    header("Location:../index.php");
	exit();
  }
  
  if(isset($_POST['submitted'])){
  
    if(!empty($_POST['nama']) and !empty($_POST['username']) and !empty($_POST['password'])){
	
	require_once('../mysqli_connect.php');
    $nama = mysqli_real_escape_string($dbc, strip_tags($_POST['nama']));
	$user = mysqli_real_escape_string($dbc, strip_tags($_POST['username']));
	$pass = mysqli_real_escape_string($dbc, strip_tags($_POST['password']));
	
	$q = "select * from fakultas where (nama='$nama' and id_univ={$_SESSION['adm_univ']}) or (username='$user' and id_univ={$_SESSION['adm_univ']})";
	$r = mysqli_query($dbc, $q);
	
	if(!preg_match('/^.{6,40}$/', $user)){
	  echo'username';
	}elseif(!preg_match('/^.{8,40}$/', $pass)){
	  echo'password';
	}elseif(mysqli_num_rows($r)>0){
	  echo'struck';
	}else{
	  $q = "insert into fakultas(id_univ, nama, username, password) values({$_SESSION['adm_univ']}, '$nama', '$user', aes_encrypt('$pass', 'f1sh6uts'))";
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