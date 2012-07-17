<?php
$page_title="Input Data";
include('../header.html');

session_start();
if(!isset($_SESSION['id']) or !isset($_SESSION['tahun']) or !isset($_SESSION['ajaran'])){

  $url='index.php';
  header("Location: $url");
  exit();
  
}


require_once('../mysqli_connect.php');
$q="select id_TA from ta where tahun={$_SESSION['tahun']} and ajaran='{$_SESSION['ajaran']}' limit 1";
$r=mysqli_query($dbc, $q); 
list($_SESSION['id_ta'])=mysqli_fetch_row($r);



echo"<span class=\"right\"><a href=\"logout.php\" class=\"log\">Logout</a></span>";
?>

<a href="cp.php" class="back"><</a>&nbsp;&nbsp;
<span class="heading">Jadwal Tahun Ajaran <?php echo"{$_SESSION['tahun']} / Semester {$_SESSION['ajaran']}" ?></span>

<div id="idata" class="base inputB">
  <div class="label inputD">INPUT DATA</div>
  <div class="form">
    <p class="center">
	  masukkan data-data yang diperlukan secara cermat dan tepat!
	</p>
	
	<div id="formwrapper" class="dosenWrapper">	
	  <div id="pointer" class="indata cdosen">Dosen</div>
	  
      <div class="form indosen">
	    <form action="cekdosen.php" method="post" id="inputdosen">
		<div id="delegate">
		  <p class="center">masukkan data-data dosen:</p>
		  
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
  $q = "select d.nama, m.nama from dosen as d inner join matkul as m using(id_TA) where id_TA={$_SESSION['id_ta']}";
  $r = mysqli_query($dbc, $q);
  if(mysqli_num_rows($r)>0){
    list($nd, $nm) = mysqli_fetch_row($r);
  }else{
    $nd = false;
	$nm = false;
  }
  $qk = "select k.nama, p.nama from kelas as k inner join prodi as p using(id_TA) where id_TA={$_SESSION['id_ta']}";
  $rk = mysqli_query($dbc, $qk);
  if(mysqli_num_rows($rk)>0){
    list($nk, $np) = mysqli_fetch_row($rk);
  }else{
    $nk = false;
	$np = false;
  }
  $qt = "select nama from tipekuliah where id_TA={$_SESSION['id_ta']}";
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
	  
      <div class="form inpdosen">
        
		<div id="delegate7">
		<form action="cekpdosen.php" method="post" id="inputpdosen">
		  <p class="center">masukkan data-data penugasan dosen:</p>
		  <div><table>
		  <tr><td>Dosen</td><td>Mata Kuliah</td><td>Program Studi</td><td>Perkuliahan</td><td>Kelas</td><td>Semester</td>
		  <tr>
		    <td><select name="nod[]">
EOT;
		$qd = "select nod, nama from dosen where id_TA={$_SESSION['id_ta']} order by nama asc";
		$rd = mysqli_query($dbc, $qd);
		while(list($nod, $dosen)=mysqli_fetch_row($rd)){
		  echo"<option value=\"$nod\">$dosen</option>";
		}
		  echo<<<EOT
		  </select></td>
		  <td><select name="idm[]">
EOT;
		$q2 = "select id_matkul, nama, sks from matkul where id_TA={$_SESSION['id_ta']} order by nama asc";
		$r2 = mysqli_query($dbc, $q2);
		while(list($idm, $nama, $sks)=mysqli_fetch_row($r2)){ 
		  echo"<option value=\"$idm\">$nama / $sks SKS</option>";
		}
		  echo<<<EOT
		  </select></td>
		  <td><select name="idp[]">
EOT;
		$qp = "select id_prodi, nama from prodi where id_TA={$_SESSION['id_ta']} order by nama asc";
		$rp = mysqli_query($dbc, $qp);
		while(list($idp, $nama)=mysqli_fetch_row($rp)){ 
		  echo"<option value=\"$idp\">$nama</option>";
		}
		  echo<<<EOT
		  </select></td>
		  <td><select name="idt[]">
EOT;
		$qt = "select id_tipe, nama from tipekuliah where id_TA={$_SESSION['id_ta']} order by nama asc";
		$rt = mysqli_query($dbc, $qt);
		while(list($idt, $nama)=mysqli_fetch_row($rt)){ 
		  echo"<option value=\"$idt\">$nama</option>";
		}
		  echo<<<EOT
		  </select></td>
		  <td><select name="idk[]">
EOT;
		$q3 = "select id_kls, nama from kelas where id_TA={$_SESSION['id_ta']} order by nama asc";
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
	  
		<div id="delegate2">
		<form action="cekprodi.php" method="post" id="inputprodi">
		  <p class="center">masukkan nama-nama program studi:</p>
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
        
		<div id="delegate6">
		<form action="cekmatkul.php" method="post" id="inputmatkul">
		  <p class="center">masukkan data-data mata kuliah:</p>
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
        
		<div id="delegate9">
		<form action="cekkelas.php" method="post" id="inputkelas">
		  <p class="center">masukkan data-data kelas:</p>
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
        
		<div id="delegate3">
		<form action="ceklokal.php" method="post" id="inputlokal">
		  <p class="center">masukkan nama-nama lokal:</p>
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
	  
		<div id="delegate5">
		<form action="cektipkul.php" method="post" id="inputprodi">
		  <p class="center">masukkan tipe-tipe perkuliahan:</p>
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
	  <form action="cekhari.php" method="post" id="inputdosen">
	  <p class="center">centang nama-nama hari berikut, untuk mengaktifkan hari kuliah:</p>
	  <table><tr>
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
        
		<div id="delegate4">
		<form action="cekjam.php" method="post" id="inputjam">
		  <p class="center">masukkan jam-jam perkuliahan:</p>
		  <div><table>
		  <tr><td>Jam Kuliah</td> <td></td><td>Mulai (Jam)</td><td></td><td>Mulai (Menit)</td><td> </td><td>Selesai (Jam)</td><td></td><td>Selesai (Menit)</td></tr>
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
		
      </div>
	 
	</div>
	

  </div>
</div>

<?php include('../footer.html'); ?>

