<?php
session_start();
if((!isset($_SESSION['adm_prodi']) or !isset($_GET['kr'])) and (!isset($_SESSION['adm_fak']) or !isset($_GET['kr']))){
  header('Location:../index.php');
}else{
  
  if(isset($_SESSION['adm_prodi'])){
  
  require_once('../mysqli_connect.php');
  $idkr = $_GET['kr'];
  $_SESSION['id_krklm'] = $_GET['kr'];
  
  echo'<div class="form" style="position:absolute; top:-30px; right:-600px; box-shadow:0 0 5px #c9c9c9; min-width:510px;" id="vKuri">';
  echo'<div style="position:relative; display:table;">
    <div id="refMatkul" class="jpd" style="font-size:12px; cursor:pointer;  display:inline-block; text-shadow:none; border-radius:15px; padding:3px 5px; padding-top:4px;" title="refresh"><img src="../img/refresh.png" height="16px" width="16px"/></div>
  </div>
  <div id="ocRMatkul">'; 
  
  $rkr = mysqli_query($dbc, "select tahun from kurikulum where id_krklm=$idkr");
  list($tahun) = mysqli_fetch_row($rkr);
  $q = "select mk.id_matkul, mk.id_krklm, mk.smstr, mkm.kd_matkul, mkm.nama, mkm.sks from matkul as mk inner join matkulmaster as mkm using(id_matkul) where id_krklm=$idkr order by mk.smstr, mkm.nama, mkm.sks asc";
  $r = mysqli_query($dbc, $q);
  
  if(mysqli_num_rows($r)>0){
	echo<<<EOT
	
	<p class="center"><span class="jad">Kurikulum $tahun</span></p><br/>
	<p class="center" style="font-size:12px;">Ubah Data Matakuliah:</p>
	<p class="center" style="font-size:12px;" id="pAppend"></p>
	<table id="tabAppend">
	  <tr class="exc"><td></td><td class="xfont">Semester</td><td class="xfont">Matakuliah</td><td class="xfont">SKS</td><td class="xfont">Kode</td></tr>
EOT;
	  $id = 1; $tr = 1;
	  while(list($idm, $idkr, $smstr, $kdm, $nama, $sks)=mysqli_fetch_row($r)){
	    echo<<<EOT
		<tr>
		  <td class="vpad"><a class="x delmatkul" href="hapusmatkul.php?idm=$smstr-$idkr-$idm" title="Hapus">-</a></td>
		  <td><span class="jad">$smstr</span></td>
		  <td><span class="jad">$nama</span></td>
		  <td><span class="jad">$sks</span></td>
		  <td><span class="jad">$kdm</span></td>
		</tr>			
EOT;
	  }
	  echo'</table></div>';
  }else{
    echo'<p class="center"><span class="jad">Kurikulum '.$tahun.'</span><br/><br/><span style="font-size:12px;">Belum ada data Matakuliah</span></p></div>'; 
  }
  
  echo<<<EOT
	    <div class="hr"><hr/></div>
		  
	    <p class="center" style="font-size:12px;">Tambah Data Matakuliah:</p>		  
		<div id="checkmatkul">
          <form action="cekmatkul.php" method="post" id="cekmatkul">
		  <table>
            <tr class="exc"><td class="xfont">Semester</td><td class="xfont">Matakuliah</td><td class="xfont">SKS</td><td class="xfont">Kode</td></tr>
		    <tr class="exc">
		      <td>
			    <select name="smstr">
				<option value="-" selected="selected">-</option>
EOT;
			    for($i=1;$i<=8;$i++){
				  echo"<option value=\"$i\">$i</option>";
				}
				echo<<<EOT
				</select>
			</td>
			<td><select name="idm" class="trigKRidm">
			    <option value="-" selected="selected">-</option>
EOT;
			  $q = "select id_matkul, nama from matkulmaster where id_prodi={$_SESSION['adm_prodi']} order by nama asc";
			  $r = mysqli_query($dbc, $q);
			  while(list($idm, $nama)=mysqli_fetch_row($r)){
			    echo"<option value=\"$idm\">$nama</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td><span class="jad ocKRsks">-</span></td>
			  <td><span class="jad ocKRkd">-</span></td>
		    </tr>
	      </table>
          <p class="center"><input type="submit" name="submit" value="+ Tambah"/></p>
          <input type="hidden" name="submitted" value="TRUE"/>
          </form>
	    </div>
	</div>
EOT;
  
  }elseif(isset($_SESSION['adm_fak'])){
  
  require_once('../mysqli_connect.php');
  $idkr = $_GET['kr'];
  $_SESSION['id_krklm'] = $_GET['kr'];
  
  echo'<div class="form" style="position:absolute; top:-30px; right:-600px; box-shadow:0 0 5px #c9c9c9; min-width:510px;" id="vKuri">';
  echo'<div style="position:relative; display:table;">
    <div id="refMatkul" class="jpd" style="font-size:12px; cursor:pointer;  display:inline-block; text-shadow:none; border-radius:15px; padding:3px 5px; padding-top:4px;" title="refresh"><img src="../img/refresh.png" height="16px" width="16px"/></div>
  </div>
  <div id="ocRMatkul">'; 
  
  $rkr = mysqli_query($dbc, "select tahun from kurikulum where id_krklm=$idkr");
  list($tahun) = mysqli_fetch_row($rkr);
  $q = "select mk.id_matkul, mk.id_krklm, mk.smstr, mkm.kd_matkul, mkm.nama, mkm.sks from matkul as mk inner join matkulmaster as mkm using(id_matkul) where id_krklm=$idkr order by mk.smstr, mkm.nama, mkm.sks asc";
  $r = mysqli_query($dbc, $q);
  
  if(mysqli_num_rows($r)>0){
	echo<<<EOT
	
	<p class="center"><span class="jad">Kurikulum $tahun</span></p><br/>
	<p class="center" style="font-size:12px;">Ubah Data Matakuliah:</p>
	<p class="center" style="font-size:12px;" id="pAppend"></p>
	<table id="tabAppend">
	  <tr class="exc"><td></td><td class="xfont">Semester</td><td class="xfont">Matakuliah</td><td class="xfont">SKS</td><td class="xfont">Kode</td></tr>
EOT;
	  $id = 1; $tr = 1;
	  while(list($idm, $idkr, $smstr, $kdm, $nama, $sks)=mysqli_fetch_row($r)){
	    echo<<<EOT
		<tr>
		  <td class="vpad"><a class="x delmatkul" href="hapusmatkul.php?idm=$smstr-$idkr-$idm" title="Hapus">-</a></td>
		  <td><span class="jad">$smstr</span></td>
		  <td><span class="jad">$nama</span></td>
		  <td><span class="jad">$sks</span></td>
		  <td><span class="jad">$kdm</span></td>
		</tr>			
EOT;
	  }
	  echo'</table></div>';
  }else{
    echo'<p class="center"><span class="jad">Kurikulum '.$tahun.'</span><br/><br/><span style="font-size:12px;">Belum ada data Matakuliah</span></p></div>'; 
  }
  
  echo<<<EOT
	    <div class="hr"><hr/></div>
		  
	    <p class="center" style="font-size:12px;">Tambah Data Matakuliah:</p>		  
		<div id="checkmatkul">
          <form action="cekmatkul.php" method="post" id="cekmatkul">
		  <table>
            <tr class="exc"><td class="xfont">Semester</td><td class="xfont">Matakuliah</td><td class="xfont">SKS</td><td class="xfont">Kode</td></tr>
		    <tr class="exc">
		      <td>
			    <select name="smstr">
				<option value="-" selected="selected">-</option>
EOT;
			    for($i=1;$i<=8;$i++){
				  echo"<option value=\"$i\">$i</option>";
				}
				echo<<<EOT
				</select>
			</td>
			<td><select name="idm" class="trigKRidm">
			    <option value="-" selected="selected">-</option>
EOT;
			  $q = "select id_matkul, nama from matkulmaster where id_prodi={$_SESSION['idpx']} order by nama asc";
			  $r = mysqli_query($dbc, $q);
			  while(list($idm, $nama)=mysqli_fetch_row($r)){
			    echo"<option value=\"$idm\">$nama</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td><span class="jad ocKRsks">-</span></td>
			  <td><span class="jad ocKRkd">-</span></td>
		    </tr>
	      </table>
          <p class="center"><input type="submit" name="submit" value="+ Tambah"/></p>
          <input type="hidden" name="submitted" value="TRUE"/>
          </form>
	    </div>
	</div>
EOT;
  
  }
  
}
?>