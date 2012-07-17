<?php
session_start();
if(!isset($_SESSION['adm_prodi']) and !isset($_SESSION['adm_fak'])){
  $url = "../index.php";
  header("Location: $url");
  exit();
}

if(isset($_POST['submitted'])){

  if(isset($_SESSION['adm_prodi'])){ 
  
  require_once('../mysqli_connect.php');
  $thn = intval($_POST['tahun']);
  
  $q = "select * from kurikulum where tahun=$thn and id_prodi={$_SESSION['adm_prodi']}";
  $r = mysqli_query($dbc, $q);
  
  if(mysqli_num_rows($r)>0){
    echo'struck';
	/*$_SESSION['struck_kr'] = 'Kurikulum sudah ada dalam database';
	header("Location:cp.php");
	exit();*/
  }else{
  
    $q="insert into kurikulum(id_univ, id_fak, id_prodi, tahun) values({$_SESSION['idpu']}, {$_SESSION['idpf']}, {$_SESSION['adm_prodi']}, $thn)";
    mysqli_query($dbc, $q);
	
	if(mysqli_affected_rows($dbc)>0){
	  echo'success';
      /*$url = "cp.php";
	  header("Location: $url");
	  exit();*/
    }else{
	  echo'error';
      /*$_SESSION['error_kr'] = 'Terjadi kesalahan pada sistem, tahun akademik gagal dibuat.';
	  header("Location:cp.php");
	  exit();*/
    }
	
  }
  
  }elseif(isset($_SESSION['adm_fak'])){
  
  require_once('../mysqli_connect.php');
  $thn = intval($_POST['tahun']);
  
  $q = "select * from kurikulum where tahun=$thn and id_prodi={$_SESSION['idpx']}";
  $r = mysqli_query($dbc, $q);
  
  if(mysqli_num_rows($r)>0){
    echo'struck';
	/*$_SESSION['struck_kr'] = 'Kurikulum sudah ada dalam database';
	header("Location:cp.php");
	exit();*/
  }else{
  
    $q="insert into kurikulum(id_univ, id_fak, id_prodi, tahun) values({$_SESSION['idfu']}, {$_SESSION['adm_fak']}, {$_SESSION['idpx']}, $thn)";
    mysqli_query($dbc, $q);
	
	if(mysqli_affected_rows($dbc)>0){
	  echo'success';
      /*$url = "cp.php";
	  header("Location: $url");
	  exit();*/
    }else{
	  echo'error';
      /*$_SESSION['error_kr'] = 'Terjadi kesalahan pada sistem, tahun akademik gagal dibuat.';
	  header("Location:cp.php");
	  exit();*/
    }
	
  }
  
  }
  
}
?>