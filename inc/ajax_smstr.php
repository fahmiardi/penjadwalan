<?php
session_start();
if((!isset($_SESSION['adm_prodi']) or !isset($_POST['idkr'])) and (!isset($_SESSION['adm_fak']) or !isset($_POST['idkr']))){
  header('../index.php');
}else{
  require_once('../mysqli_connect.php');
  
  $aj = substr($_POST['idkr'], 0, 1);
  $idkr = substr($_POST['idkr'], 1);
  
  if($aj==1){
    echo"<option value=\"-\">-</option>";
	$c = array(1=>1, 3=>3, 5=>5, 7=>7);
	foreach($c as $k){
	  echo"<option value=\"$k$idkr\">$k</option>";
	}
  }else{
    echo"<option value=\"-\">-</option>";
	$c = array(2=>2, 4=>4, 6=>6, 8=>8);
	foreach($c as $k){
	  echo"<option value=\"$k$idkr\">$k</option>";
	}
  }
   
}
?>