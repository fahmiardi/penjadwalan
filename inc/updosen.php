<?php
session_start();
if(!isset($_SESSION['adm_prodi']) and !isset($_SESSION['adm_fak'])/* or !isset($_SESSION['id_ta'])*/){
  header("Location: index.php");
  exit();
}

if(isset($_POST['submitted'])){

  if(isset($_SESSION['adm_prodi'])){

  require('../mysqli_connect.php');
  $dosen = mysqli_real_escape_string($dbc, strip_tags($_POST['nama']));
  $user = mysqli_real_escape_string($dbc, strip_tags($_POST['username']));
  $pass = mysqli_real_escape_string($dbc, strip_tags($_POST['password']));
  $email = mysqli_real_escape_string($dbc, strip_tags($_POST['email']));
  $telp = mysqli_real_escape_string($dbc, strip_tags($_POST['telp']));
  $nip = mysqli_real_escape_string($dbc, strip_tags($_POST['nip']));
  $nod = intval($_POST['nod']);
  $idp = intval($_SESSION['adm_prodi']);
  $idu = intval($_SESSION['idpu']);
  $idf = intval($_SESSION['idpf']);
  
  if(!empty($dosen) and !empty($user) and !empty($pass)){
  
    $q = "select * from dosen where (nip='$nip' and id_univ=$idu and nod!=$nod) or (username='$user' and nod!=$nod)";
	$r = mysqli_query($dbc, $q);
	  
	if(!preg_match('/^[A-Za-z0-9\s.-]*$/', $nip)){
	  echo'nip';
	}elseif(!preg_match('/^.{6,40}$/', $user)){
	  echo'username';
	}elseif(!preg_match('/^.{8,40}$/', $pass)){
	  echo'password';
	}elseif(!preg_match('/^[0-9.-\s]*$/', $telp)){
	  echo'telp';
	}elseif(!preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$/', $email)){
	  echo'email';
	}elseif(mysqli_num_rows($r)>0){
	  echo'struck';
	}else{
	  $qi = "update dosen set nama='$dosen', nip='$nip', username='$user', password=aes_encrypt('$pass', 'f1sh6uts'), email='$email', telp='$telp' where nod=$nod";
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
  
  }elseif(isset($_SESSION['adm_fak'])){
  
  require('../mysqli_connect.php');
  $dosen = mysqli_real_escape_string($dbc, strip_tags($_POST['nama']));
  $user = mysqli_real_escape_string($dbc, strip_tags($_POST['username']));
  $pass = mysqli_real_escape_string($dbc, strip_tags($_POST['password']));
  $email = mysqli_real_escape_string($dbc, strip_tags($_POST['email']));
  $telp = mysqli_real_escape_string($dbc, strip_tags($_POST['telp']));
  $nip = mysqli_real_escape_string($dbc, strip_tags($_POST['nip']));
  $nod = intval($_POST['nod']);
  $idp = intval($_SESSION['idpx']);
  $idu = intval($_SESSION['idfu']);
  $idf = intval($_SESSION['adm_fak']);
  
  if(!empty($dosen) and !empty($user) and !empty($pass)){
  
    $q = "select * from dosen where (nip='$nip' and id_univ=$idu and nod!=$nod) or (username='$user' and nod!=$nod)";
	$r = mysqli_query($dbc, $q);
	  
	if(!preg_match('/^[0-9\s.-]*$/', $nip)){
	  echo'nip';
	}elseif(!preg_match('/^.{6,40}$/', $user)){
	  echo'username';
	}elseif(!preg_match('/^.{8,40}$/', $pass)){
	  echo'password';
	}elseif(!preg_match('/^[0-9.-\s]*$/', $telp)){
	  echo'telp';
	}elseif(!preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$/', $email)){
	  echo'email';
	}elseif(mysqli_num_rows($r)>0){
	  echo'struck';
	}else{
	  $qi = "update dosen set nama='$dosen', nip='$nip', username='$user', password=aes_encrypt('$pass', 'f1sh6uts'), email='$email', telp='$telp' where nod=$nod";
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
  
}
?>