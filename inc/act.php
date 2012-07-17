<?php
 session_start();
 if((!isset($_SESSION['id_ta']) or !isset($_SESSION['adm_prodi'])) and (!isset($_SESSION['id_ta']) or !isset($_SESSION['adm_fak']))){
   header("Location:../index.php");
 }
 
 if(isset($_POST['submitted'])){
 
   if(isset($_SESSION['adm_prodi'])){
   
   require_once('../mysqli_connect.php');
   $act = $_POST['act'];
   if($act==1){
     mysqli_query($dbc, "update status set status=$act where id_prodi={$_SESSION['adm_prodi']} and id_TA={$_SESSION['id_ta']}");
	 echo'aktif';
	 //header("Location:dj.php?ta={$_SESSION['id_ta']}&aj={$_SESSION['aj']}");
   }else{
     mysqli_query($dbc, "update status set status=$act where id_prodi={$_SESSION['adm_prodi']} and id_TA={$_SESSION['id_ta']}");
	 echo'non-aktif';
	 //header("Location:dj.php?ta={$_SESSION['id_ta']}&aj={$_SESSION['aj']}");
   }
   
   }elseif(isset($_SESSION['adm_fak'])){
   
   require_once('../mysqli_connect.php');
   $act = $_POST['act'];
   if($act==1){
     mysqli_query($dbc, "update status set status=$act where id_prodi={$_SESSION['idpx']} and id_TA={$_SESSION['id_ta']}");
	 echo'aktif';
	 //header("Location:dj.php?ta={$_SESSION['id_ta']}&aj={$_SESSION['aj']}");
   }else{
     mysqli_query($dbc, "update status set status=$act where id_prodi={$_SESSION['idpx']} and id_TA={$_SESSION['id_ta']}");
	 echo'non-aktif';
	 //header("Location:dj.php?ta={$_SESSION['id_ta']}&aj={$_SESSION['aj']}");
   }
   
   }
   
 }
?>