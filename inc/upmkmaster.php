<?php
session_start();
if(!isset($_SESSION['adm_prodi']) and !isset($_SESSION['adm_fak'])/* or !isset($_SESSION['id_ta'])*/){
  header("Location: index.php");
  exit();
}

if(isset($_POST['submitted'])){
  
  if(isset($_SESSION['adm_prodi'])){
  
  require('../mysqli_connect.php');
  $kdm = mysqli_real_escape_string($dbc, strip_tags($_POST['kdm']));
  $matkul = mysqli_real_escape_string($dbc, strip_tags($_POST['matkul']));
  $sks = intval($_POST['sks']);
  $idm = intval($_POST['idm']);
  $idp = intval($_SESSION['adm_prodi']);
  $idu = intval($_SESSION['idpu']);
  $idf = intval($_SESSION['idpf']);
  
  if(!empty($kdm) and !empty($matkul) and $sks!="-"){
  
    $q = "select * from matkulmaster where (kd_matkul='$kdm' and id_prodi=$idp and id_matkul!=$idm)";
	$r = mysqli_query($dbc, $q);
	  
	if(!preg_match('/^[A-Za-z0-9.-_]+$/', $kdm)){
	  echo'kdm';
	}elseif(!preg_match('/^.{1,60}$/', $matkul)){
	  echo'matkul';
	}elseif(mysqli_num_rows($r)>0){
	  echo'struck';
	}else{
	  $qi = "update matkulmaster set kd_matkul='$kdm', nama='$matkul', sks='$sks' where id_matkul=$idm";
	  mysqli_query($dbc, $qi);
	  echo'success';
	}
	  
  }else{
    echo'kosong';
  }
  
  }elseif(isset($_SESSION['adm_fak'])){
  
  require('../mysqli_connect.php');
  $kdm = mysqli_real_escape_string($dbc, strip_tags($_POST['kdm']));
  $matkul = mysqli_real_escape_string($dbc, strip_tags($_POST['matkul']));
  $sks = intval($_POST['sks']);
  $idm = intval($_POST['idm']);
  $idp = intval($_SESSION['idpx']);
  $idu = intval($_SESSION['idfu']);
  $idf = intval($_SESSION['adm_fak']);
  
  if(!empty($kdm) and !empty($matkul) and $sks!="-"){
  
    $q = "select * from matkulmaster where (kd_matkul='$kdm' and id_prodi=$idp and id_matkul!=$idm)";
	$r = mysqli_query($dbc, $q);
	  
	if(!preg_match('/^[A-Za-z0-9.-_]+$/', $kdm)){
	  echo'kdm';
	}elseif(!preg_match('/^.{1,60}$/', $matkul)){
	  echo'matkul';
	}elseif(mysqli_num_rows($r)>0){
	  echo'struck';
	}else{
	  $qi = "update matkulmaster set kd_matkul='$kdm', nama='$matkul', sks='$sks' where id_matkul=$idm";
	  mysqli_query($dbc, $qi);
	  echo'success';
	}
	  
  }else{
    echo'kosong';
  }
  
  }
  
}
?>