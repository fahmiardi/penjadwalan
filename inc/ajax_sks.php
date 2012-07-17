<?php
session_start();
if((!isset($_SESSION['adm_prodi']) or !isset($_SESSION['id_ta'])) and (!isset($_SESSION['adm_fak']) or !isset($_SESSION['id_ta']))){
  header('Location:../index.php');
}

if($_POST['idm']){

require_once('../mysqli_connect.php');
  
  $idm = substr($_POST['idm'], 1);
  
  if($_POST['idm']=='-'){
    echo'-';
  }else{
    $q = "select sks from matkulmaster where id_matkul=$idm";
    $r = mysqli_query($dbc, $q);
    list($sks) = mysqli_fetch_row($r);
    echo"$sks";	
  }
  
  
}
?>