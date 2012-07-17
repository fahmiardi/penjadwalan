<?php
//echo'<option value="damn">damn</option>';
session_start();
if((!isset($_SESSION['adm_prodi']) or !isset($_POST['smstr'])) and (!isset($_SESSION['adm_fak']) or !isset($_POST['smstr']))){
  header('../index.php');
}else{
  require_once('../mysqli_connect.php');
  
  $smstr = substr($_POST['smstr'], 0, 1);
  $idkr = substr($_POST['smstr'], 1);
  
  $qm = "select mkm.id_matkul, mkm.nama, mkm.sks from matkulmaster as mkm inner join matkul as mk using(id_matkul)
  where mk.id_krklm=$idkr and mk.smstr=$smstr order by mkm.nama asc";
  $rm = mysqli_query($dbc, $qm);
  if(mysqli_num_rows($rm)>0){
    echo'<option value="-">-</option>';
	while(list($idm, $matkul, $sks) = mysqli_fetch_row($rm)){
      echo"<option value=\"$sks$idm\">$matkul</option>";
    }
  }else{
    echo'<option value="-">-</option>';
  }
  
  
}
?>