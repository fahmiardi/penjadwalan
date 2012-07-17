<?php
session_start();
if((!isset($_SESSION['adm_prodi']) or !isset($_SESSION['id_ta'])) and (!isset($_SESSION['adm_fak']) or !isset($_SESSION['id_ta']))){
  header("Location:../index.php");
}


if($_POST['idm']){

  require_once('../mysqli_connect.php');
  
  if($_POST['idm']=='-'){
    echo'<option value="1">-</option>';
  }else{
  
    $sks = substr($_POST['idm'], 0, 1);
    if($sks==4){
	  echo'<option value="-">-</option>';
	  echo'<option value="2.2">2 - 2</option>';
	}elseif($$sks==5){
	  echo'<option value="-">-</option>';
	  echo'<option value="3.2">3 - 2</option>';
	}elseif($sks==6){
	  echo'<option value="-">-</option>';
	  echo'<option value="3.3">3 - 3</option>';
	  echo'<option value="2.2.2">2 - 2 - 2</option>';
	}elseif($sks==7){
	  echo'<option value="-">-</option>';
	  echo'<option value="4.3">4 - 3</option>';
	  echo'<option value="3.2.2">3 - 2 - 2</option>';
	}elseif($sks==8){
	  echo'<option value="-">-</option>';
	  echo'<option value="4.4">4 - 4</option>';
	  echo'<option value="4.2.2">4 - 2 - 2</option>';
	  echo'<option value="3.3.2">3 - 3 - 2</option>';
	  echo'<option value="2.2.2.2">2 - 2 - 2 - 2</option>';
	}
  
  }
  
}
?>