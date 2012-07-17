<?php
$page_title="Lihat Data";
include('../header.html');

session_start();
if(!isset($_SESSION['id']) or !isset($_GET['idTA'])){
  $url='index.php';
  header("Location: $url");
  exit();
}else{
  $id_ta = $_GET['idTA'];
  $_SESSION['id_ta'] = $_GET['idTA'];
}

require_once('../mysqli_connect.php');
$q="select tahun, ajaran from ta where id_TA=$id_ta limit 1";
$r=mysqli_query($dbc, $q); 
list($tahun, $ajaran)=mysqli_fetch_row($r);
$ajaran = strtoupper($ajaran);

?>
<a href="cp.php" class="back"><</a>&nbsp;&nbsp;
<a class="fback fprint" href="javascript:window.print()" title="Print">P</a>
<div id="vdata" class="base view">
  <div class="label inputD cview">JADWAL TAHUN AJARAN <?php echo"$tahun / SEMESTER $ajaran" ?></div>
  <div class="form">
  
  
  <div id="formwrapper">
    <div id="pointview">Dosen</div>
	<div class="form">
	  <?php
		$q = "select nip, nama, username, telp from dosen where id_TA=$id_ta order by nama asc";
		$r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r) > 0){
		  echo<<<EOT
		  <table cellspacing="0"><ol><tr><td>NIP</td><td>Nama</td><td>Username</td><td>No. Telepon</td></tr>
EOT;
		  $bg = 'white';
		  while(list($nip, $nama, $user, $telp)=mysqli_fetch_row($r)){
			
			$bg = ($bg=='#f7f7f7'?'white':'#f7f7f7');
			
			echo<<<EOT
			<tr>
			  <td class="vpad"><span class="jad">$nip</span></td>
			  <td class="vpad"><span class="jad">$nama</span></td>
			  <td class="vpad"><span class="jad">$user</span></td>
			  <td class="vpad"><span class="jad">$telp</span></td>
			</tr>
EOT;
		  }
		  echo"</ol></table>";
		}else{
		  echo"<p class=\"center\"><span class=\"jad\"><i>Belum ada data...</i></span></p>";
		}
	  ?>
	</div>
  </div>
  
  <div id="formwrapper">
    <div id="pointview" class="break">Penugasan Dosen</div>
	<div class="form pdos">
	  <?php
		$q = "select id_pd, nod, id_matkul, id_prodi, id_tipe, id_kls, smstr from pdosen where id_TA=$id_ta order by nod";
		$r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r) > 0){
		
		  $bg = 'white';
		  
		  echo<<<EOT
		  <table cellpadding="0" cellspacing="0" border="0"><tr><td>Dosen</td><td>Mata Kuliah</td><td>Program Studi</td><td>Perkuliahan</td><td>Kelas</td><td>Semester</td></tr>
EOT;
		  while(list($idpd, $nod, $idm, $idp, $idt, $idk, $smstr)=mysqli_fetch_row($r)){
		    $qd = "select nama from dosen where nod=$nod";
			$rd = mysqli_query($dbc, $qd);
			list($dosen)=mysqli_fetch_row($rd);
			$qm = "select nama, sks from matkul where id_matkul=$idm";
			$rm = mysqli_query($dbc, $qm);
			list($matkul, $sks)=mysqli_fetch_row($rm);
			$qp = "select nama from prodi where id_prodi=$idp";
			$rp = mysqli_query($dbc, $qp);
			list($prodi)=mysqli_fetch_row($rp);
			$qt = "select nama from tipekuliah where id_tipe=$idt";
			$rt = mysqli_query($dbc, $qt);
			list($tipe)=mysqli_fetch_row($rt);
			$qk = "select nama from kelas where id_kls=$idk";
			$rk = mysqli_query($dbc, $qk);
			list($kelas)=mysqli_fetch_row($rk);
			$bg = ($bg == '#f7f7f7' ? 'white' : '#f7f7f7');
			
			echo<<<EOT
			<tr>
			  <td class="vpad"><span class="jad">$dosen</span></td><td><span class="jad">$matkul</span></td>
			  <td class="vpad"><span class="jad">$prodi</span></td><td><span class="jad">$tipe</span></td>
			  <td class="vpad"><span class="jad">$kelas</span></td><td><span class="jad">$smstr</span></td>
			</tr>
EOT;
		  }
		  echo"</ol></table>";
		}else{
		  echo"<p class=\"center\"><span class=\"jad\"><i>Belum ada data...</i></span></p>";
		}
	  ?>
	</div>
  </div>
  
  <div id="formwrapper">
    <div id="pointview" class="break">Program Studi</div>
	<div class="form">
	  <?php
		$q = "select nama from prodi where id_ta=$id_ta order by nama asc";
		$r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r) > 0){
		  echo<<<EOT
		  <table cellspacing="0">
EOT;
		  
		  while(list($nama)=mysqli_fetch_row($r)){
		    echo<<<EOT
			<tr>
			  <td class="vpad"><span class="jad">$nama</span></td>
			</tr>
EOT;
		  }
		  echo"</ol></table>";
		}else{
		  echo"<p class=\"center\"><span class=\"jad\"><i>Belum ada data...</i></span></p>";
		}
	  ?>
	</div>
  </div>
  
  <div id="formwrapper">
    <div id="pointview" class="break">Mata Kuliah</div>
	<div class="form">
	  <?php
		$q = "select nama, sks from matkul where id_ta=$id_ta order by nama asc";
		$r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r) > 0){
		  echo<<<EOT
		  <table cellspacing="0"><tr><td>Mata Kuliah</td><td>SKS</td></tr>
EOT;
		  
		  while(list($nama, $sks)=mysqli_fetch_row($r)){
		    echo<<<EOT
			<tr>
			  <td class="vpad"><span class="jad">$nama</span></td>
			  <td class="vpad"><span class="jad">$sks SKS</span></td>
			</tr>
EOT;
		  }
		  echo"</table>";
		}else{
		  echo"<p class=\"center\"><span class=\"jad\"><i>Belum ada data...</i></span></p>";
		}
	  ?>
	</div>
  </div>
  
  <div id="formwrapper">
    <div id="pointview" class="break">Kelas</div>
	<div class="form">
	  <?php
		$q = "select nama, mhs from kelas where id_ta=$id_ta order by nama asc";
		$r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r) > 0){
		  echo<<<EOT
		  <table cellspacing="0"><tr><td>Kelas</td><td>Mahasiswa</td></tr>
EOT;
		  
		  while(list($kelas, $mhs)=mysqli_fetch_row($r)){
		    echo<<<EOT
			<tr>
			  <td class="vpad"><span class="jad">$kelas</span></td>
			  <td class="vpad"><span class="jad">$mhs</span></td>
			</tr>
EOT;
		  }
		  echo"</table>";
		}else{
		  echo"<p class=\"center\"><span class=\"jad\"><i>Belum ada data...</i></span></p>";
		}
	  ?>
	</div>
  </div>
  
  <div id="formwrapper">
    <div id="pointview" class="break">Lokal</div>
	<div class="form">
	  <?php
		$q = "select nama, muat from lokal where id_ta=$id_ta order by nama asc";
		$r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r) > 0){
		  echo<<<EOT
		  <table cellspacing="0"><tr><td>Lokal</td><td>Kapasitas</td></tr>
EOT;
		  
		  while(list($lokal, $muat)=mysqli_fetch_row($r)){
		    echo<<<EOT
			<tr>
			  <td class="vpad"><span class="jad">$lokal</span></td>
			  <td class="vpad"><span class="jad">$muat</span></td>
			</tr>
EOT;
		  }
		  echo"</table>";
		}else{
		  echo"<p class=\"center\"><span class=\"jad\"><i>Belum ada data...</i></span></p>";
		}
	  ?>
	</div>
  </div>
  
  <div id="formwrapper">
    <div id="pointview" class="break">Tipe Perkuliahan</div>
	<div class="form">
	   <?php
		$q = "select nama from tipekuliah where id_ta=$id_ta order by nama asc";
		$r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r) > 0){
		  echo<<<EOT
		  <table cellspacing="0">
EOT;
		  
		  while(list($nama)=mysqli_fetch_row($r)){
		    echo<<<EOT
			<tr>
			  <td class="vpad"><span class="jad">$nama</span></td>
			</tr>
EOT;
		  }
		  echo"</table>";
		}else{
		  echo"<p class=\"center\"><span class=\"jad\"><i>Belum ada data...</i></span></p>";
		}
	  ?>
	</div>
  </div>
  
  <div id="formwrapper">
    <div id="pointview" class="break">Hari Aktif Kuliah</div>
	<div class="form">
	  <?php
		$q = "select nama from hariaktif where id_ta=$id_ta";
		$r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r) > 0){
		  echo<<<EOT
		  <table cellspacing="0">
		  <tr>
EOT;
		  
		  while(list($nama)=mysqli_fetch_row($r)){
		    echo<<<EOT
			  <td class="vpad"><span class="jad">$nama</span></td>
EOT;
		  }
		  echo"</tr></table>";
		}else{
		  echo"<p class=\"center\"><span class=\"jad\"><i>Belum ada data...</i></span></p>";
		}
	  ?>
	</div>
  </div>
  
  <div id="formwrapper">
    <div id="pointview" class="break">Jam Perkuliahan</div>
	<div class="form">
	  <?php
		$q = "select jam_kul, mulai_jam, mulai_menit, selesai_jam, selesai_menit from tabeljam where id_ta=$id_ta order by jam_kul asc";
		$r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r) > 0){
		  echo<<<EOT
		  <table cellspacing="0"><tr><td>Jam Kuliah ke-</td> <td></td><td>Mulai (Jam)</td><td></td><td>Mulai (Menit)</td><td> </td><td>Selesai (Jam)</td><td></td><td>Selesai (Menit)</td></tr>
EOT;
		  
		  while(list($jk, $mj, $mm, $sj, $sm)=mysqli_fetch_row($r)){
		  
		    if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
			if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
			if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
			if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
			
		    echo<<<EOT
			<tr>
			  <td class="vpad"><span class="jad">$jk</span></td>
			  <td></td>
			  <td><span class="jad">$mjl</span></td><td>:</td><td><span class="jad">$mml</span></td>
			  <td></td>
			  <td><span class="jad">$sjl</span></td><td>:</td><td><span class="jad">$sml</span></td>
			</tr>
EOT;
		  }
		  echo"</table>";
		}else{
		  echo"<p class=\"center\"><span class=\"jad\"><i>Belum ada data...</i></span></p>";
		}
	  ?>
	</div>
  </div>
  
  <div id="formwrapper">
    <div id="pointview" class="break">Operasional Perkuliahan</div>
	<div class="form">
	  <?php
		$q = "select id_tipe, masuk_jam, masuk_menit, pulang_jam, pulang_menit from operasional where id_TA=$id_ta order by id_op asc";
	    $r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r) > 0){
		  echo<<<EOT
		  <table cellspacing="0">
		  <tr><td>Tipe Kuliah</td><td></td><td>Jam Masuk (Jam)</td><td></td><td>Jam Masuk (Menit)</td><td> </td><td>Jam Pulang (Jam)</td><td></td><td>Jam Pulang (Menit)</td></tr>
EOT;
		  
		  while(list($idt, $mj, $mm, $pj, $pm)=mysqli_fetch_row($r)){
		  
		    if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
		    if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
		    if(strlen($pj)==1){ $pjl = 0 . $pj; }else{ $pjl = $pj; }
		    if(strlen($pm)==1){ $pml = $pm . 0; }else{ $pml = $pm; }
			
			$qt = "select nama from tipekuliah where id_tipe=$idt";
		    $rt = mysqli_query($dbc, $qt);
		    list($tipe) = mysqli_fetch_row($rt);
			
		    echo<<<EOT
			<tr>
			  <td class="vpad"><span class="jad">$tipe</span></td><td></td>
			  <td><span class="jad">$mjl</span></td><td>:</td><td><span class="jad">$mml</span></td><td> </td>
			  <td><span class="jad">$pjl</span></td><td>:</td><td><span class="jad">$pml</span></td>
			</tr>
EOT;
		  }
		  echo"</table>";
		}else{
		  echo"<p class=\"center\"><span class=\"jad\"><i>Belum ada data...</i></span></p>";
		}
	  ?>
	</div>
  </div>
  
  
  </div>
  
</div>