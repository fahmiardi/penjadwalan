<?php
session_start();
if(!isset($_SESSION['adm_prodi']) and !isset($_SESSION['adm_fak'])/* or !isset($_SESSION['id_ta'])*/){
  header("Location:../index.php");
  exit();
}

if(isset($_POST['submitted'])){
  
  require_once('../mysqli_connect.php');
  $dosen = mysqli_real_escape_string($dbc, strip_tags($_POST['nama']));
  $user = mysqli_real_escape_string($dbc, strip_tags($_POST['username']));
  $pass = mysqli_real_escape_string($dbc, strip_tags($_POST['password']));
  $email = mysqli_real_escape_string($dbc, strip_tags($_POST['email']));
  $telp = mysqli_real_escape_string($dbc, strip_tags($_POST['telp']));
  $nip = mysqli_real_escape_string($dbc, strip_tags($_POST['nip']));
  $idp = $_POST['idp'];
  $idf = $_POST['idf'];
  $idu = $_POST['idu'];
  
  if(!empty($dosen) and !empty($user) and !empty($pass)){
    
	$ru = mysqli_query($dbc, "select * from dosen where username='$user' and id_univ=$idu");
	if(!empty($nip)){
    $q = "select * from dosen where nip='$nip' and id_univ=$idu";
	$r = mysqli_query($dbc, $q);
	}
	if(!preg_match('/^[A-Za-z0-9\s.-]*$/', $nip)){
	  echo'nip';
	}elseif(!preg_match('/^.{6,40}$/', $user)){
	  echo'username';
	}elseif(!preg_match('/^.{8,40}$/', $pass)){
	  echo'password';
	}elseif(!preg_match('/^[0-9.-\s]*$/', $telp)){
	  echo'telp';
	}elseif(!preg_match('/^([\w.-]+@[\w.-]+\.[a-zA-Z]{2,6})*$/', $email)){
	  echo'email';
	}elseif(!empty($nip) and mysqli_num_rows($r)>0){
	  echo'struck';
	}elseif(mysqli_num_rows($ru)>0){
	  echo'struck';
	}else{
	  $qi = "insert into dosen(nip, nama, username, password, email, telp, id_prodi, id_fak, id_univ)
	  values('$nip', '$dosen', '$user', aes_encrypt('$pass', 'f1sh6uts'), '$email', '$telp', $idp, $idf, $idu)";
	  mysqli_query($dbc, $qi);
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