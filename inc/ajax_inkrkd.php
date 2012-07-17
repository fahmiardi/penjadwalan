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
    $q = "select kd_matkul from matkulmaster where id_matkul=$idm";
    $r = mysqli_query($dbc, $q);
    list($kdm) = mysqli_fetch_row($r);
    echo"$kdm";
  }
  
}
?>