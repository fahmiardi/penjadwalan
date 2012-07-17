<?php
session_start();
if(!isset($_SESSION['nod']) or !isset($_SESSION['id_ta'])){
  header("Location:../index.php");
}

if($_POST['idl']){

  require_once('../mysqli_connect.php');
  $idl = $_POST['idl'];
  
  if($idl=='-'){
    echo"<span class=\"dOcKaps\">-</span>";
  }else{
    $q = "select muat from lokal where id_lkl=$idl";
    $r = mysqli_query($dbc, $q);
    list($kaps) = mysqli_fetch_row($r);
    echo"<span class=\"dOcKaps\">$kaps</span>";
  }
  
}
?>