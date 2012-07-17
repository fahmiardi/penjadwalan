<?php

if(isset($_POST['submitted'])){

  session_start();
  require_once('../mysqli_connect.php');
  
  $usr=mysqli_real_escape_string($dbc, trim(strip_tags($_POST['username'])));
  $pass=mysqli_real_escape_string($dbc, trim(strip_tags($_POST['password'])));

  $q = "select * from admin where username='$usr' and password=aes_encrypt('$pass', 'f1sh6uts')";
  $r = mysqli_query($dbc, $q);
  $qu = "select id_univ, nama from univ where username='$usr' and password=aes_encrypt('$pass', 'f1sh6uts')";
  $ru = mysqli_query($dbc, $qu);
  $qf = "select id_fak, id_univ, nama from fakultas where username='$usr' and password=aes_encrypt('$pass', 'f1sh6uts')";
  $rf = mysqli_query($dbc, $qf);
  $qp = "select id_prodi, id_fak, id_univ, nama from prodi where username='$usr' and password=aes_encrypt('$pass', 'f1sh6uts')";
  $rp = mysqli_query($dbc, $qp);
  $qd = "select nod, id_prodi, id_fak, id_univ, nip, nama from dosen where username='$usr' and password=aes_encrypt('$pass', 'f1sh6uts')";
  $rd = mysqli_query($dbc, $qd);
  
  if(mysqli_num_rows($r)>0){
	
    list($ids, $super) = mysqli_fetch_row($r);
	$_SESSION['adm_super']  =  $ids;
	$_SESSION['super']  =  $super;
	
	header("Location: cp.php");
	exit();
	
  }elseif(mysqli_num_rows($ru)>0){
	
    list($idu, $univ) = mysqli_fetch_row($ru);
	$_SESSION['adm_univ']  =  $idu;
	$_SESSION['univ']  =  $univ;
	
	header("Location: cp.php");
	exit();
	
  }elseif(mysqli_num_rows($rf)>0){
	
    list($idf, $idfu, $fak) = mysqli_fetch_row($rf);
	$_SESSION['adm_fak']  =  $idf;
	$_SESSION['idfu'] = $idfu;
	$_SESSION['fak']  =  $fak;
	
	header("Location: cp.php");
	exit();
	
  }elseif(mysqli_num_rows($rp)>0){
	
    list($idp, $idpf, $idpu, $prodi) = mysqli_fetch_row($rp);
	$_SESSION['adm_prodi']  =  $idp;
	$_SESSION['idpf'] = $idpf;
	$_SESSION['idpu'] = $idpu;
	$_SESSION['prodi']  =  $prodi;
	
	header("Location: cp.php");
	exit();
	
  }elseif(mysqli_num_rows($rd)>0){
	
    list($nod, $iddp, $iddf, $iddu, $nip, $dosen) = mysqli_fetch_row($rd);
	$_SESSION['nod']  =  $nod;
	$_SESSION['iddp'] = $iddp;
	$_SESSION['iddf'] = $iddf;
	$_SESSION['iddu'] = $iddu;
	$_SESSION['nip'] = $nip;
	$_SESSION['dosen']  =  $dosen;
	
	header("Location: cp.php");
	exit();
	
  }else{
    $_SESSION['failog']="Username / Password tidak valid!";
    header("Location:../index.php");
    exit();
  }
  
}

?>