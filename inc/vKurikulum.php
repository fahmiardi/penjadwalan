<?php
session_start();
if((!isset($_SESSION['adm_fak']) or !isset($_GET['kr'])) and (!isset($_SESSION['adm_univ']) or !isset($_GET['kr'])) and (!isset($_SESSION['adm_super']) or !isset($_GET['kr']))){
  header('Location:../index.php');
}else{
  
  require_once('../mysqli_connect.php');
  $idkr = $_GET['kr'];
  $_SESSION['id_krklm'] = $_GET['kr'];
  
  $rkr = mysqli_query($dbc, "select tahun from kurikulum where id_krklm=$idkr");
  list($tahun) = mysqli_fetch_row($rkr);
  $q = "select mk.smstr, mkm.nama, mkm.sks, mkm.kd_matkul from matkul as mk inner join matkulmaster as mkm using(id_matkul) where mk.id_krklm=$idkr order by mk.smstr, mkm.nama, mkm.sks asc";
  $r = mysqli_query($dbc, $q);
  echo'<div class="form" style="position:absolute; top:-30px; right:-600px; box-shadow:0 0 5px #c9c9c9; min-width:510px;" id="vKuri">';
  if(mysqli_num_rows($r)>0){
	echo<<<EOT
	
	<p class="center"><span class="jad">Kurikulum $tahun</span></p><br/>
	<table id="tabAppend">
	  <tr class="exc"><td class="xfont">Smstr</td><td class="xfont">Matakuliah</td><td class="xfont">SKS</td><td class="xfont">Kode</td></tr>
EOT;
	  while(list($smstr, $matkul, $sks, $kdm)=mysqli_fetch_row($r)){
	    echo"
		<tr>
		  <td class=\"vpad\"><span class=\"jad\">$smstr</span></td>
		  <td><span class=\"jad\">$matkul</span></td>
		  <td><span class=\"jad\">$sks</span></td>
		  <td><span class=\"jad\">$kdm</span></td>
		</tr>";
	  }
	  echo'</table>';
  }else{
    echo'<p class="center"><span class="jad">Kurikulum '.$tahun.'</span><br/><br/><span style="font-size:12px;">Belum ada data Matakuliah</span></p>'; 
  }
  echo'</div>';
	 
}
?>