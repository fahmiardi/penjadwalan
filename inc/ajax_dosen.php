<?php
session_start();
if((!isset($_SESSION['adm_prodi']) or !isset($_SESSION['id_ta'])) and (!isset($_SESSION['adm_fak']) or !isset($_SESSION['id_ta']))){
  header("Location:../index.php");
}

if($_POST['idp']){

  require_once('../mysqli_connect.php');
  
  $q = "select nod, nama from dosen where id_prodi={$_POST['idp']} order by nama asc";
  $r = mysqli_query($dbc, $q);
  if(mysqli_num_rows($r)>0){
    echo'<option value="-">-</option>';
    while(list($nod,$dosen)=mysqli_fetch_row($r)){
	  echo"<option value=\"$nod\">$dosen</option>";
	}
  }else{
    echo'<option value="-">-</option>';
  }
 
  
}
?>
