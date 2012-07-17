<?php
session_start();
if(!isset($_SESSION['adm_super']) and !isset($_SESSION['adm_univ']) and !isset($_SESSION['adm_fak'])){
  $url = "../index.php";
  header("Location: $url");
  exit();
}

if(isset($_POST['submitted'])){

  require_once('../mysqli_connect.php');
  $_SESSION['tahun'] = $_POST['tahun'];
  $_SESSION['ajaran'] = $_POST['ajaran'];
  $thn = $_POST['tahun'];
  $aj = $_POST['ajaran'];
  
  $q = "select * from ta where tahun='$thn' and ajaran=$aj";
  $r = mysqli_query($dbc, $q);
  
  if(mysqli_num_rows($r)>0){
	$_SESSION['struck_ta'] = 'Tahun akademik sudah ada dalam database';
	header("Location:cp.php");
	exit();
  }else{
  
    $q="insert into ta(tahun, ajaran) values('$thn', $aj)";
    $r=mysqli_query($dbc, $q);
	
	$rta = mysqli_query($dbc, "select id_TA from ta where tahun='$thn' and ajaran=$aj");
	list($ta) = mysqli_fetch_row($rta);
	$rp = mysqli_query($dbc, "select id_prodi from prodi");
	if(mysqli_num_rows($rp)>0){
	  while(list($idp)=mysqli_fetch_row($rp)){
	    $rtrig = mysqli_query($dbc, "select * from status where id_TA=$ta and id_prodi=$idp");
	    if(mysqli_num_rows($rtrig)==0){
		  $sql = "insert into status(id_TA, id_prodi, status) values($ta, $idp, 0)";
		  $res = mysqli_query($dbc, $sql);
	    }
	  }
	}
	
	if(mysqli_affected_rows($dbc)>0){
      $url = "cp.php";
	  header("Location: $url");
	  exit();
    }else{
      $_SESSION['error_ta'] = 'Terjadi kesalahan pada sistem, tahun akademik gagal dibuat.';
	  header("Location:cp.php");
	  exit();
    }
	
  }
  
}
?>