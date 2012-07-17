<?php
session_start();
if(!isset($_SESSION['adm_fak'])){
  header("Location:../index.php");
  exit();
}
  
if(isset($_POST['submitted'])){
  
  if(!empty($_POST['nama']) and !empty($_POST['username']) and !empty($_POST['password'])){
	
	require_once('../mysqli_connect.php');
    $nama = mysqli_real_escape_string($dbc, strip_tags($_POST['nama']));
	$user = mysqli_real_escape_string($dbc, strip_tags($_POST['username']));
	$pass = mysqli_real_escape_string($dbc, strip_tags($_POST['password']));
	
	$q = "select * from prodi where nama='$nama' or username='$user'";
	$r = mysqli_query($dbc, $q);
	
	if(!preg_match('/^.{6,40}$/', $user)){
	  echo'username';
	}elseif(!preg_match('/^.{8,40}$/', $pass)){
	  echo'password';
	}elseif(mysqli_num_rows($r)>0){
	  echo'struck';
	}else{
	
	  $qu = "select id_univ from fakultas where id_fak={$_SESSION['adm_fak']}";
	  $ru = mysqli_query($dbc, $qu);
	  list($idu) = mysqli_fetch_row($ru);
	  $q = "insert into prodi(nama, username, password, id_fak, id_univ) values('$nama', '$user', aes_encrypt('$pass', 'f1sh6uts'), {$_SESSION['adm_fak']}, $idu)";
	  $r = mysqli_query($dbc, $q);
	  
	  $rp = mysqli_query($dbc, "select id_prodi from prodi where nama='$nama' and username='$user' and id_fak={$_SESSION['adm_fak']} and id_univ=$idu");
	  list($idp) = mysqli_fetch_row($rp);
	  $rta = mysqli_query($dbc, "select id_TA from ta");
	  if(mysqli_num_rows($rta)>0){
	    while(list($ta)=mysqli_fetch_row($rta)){
	      $ract = mysqli_query($dbc, "select * from status where id_TA=$ta and id_prodi=$idp");
	      if(mysqli_num_rows($ract)==0){
		    $sql = "insert into status(id_TA, id_prodi, status) values($ta, $idp, 0)";
		    $res = mysqli_query($dbc, $sql);
	      }
	    }
	  }
	  
	  echo'success';
	  
	  /*if(mysqli_affected_rows($dbc)>0){
	    echo'success';
	  }else{
	    echo'error'; 
	  }*/
	  
	}
	
  }else{
    echo'kosong';
  }
	
}
?>