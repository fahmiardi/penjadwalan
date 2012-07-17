<?php
session_start();
if(!isset($_SESSION['adm_super']) and !isset($_SESSION['adm_univ']) and !isset($_SESSION['adm_fak']) and !isset($_SESSION['adm_prodi']) and !isset($_SESSION['nod'])){

  $url='index.php';
  header("Location: $url");
  exit();
  
}

if(isset($_POST['submitted'])){
  
  require_once('../mysqli_connect.php');

  $pass = mysqli_real_escape_string($dbc, strip_tags($_POST['password']));
  $newpass = mysqli_real_escape_string($dbc, strip_tags($_POST['newpass1']));
  
  if(isset($_SESSION['adm_super'])){
    $q="update admin set password=aes_encrypt('$newpass', 'f1sh6uts') where id_admin={$_SESSION['adm_super']} and password=aes_encrypt('$pass', 'f1sh6uts') limit 1";
    $r=mysqli_query($dbc, $q);
  }elseif(isset($_SESSION['adm_univ'])){
    $q="update univ set password=aes_encrypt('$newpass', 'f1sh6uts') where id_univ={$_SESSION['adm_univ']} and password=aes_encrypt('$pass', 'f1sh6uts') limit 1";
    $r=mysqli_query($dbc, $q);
  }elseif(isset($_SESSION['adm_fak'])){
    $q="update fakultas set password=aes_encrypt('$newpass', 'f1sh6uts') where id_fak={$_SESSION['adm_fak']} and password=aes_encrypt('$pass', 'f1sh6uts') limit 1";
    $r=mysqli_query($dbc, $q);
  }elseif(isset($_SESSION['adm_prodi'])){
    $q="update prodi set password=aes_encrypt('$newpass', 'f1sh6uts') where id_prodi={$_SESSION['adm_prodi']} and password=aes_encrypt('$pass', 'f1sh6uts') limit 1";
    $r=mysqli_query($dbc, $q);
  }elseif(isset($_SESSION['nod'])){
    $q="update dosen set password=aes_encrypt('$newpass', 'f1sh6uts') where nod={$_SESSION['nod']} and password=aes_encrypt('$pass', 'f1sh6uts') limit 1";
    $r=mysqli_query($dbc, $q);
  }
  
  if(mysqli_affected_rows($dbc)>0){
    echo'success';
  }else{
    echo'block';
  }
  
}
?>