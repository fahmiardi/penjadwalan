<?php
session_start();
if(!isset($_SESSION['adm_prodi'])){
  header('../index.php');
}

if($_POST['idm']){

require_once('../mysqli_connect.php');
  
  $idm = $_POST['idm'];
  
  if($idm=='-'){
    echo'-';
  }else{
    $q = "select sks from matkulmaster where id_matkul=$idm";
    $r = mysqli_query($dbc, $q);
    list($sks) = mysqli_fetch_row($r);
    echo"$sks";
  }
  
}
?>