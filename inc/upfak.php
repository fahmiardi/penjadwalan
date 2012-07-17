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
	$idf = $_POST['idf'];
	
	$q = "select * from fakultas where (nama='$nama' and id_fak!=$idf) or (username='$user' and id_fak!=$idf)";
	$r = mysqli_query($dbc, $q);
	
	if(!preg_match('/^.{6,40}$/', $user)){
	  echo'username';
	}elseif(!preg_match('/^.{8,40}$/', $pass)){
	  echo'password';
	}elseif(mysqli_num_rows($r)>0){
	  echo'struck';
	}else{
	  $q = "update fakultas set nama='$nama', username='$user', password=aes_encrypt('$pass', 'f1sh6uts') where id_fak=$idf";
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