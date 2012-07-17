<?php
session_start();
if(!isset($_SESSION['adm_prodi']) and !isset($_SESSION['adm_fak'])/* or !isset($_SESSION['id_ta'])*/){
  header("Location: index.php");
  exit();
}

if(isset($_POST['submitted'])){
  
  require_once('../mysqli_connect.php');
  $kdm = mysqli_real_escape_string($dbc, strip_tags($_POST['kdm']));
  $matkul = mysqli_real_escape_string($dbc, strip_tags($_POST['matkul']));
  $sks = intval($_POST['sks']);
  $idp = $_POST['idp'];
  $idf = $_POST['idf'];
  $idu = $_POST['idu'];
  
  if(!empty($kdm) and !empty($matkul) and $sks!="-"){
  
    $q = "select * from matkulmaster where (kd_matkul='$kdm' and id_prodi=$idp)";
	$r = mysqli_query($dbc, $q);
	  
	if(!preg_match('/^[A-Za-z0-9.-_]+$/', $kdm)){
	  echo'kdm';
	}elseif(!preg_match('/^.{1,60}$/', $matkul)){
	  echo'matkul';
	}elseif(mysqli_num_rows($r)>0){
	  echo'struck';
	}else{
	  $qi = "insert into matkulmaster(kd_matkul, nama, sks, id_prodi, id_fak, id_univ)
	  values('$kdm', '$matkul', $sks, $idp, $idf, $idu)";
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