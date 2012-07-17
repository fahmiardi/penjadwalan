<?php
session_start();
if(!isset($_SESSION['adm_super'])){
    header("Location:../index.php");
}else{
 
  require_once('../mysqli_connect.php');
  mysqli_query($dbc, "truncate univ");
  mysqli_query($dbc, "truncate prodi");
  mysqli_query($dbc, "truncate fakultas");
  mysqli_query($dbc, "truncate dosen");
  mysqli_query($dbc, "truncate hariaktif");
  mysqli_query($dbc, "truncate jadwal");
  mysqli_query($dbc, "truncate kelas");
  mysqli_query($dbc, "truncate lokal");
  mysqli_query($dbc, "truncate matkul");
  mysqli_query($dbc, "truncate tabeljam");
  mysqli_query($dbc, "truncate ta");
  mysqli_query($dbc, "truncate kurikulum");
  mysqli_query($dbc, "truncate lokalmaster");
  
  $_SESSION = array();
  session_destroy();
  header("Location:../index.php");
}
?>