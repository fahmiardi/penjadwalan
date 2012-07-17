<?php
session_start();
if((!isset($_SESSION['adm_prodi']) or !isset($_SESSION['id_ta'])) and (!isset($_SESSION['adm_fak']) or !isset($_SESSION['id_ta']))){
  header('Location:../index.php');
}

if($_POST['smstr']){

  if(isset($_SESSION['adm_prodi'])){
  
  require_once('../mysqli_connect.php');
  
  $smstr = substr($_POST['smstr'], 0, 1);
  
  $q = "select id_kls, nama from kelas where smstr=$smstr and id_prodi={$_SESSION['adm_prodi']} and id_TA={$_SESSION['id_ta']} order by nama asc";
  $r = mysqli_query($dbc, $q);
  if(mysqli_num_rows($r)>0){
    echo"<option value=\"-\">-</option>";
    while(list($idk, $kelas) = mysqli_fetch_row($r)){
      echo"<option value=\"$idk\">$kelas</option>";
    }
  }else{
    echo'<option value="-">-</option>';
  }
  
  }elseif(isset($_SESSION['adm_fak'])){
  
  require_once('../mysqli_connect.php');
  
  $smstr = substr($_POST['smstr'], 0, 1);
  
  $q = "select id_kls, nama from kelas where smstr=$smstr and id_prodi={$_SESSION['idpx']} and id_TA={$_SESSION['id_ta']} order by nama asc";
  $r = mysqli_query($dbc, $q);
  if(mysqli_num_rows($r)>0){
    echo"<option value=\"-\">-</option>";
    while(list($idk, $kelas) = mysqli_fetch_row($r)){
      echo"<option value=\"$idk\">$kelas</option>";
    }
  }else{
    echo'<option value="-">-</option>';
  }
  
  }
  
}
?>