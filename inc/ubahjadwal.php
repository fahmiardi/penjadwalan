<?php
$page_title="Data Jadwal";
include('../header.html');

session_start();
if((!isset($_SESSION['adm_fak']) or !isset($_GET['idTA'])) or (!isset($_SESSION['adm_prodi']) or !isset($_GET['idTA']))){
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

echo<<<EOT
  <span class="right"><a href="logout.php" class="log">Logout</a></span>
EOT;
?>

<a href="cp.php" class="back"><</a>&nbsp;&nbsp;
<span class="heading"><i>Jadwal Tahun Akademik <?php echo"$tahun - $ajaran" ?></i></span>

<div id="idata" class="base inputB">
  <div class="label inputD">DATA JADWAL</div>
  <div class="form">
    <p class="center">
	  ubah atau tambahkan data-data yang diperlukan secara cermat dan tepat!
	</p>
	
	<div id="formwrapper" class="dosenWrapper">	
	  <div id="pointer" class="indata cdosen">Dosen</div>
	  
      <div class="form indosen">
	  
	      <p class="center">ubah data-data dosen:</p>
		  
		  <?php
		    $q = "select nod, nip, nama, username, telp from dosen where id_TA=$id_ta order by nama asc";
			$r = mysqli_query($dbc, $q);
			if(mysqli_num_rows($r) > 0){
			  echo<<<EOT
			  <table><tr><td></td><td>NIP</td><td>Nama</td><td>Username</td><td>Password</td><td>No. Telepon</td><td></td></tr>
EOT;
			  while(list($nod, $nip, $nama, $user, $telp)=mysqli_fetch_row($r)){
			    echo<<<EOT
				<tr>
				  <form action="ubahdosen.php" method="post" id="ubahdosen">
				  <td><a class="x" href="hapusdosen.php?nod=$nod" title="Hapus">-</a></td>
				  <td><input type="text" name="nip" size="17" value="$nip"></td>
				  <td><input type="text" name="nama" size="30" value="$nama"></td>
				  <td><input type="text" name="username" size="17" value="$user"></td>
				  <td><input type="password" name="password" size="17"></td>
				  <td><input type="text" name="telp" size="17" value="$telp"></td>
				  <td><input type="submit" name="submit" value="+" title="Update" id="subnewdata" class="update"/></td>
				  <input type="hidden" name="submitted" value="true"/>
				  <input type="hidden" name="nod" value="$nod"/>
				  </form>
				</tr>
EOT;
			  }
			  echo"</table>";
			}else{
			  echo"<p class=\"center\"><span class=\"jad\"><i>Belum ada data...</i></span></p>";
			}
		  ?>
		
		<div class="hr"><hr/></div>
		
	    <form action="tambahdosen.php" method="post" id="tambahdosen">
		<div id="delegate">
		  
		  <p class="center">tambahkan data-data dosen:</p>

		  <div><table>
		  <tr><td>NIP</td><td>Nama Lengkap</td><td>Username</td><td>Password</td><td>No. Telepon</td></tr>
		  <tr>
		    <td><input type="text" name="nip[]" size="17" id="innip"/></td>
		    <td><input type="text" name="nama[]" size="17" id="innama"/></td>
		    <td><input type="text" name="username[]" size="17" id="username"/></td>
		    <td><input type="password" name="password[]" size="17" id="nama_dosen"/></td>
		    <td><input type="text" name="telp[]" size="17" id="telp"/></td>
		  </tr></table>
		  <div class="add">+ Tambah Form</div>
		  </div>
		  
		  <p class="center"><br/><input type="submit" name="submit" value="Submit" id="subnewdata"/></p>
	      <input type="hidden" name="submitted" value="true"/>
	    </form>
		</div>
		
      </div>
	</div>
	
	<?php
  $q = "select d.nama, m.nama from dosen as d inner join matkul as m using(id_TA) where id_TA=$id_ta";
  $r = mysqli_query($dbc, $q);
  if(mysqli_num_rows($r)>0){
    list($nd, $nm) = mysqli_fetch_row($r);
  }else{
    $nd = false;
	$nm = false;
  }
  $qk = "select k.nama, p.nama from kelas as k inner join prodi as p using(id_TA) where id_TA=$id_ta";
  $rk = mysqli_query($dbc, $qk);
  if(mysqli_num_rows($rk)>0){
    list($nk, $np) = mysqli_fetch_row($rk);
  }else{
    $nk = false;
	$np = false;
  }
  $qt = "select nama from tipekuliah where id_TA=$id_ta";
  $rt = mysqli_query($dbc, $qt);
  if(mysqli_num_rows($rt)>0){
    list($nt) = mysqli_fetch_row($rt);
  }else{
    $nk = false;
  }  
  if($nd and $nm and $nk and $np and $nt){
    echo<<<EOT
	<div id="formwrapper" class="pdosenWrapper">	
	  <div id="pointer" class="indata cpdosen">Penugasan Dosen</div>
	  
      <div class="form inpdosen stylect">
	  
	      <p class="center">ubah data-data penugasan dosen:</p>
EOT;

		  $qu = "select p.id_pd, p.nod, p.id_matkul, p.id_prodi, p.id_tipe, p.id_kls, p.smstr from pdosen as p 
		  inner join dosen as d using(nod) order by d.nama asc";
		  $ru = mysqli_query($dbc, $qu);
		  if(mysqli_num_rows($ru)>0){
		    //$bg = '#f7f7f7';
		    echo'<table>
			<tr><td></td><td>Dosen</td><td>Mata Kuliah</td><td>Program Studi</td><td>Perkuliahan</td><td>Kelas</td><td>Semester</td><td></td></tr>';
		    while(list($idpd, $nod, $idm, $idp, $idt, $idk, $smstr)=mysqli_fetch_row($ru)){
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
			  echo"<td><tr><td><a class=\"x\" href=\"hapuspdosen.php?idpd=$idpd\" title=\"Hapus\">-</a></td>
			  <td><span class=\"jad\">$dosen</span></td><td><span class=\"jad\">$matkul</span></td>
			  <td><span class=\"jad\">$prodi</span></td><td><span class=\"jad\">$tipe</span></td>
			  <td><span class=\"jad\">$kelas</span></td><td><span class=\"jad\">$smstr</span></td>
			  <td></td></tr>";
			  
			  echo<<<EOT
			  <form action="ubahpdosen.php" method="post" id="ubahpdosen">
			  <tr><td></td>
			    <td><select name="nod">
EOT;
			    $qgd = "select nod, nama from dosen where id_TA=$id_ta order by nama asc";
				$rgd = mysqli_query($dbc, $qgd);
				while(list($gnod, $gdosen)=mysqli_fetch_row($rgd)){
				  echo<<<EOT
				  <option value="$gnod">$gdosen</option>
EOT;
				}
				echo<<<EOT
				</select></td>
				<td><select name="idm">
EOT;
			    $qgm = "select id_matkul, nama, sks from matkul where id_TA=$id_ta order by nama asc";
				$rgm = mysqli_query($dbc, $qgm);
				while(list($gidm, $gmatkul, $sks)=mysqli_fetch_row($rgm)){
				  echo<<<EOT
				  <option value="$gidm">$gmatkul / $sks SKS</option>
EOT;
				}
				echo<<<EOT
				</select></td>
				<td><select name="idp">
EOT;
			    $qgp = "select id_prodi, nama from prodi where id_TA=$id_ta order by id_prodi asc";
				$rgp = mysqli_query($dbc, $qgp);
				while(list($gidp, $gprodi)=mysqli_fetch_row($rgp)){
				  echo<<<EOT
				  <option value="$gidp">$gprodi</option>
EOT;
				}
				echo<<<EOT
				</select></td>
				<td><select name="idt">
EOT;
			    $qgt = "select id_tipe, nama from tipekuliah where id_TA=$id_ta order by id_tipe asc";
				$rgt = mysqli_query($dbc, $qgt);
				while(list($gidt, $gtipe)=mysqli_fetch_row($rgt)){
				  echo<<<EOT
				  <option value="$gidt">$gtipe</option>
EOT;
				}
				echo<<<EOT
				</select></td>
				<td><select name="idk">
EOT;
			    $qgk = "select id_kls, nama from kelas where id_TA=$id_ta order by nama asc";
				$rgk = mysqli_query($dbc, $qgk);
				while(list($gidk, $gkelas)=mysqli_fetch_row($rgk)){
				  echo<<<EOT
				  <option value="$gidk">$gkelas</option>
EOT;
				}
				echo<<<EOT
				</select></td>
				<td><select name="smstr">
EOT;
				for($i=1;$i<=8;$i++){
				  echo"<option value=\"$i\">$i</option>";
				}
				echo<<<EOT
				</select></td>
				<td><input type="submit" name="submit" value="+" title="Update" id="subnewdata" class="update" class="update"/></td>
				<input type="hidden" name="submitted" value="true"/>
				<input type="hidden" name="idpd" value="$idpd"/>
			  </tr></td>
			  </form>
EOT;
			}
			echo'</table>';
		  }else{
		    echo"<p class=\"center\"><span class=\"jad\"><i>Belum ada data...</i></span></p>";
		  }
		  
		echo<<<EOT
		<div class="hr"><hr/></div>
        
		<div id="delegate7">
		<form action="tambahpdosen.php" method="post" id="tambahpdosen">
		  <p class="center">tambahkan data-data penugasan dosen:</p>
		  <div><table>
		  <tr><td>Dosen</td><td>Mata Kuliah</td><td>Program Studi</td><td>Perkuliahan</td><td>Kelas</td><td>Semester</td>
		  <tr>
		    <td><select name="nod[]">
EOT;
		$qd = "select nod, nama from dosen where id_TA=$id_ta order by nama asc";
		$rd = mysqli_query($dbc, $qd);
		while(list($nod, $dosen)=mysqli_fetch_row($rd)){
		  echo"<option value=\"$nod\">$dosen</option>";
		}
		  echo<<<EOT
		  </select></td>
		  <td><select name="idm[]">
EOT;
		$q2 = "select id_matkul, nama, sks from matkul where id_TA=$id_ta order by nama asc";
		$r2 = mysqli_query($dbc, $q2);
		while(list($idm, $nama, $sks)=mysqli_fetch_row($r2)){ 
		  echo"<option value=\"$idm\">$nama / $sks SKS</option>";
		}
		  echo<<<EOT
		  </select></td>
		  <td><select name="idp[]">
EOT;
		$qp = "select id_prodi, nama from prodi where id_TA=$id_ta order by nama asc";
		$rp = mysqli_query($dbc, $qp);
		while(list($idp, $nama)=mysqli_fetch_row($rp)){ 
		  echo"<option value=\"$idp\">$nama</option>";
		}
		  echo<<<EOT
		  </select></td>
		  <td><select name="idt[]">
EOT;
		$qt = "select id_tipe, nama from tipekuliah where id_TA=$id_ta order by id_tipe asc";
		$rt = mysqli_query($dbc, $qt);
		while(list($idt, $nama)=mysqli_fetch_row($rt)){ 
		  echo"<option value=\"$idt\">$nama</option>";
		}
		  echo<<<EOT
		  </select></td>
		  <td><select name="idk[]">
EOT;
		$q3 = "select id_kls, nama from kelas where id_TA=$id_ta order by nama asc";
		$r3 = mysqli_query($dbc, $q3);
		while(list($id_kls, $kelas)=mysqli_fetch_row($r3)){
		  echo"<option value=\"$id_kls\">$kelas</option>";
		}
		  echo<<<EOT
		  </select></td>
		  <td><select name="smstr[]">
EOT;
		  $smstr = range(1, 8);
		  foreach($smstr as $k => $v){
		    echo"<option value=\"$v\">$v</option>";
		  }
		  echo<<<EOT
		  </select></td>
		  </tr></table>
		  <div class="add7">+ Tambah Form</div>
		  </div>
		  
		  <p class="center"><br/><input type="submit" name="submit" value="Submit" id="subnewdata"/></p>
	      <input type="hidden" name="submitted" value="true"/>
		</form>
		</div>
		
      </div>
	  
	</div>
	
EOT;
  }
  ?>
	
	<div id="formwrapper" class="prodiWrapper">	
	  <div id="pointer" class="indata cprodi">Program Studi</div>
	  
      <div class="form inprodi">
	  
	      <p class="center">ubah nama-nama program studi:</p>
		  
		  <?php 
		    $q = "select id_prodi, nama from prodi where id_ta=$id_ta order by nama asc";
			$r = mysqli_query($dbc, $q);
			if(mysqli_num_rows($r) > 0){
			  echo<<<EOT
			  <table>
EOT;
			  while(list($idp, $nama)=mysqli_fetch_row($r)){
			    echo<<<EOT
				<tr>
				  <form action="ubahprodi.php" method="post" id="ubahprodi">
				  <td><a class="x" href="hapusprodi.php?idp=$idp" title="Hapus">-</a></td>
				  <td><input type="text" name="nama" value="$nama"></td>
				  <td><input type="submit" name="submit" value="+" title="Update" id="subnewdata" class="update"/></td>
				  <input type="hidden" name="submitted" value="true"/>
				  <input type="hidden" name="idp" value="$idp"/>
				  </form>
				</tr>
EOT;
			  }
			  echo'</table>';
			}else{
			  echo"<p class=\"center\"><span class=\"jad\"><i>Belum ada data...</i></span></p>";
			}
		 ?>
		
		<div class="hr"><hr/></div>
	  
		<div id="delegate2">
		<form action="tambahprodi.php" method="post" id="tambahprodi">
		  <p class="center">tambahkan nama-nama program studi:</p>
		  <div>
		  <p class="center"><input type="text" name="prodi[]" id="prodi"/></p>
		  <div class="add2">+ Tambah Form</div>
		  </div>
		  
		  <p class="center"><br/><input type="submit" name="submit" value="Submit" id="subnewdata"/></p>
	      <input type="hidden" name="submitted" value="true"/>
		</form>
		</div>
	    
      </div>
	</div>
	
	
	<div id="formwrapper" class="matkulWrapper">	
	  <div id="pointer" class="indata cmatkul">Mata Kuliah</div>
	  
      <div class="form inmatkul">
	  
	      <p class="center">ubah data-data mata kuliah:</p>
		  
		  <?php
		    $q = "select id_matkul, nama, sks from matkul where id_ta=$id_ta order by nama asc";
			$r = mysqli_query($dbc, $q);
			if(mysqli_num_rows($r) > 0){
			  echo<<<EOT
			  <table><tr><td></td><td>Mata Kuliah</td><td>SKS</td></tr><td></td>
EOT;
			  while(list($idm, $nama, $sks)=mysqli_fetch_row($r)){
			    echo<<<EOT
				<tr>
				  <form action="ubahmatkul.php" method="post" id="ubahmatkul">
				  <td><a class="x" href="hapusmatkul.php?idm=$idm" title="Hapus">-</a></td>
				  <td><input type="text" name="nama" value="$nama"></td>
				  <td>($sks) <select name="sks">
EOT;
				for($i=1;$i<7;$i++){
				echo"<option value=\"$i\">$i</opton>";
				}
				echo<<<EOT
				  </select></td>
				  <td><input type="submit" name="submit" value="+" title="Update" id="subnewdata" class="update"/></td>
				  <input type="hidden" name="submitted" value="true"/>
				  <input type="hidden" name="idm" value="$idm"/>
				  </form>
				</tr>
EOT;
			  }
			  echo'</table>';
			}else{
			  echo"<p class=\"center\"><span class=\"jad\"><i>Belum ada data...</i></span></p>";
			}
		  ?>
		
		<div class="hr"><hr/></div>
        
		<div id="delegate6">
		<form action="tambahmatkul.php" method="post" id="tambahmatkul">
		  <p class="center">tambahkan data-data mata kuliah:</p>
		  <div><table>
		  <tr><td>Mata Kuliah</td><td>SKS</td>
		  <tr>
		    <td><input type="text" name="nama[]" id="matkul"/></td>
		    <td><select name="sks[]">
		<?php
		for($i=1;$i<7;$i++){
		  echo"<option value=\"$i\">$i</option>";

		} 
		?>
		  </select></td>
		  </tr></table>
		  <div class="add6">+ Tambah Form</div>
		  </div>
		  
		  <p class="center"><br/><input type="submit" name="submit" value="Submit" id="subnewdata"/></p>
	      <input type="hidden" name="submitted" value="true"/>
		</form>
		</div>
		
      </div>
	  
	</div>
	

	<div id="formwrapper" class="kelasWrapper">	
	  <div id="pointer" class="indata ckelas">Kelas</div>
	  
      <div class="form inkelas">
	  
	      <p class="center">ubah data-data kelas:</p>
		  
		  <?php
		    $q = "select id_kls, nama, mhs from kelas where id_TA=$id_ta order by nama asc";
			$r = mysqli_query($dbc, $q);
			if(mysqli_num_rows($r) > 0){
			  echo<<<EOT
			  <table><tr><td></td><td>Nama Kelas</td><td>Mahasiswa</td><td></td></tr>
EOT;
			  while(list($idk, $nama, $mhs)=mysqli_fetch_row($r)){
			    echo<<<EOT
				<tr>
				  <form action="ubahkelas.php" method="post" id="ubahkelas">
				  <td><a class="x" href="hapuskelas.php?idk=$idk" title="Hapus">-</a></td>
				  <td><input type="text" name="nama" value="$nama"></td>
				  <td><input type="text" name="mhs" size="2" value="$mhs"></td>
				  <td><input type="submit" name="submit" value="+" title="Update" id="subnewdata" class="update"/></td>
				  <input type="hidden" name="submitted" value="true"/>
				  <input type="hidden" name="idk" value="$idk"/>
				  </form>
				</tr>
EOT;
			  }
			  echo"</table>";
			}else{
			  echo"<p class=\"center\"><span class=\"jad\"><i>Belum ada data...</i></span></p>";
			}
		  ?>
		
		<div class="hr"><hr/></div>
        
		<div id="delegate9">
		<form action="tambahkelas.php" method="post" id="tambahkelas">
		  <p class="center">tambahkan data-data kelas:</p>
		  <div><table>
		  <tr><td>Nama Kelas</td><td>Mahasiswa</td>
		  <tr>
		    <td><input type="text" name="nama[]" id="matkul"/></td>
		    <td><input type="text" name="mhs[]" size="2" id="matkul"/></td>
		  </tr></table>
		  <div class="add9">+ Tambah Form</div>
		  </div>
		  
		  <p class="center"><br/><input type="submit" name="submit" value="Submit" id="subnewdata"/></p>
	      <input type="hidden" name="submitted" value="true"/>
		</form>
		</div>
		
      </div>
	  
	</div>
  
	
	<div id="formwrapper" class="lokalWrapper">	
	  <div id="pointer" class="indata clokal">Lokal</div>
	  
      <div class="form inlokal">
	  
	      <p class="center">ubah data-data lokal:</p>
		  
		  <?php
		    $q = "select id_lkl, nama, muat from lokal where id_TA=$id_ta order by nama asc";
			$r = mysqli_query($dbc, $q);
			if(mysqli_num_rows($r) > 0){
			  echo<<<EOT
			  <table><tr><td></td><td>Lokal</td><td>Kapasitas</td><td></td></tr>
EOT;
			  while(list($idl, $nama, $muat)=mysqli_fetch_row($r)){
			    echo<<<EOT
				<tr>
				  <form action="ubahlokal.php" method="post" id="ubahlokal">
				  <td><a class="x" href="hapuslokal.php?idl=$idl" title="Hapus">-</a></td>
				  <td><input type="text" name="nama" size="7" value="$nama"></td>
				  <td><input type="text" name="muat" size="2" value="$muat"></td>
				  <td><input type="submit" name="submit" value="+" title="Update" id="subnewdata" class="update"/></td>
				  <input type="hidden" name="submitted" value="true"/>
				  <input type="hidden" name="idl" value="$idl"/>
				  </form>
				</tr>
EOT;
			  }
			  echo"</table>";
			}else{
			  echo"<p class=\"center\"><span class=\"jad\"><i>Belum ada data...</i></span></p>";
			}
		  ?>
		
		<div class="hr"><hr/></div>
        
		<div id="delegate3">
		<form action="tambahlokal.php" method="post" id="tambahlokal">
		  <p class="center">tambahkan data-data lokal:</p>
		  <div><table>
		  <tr><td>Nama Lokal</td><td>Kapasitas</td>
		  <tr>
		    <td><input type="text" name="lokal[]" size="7" id="lokal"/></td>
		    <td><input type="text" name="muat[]" size="2" id="muat"/></td>
		  </tr></table>
		  <div class="add3">+ Tambah Form</div>
		  </div>
		  
		  <p class="center"><br/><input type="submit" name="submit" value="Submit" id="subnewdata"/></p>
	      <input type="hidden" name="submitted" value="true"/>
		</form>
		</div>
		
      </div>
	  
	</div>
	
	<div id="formwrapper" class="tipkulWrapper">	
	  <div id="pointer" class="indata ctipkul">Tipe Perkuliahan</div>
	  
      <div class="form intipkul">
	  
	      <p class="center">ubah tipe-tipe perkuliahan:</p>
		  
		  <?php 
		    $q = "select id_tipe, nama from tipekuliah where id_ta=$id_ta order by id_tipe asc";
			$r = mysqli_query($dbc, $q);
			if(mysqli_num_rows($r) > 0){
			  echo<<<EOT
			  <table>
EOT;
			  while(list($idt, $nama)=mysqli_fetch_row($r)){
			    echo<<<EOT
				<tr>
				  <form action="ubahtipkul.php" method="post" id="ubahtipkul">
				  <td><a class="x" href="hapustipkul.php?idt=$idt" title="Hapus">-</a></td>
				  <td><input type="text" name="nama" value="$nama"></td>
				  <td><input type="submit" name="submit" value="+" title="Update" id="subnewdata" class="update"/></td>
				  <input type="hidden" name="submitted" value="true"/>
				  <input type="hidden" name="idt" value="$idt"/>
				  </form>
				</tr>
EOT;
			  }
			  echo'</table>';
			}else{
			  echo"<p class=\"center\"><span class=\"jad\"><i>Belum ada data...</i></span></p>";
			}
		 ?>
		  		
		<div class="hr"><hr/></div>
	  
		<div id="delegate5">
		<form action="tambahtipkul.php" method="post" id="tambahtipkul">
		  <p class="center">tambahkan tipe-tipe perkuliahan:</p>
		  <div>
		  <p class="center"><input type="text" name="tipkul[]" id="prodi"/></p>
		  <div class="add5">+ Tambah Form</div>
		  </div>
		  
		  <p class="center"><br/><input type="submit" name="submit" value="Submit" id="subnewdata"/></p>
	      <input type="hidden" name="submitted" value="true"/>
		</form>
		</div>
	    
      </div>
	</div>
	
    <div id="formwrapper" class="dayWrapper">
	  <div id="pointer" class="indata cday">Hari Aktif Kuliah</div>
	  
      <div class="form inday">
	    <form action="tambahhari.php" method="post" id="tambahhari">
	    <p class="center">hapus atau tambah hari perkuliahan:</p>
	    <table><tr>
		
		<?php 
		    $q = "select id_hari, nama from hariaktif where id_ta=$id_ta order by id_hari asc";
			$r = mysqli_query($dbc, $q);
			if(mysqli_num_rows($r) > 0){
			  echo<<<EOT
			  <table><tr>
EOT;
			  while(list($idh, $nama)=mysqli_fetch_row($r)){
			    echo<<<EOT
				  <td><a class="x" href="hapushari.php?idh=$idh" title="Hapus">-</a></td>
				  <td>$nama</td>
EOT;
			  }
			  echo'</tr></table>';
			}else{
			  echo"<p class=\"center\"><span class=\"jad\"><i>Belum ada data...</i></span></p>";
			}
		 ?>
		 
		 <div class="hr"><hr/></div>
		 
	    <?php
	      $day = array('Senin'=>'Senin', 'Selasa'=>'Selasa', 'Rabu'=>'Rabu', 'Kamis'=>'Kamis', 'Jumat'=>'Jumat', 'Sabtu'=>'Sabtu', 'Minggu'=>'Minggu');
	      foreach($day as $k => $v){
	        echo<<<EOT
	   	    <td class="xfont"><input type="checkbox" name="hari[]" value="$k" />$v</td>
EOT;
	      }
	    ?>
	  </tr></table>
	  <p class="center"><br/><input type="submit" name="submit" value="Submit" id="subnewdata"/></p>
	  <input type="hidden" name="submitted" value="true"/>
	  </form>
      </div>
	  
	</div>
	
	<div id="formwrapper" class="jamWrapper">	
	  <div id="pointer" class="indata cjam">Jam Perkuliahan</div>
	  
      <div class="form injam">

		  <?php 
		    $q = "select id_jam, jam_kul, mulai_jam, mulai_menit, selesai_jam, selesai_menit from tabeljam where id_ta=$id_ta order by jam_kul asc";
			$r = mysqli_query($dbc, $q);
			if(mysqli_num_rows($r) > 0){
			  echo"<table>
			  <tr><td>Jam Kuliah ke-</td> <td></td><td>Mulai (Jam)</td><td></td><td>Mulai (Menit)</td><td> </td><td>Selesai (Jam)</td><td></td><td>Selesai (Menit)</td></tr>";
			  while(list($idj, $jk, $mj, $mm, $sj, $sm)=mysqli_fetch_row($r)){
			  
			  if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
			  if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
			  if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
			  if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
			  
			    echo<<<EOT
				<tr><td class="xpad"><span class="jad">$jk</span></td>
				<td></td>
				<td><span class="jad">$mjl</span></td><td>:</td><td><span class="jad">$mml</span></td>
				<td></td>
				<td><span class="jad">$sjl</span></td><td>:</td><td><span class="jad">$sml</span></td></tr>
EOT;

			  }
			  echo<<<EOT
			  </table>
			  <p class="center"><br/><font size="2">inisialisasi ulang jam perkuliahan?</font>
			  &nbsp;<a class="x" href="hapusjam.php?idj=$idj" title="Hapus">-</a></p>
EOT;
			}else{
			  echo"<p class=\"center\"><span class=\"jad\"><i>Belum ada data...</i></span><br/></br></p>
			  ";
		  ?>
		
		<div id="delegate4">
		<form action="tambahjam.php" method="post" id="tambahjam">
		  <p class="center">tambahkan jam-jam perkuliahan:</p>
		  <div><table>
		  <tr><td>Jam Kuliah ke-</td> <td></td><td>Mulai (Jam)</td><td></td><td>Mulai (Menit)</td><td> </td><td>Selesai (Jam)</td><td></td><td>Selesai (Menit)</td></tr>
		  <tr>
		    <td><select name="jamkul[]">
			<?php
			$jamkul = array(1=>'I','II','III','IV','V','VI','VII','VIII','IX','X',
			'XI','XII','XIII','XIV','XV','XVI','XVII','XVIII','XIX','XX');
			foreach($jamkul as $k => $v){
			echo<<<EOT
			<option value="$k">$v</option>
EOT;
			}
			?>
			</select></td>
			<td> </td>
		    <td>
			<select name="mulai_jam[]">
			<?php
			$jam = array(1=>'01','02','03','04','05','06','07','08','09','10','11',
			'12','13','14','15','16','17','18','19','20','21','22','23','24');
			foreach($jam as $k => $v){
			echo<<<EOT
			<option value="$k">$v</option>
EOT;
			}
			?>
			</select></td>
		    <td>:</td>
		    <td>
			<select name="mulai_menit[]">
			<?php
			$menit = array(0=>'00',10=>'10',20=>'20',30=>'30',40=>'40',50=>'50');
			foreach($menit as $k => $v){
			echo<<<EOT
			<option value="$k">$v</option>
EOT;
			}
			?>
			</select></td>
			<td> </td>
		    <td>
			<select name="selesai_jam[]">
			<?php
			$jam = array(1=>'01','02','03','04','05','06','07','08','09','10','11',
			'12','13','14','15','16','17','18','19','20','21','22','23','24');
			foreach($jam as $k => $v){
			echo<<<EOT
			<option value="$k">$v</span></option>
EOT;
			}
			?>
			</select>
			</td>
			<td>:</td>
			<td>
			<select name="selesai_menit[]">
			<?php
			$menit = array(0=>'00',10=>'10',20=>'20',30=>'30',40=>'40',50=>'50');
			foreach($menit as $k => $v){
			echo<<<EOT
			<option value="$k">$v</option>
EOT;
			}
			?>
			</select></td>
		  </tr></table>
		  <div class="add4">+ Tambah Form</div>
		  </div>
		  
		  <p class="center"><br/><input type="submit" name="submit" value="Submit" id="subnewdata"/></p>
	      <input type="hidden" name="submitted" value="true"/>
	    </form>
		</div>
		
		 <?php
			}
		 ?>
				
      </div>
	 
	</div>
	
	
  </div>
</div>

<?php include('../footer.html'); ?>

