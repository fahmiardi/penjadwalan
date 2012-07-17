<?php

session_start();
if(!isset($_SESSION['adm_super']) and !isset($_SESSION['adm_univ']) and !isset($_SESSION['adm_fak']) and !isset($_SESSION['adm_prodi']) and !isset($_SESSION['nod'])){

  $url='../index.php';
  header("Location: $url");
  exit();
  
}

$page_title="Data Master";

include('../header.html');

echo'<span class="right">';
if(isset($_SESSION['univ'])){
  echo<<<EOT
  <span class="jad" id="jlog"><font style="font-family:Hopper; font-size:13px;">{$_SESSION['univ']}</font>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
EOT;
}elseif(isset($_SESSION['fak'])){
  echo<<<EOT
  <span class="jad" id="jlog"><font style="font-family:Hopper; font-size:13px;">{$_SESSION['fak']}</font>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
EOT;
}elseif(isset($_SESSION['prodi'])){
  echo<<<EOT
  <span class="jad" id="jlog"><font style="font-family:Hopper; font-size:13px;">{$_SESSION['prodi']}</font>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
EOT;
}elseif(isset($_SESSION['dosen'])){
  echo<<<EOT
  <span class="jad" id="jlog"><font style="font-family:Hopper; font-size:13px;">{$_SESSION['dosen']}</font>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
EOT;
}else{
  echo<<<EOT
  <span class="jad" id="jlog"><font style="font-family:Hopper; font-size:13px;">f1sh</font>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
EOT;
}
echo<<<EOT
  <a href="logout.php" class="off"></a></span></span>
EOT;
?>

<div id="cp" class="base">
  <div class="label">DATA MASTER</div>
  <div class="form">
    
  <?php // JADWAL
    echo<<<EOT
    <div id="formwrapper" class="jadwalWrapper">
	  <div id="pointer" class="slider exc">Kalender Akademik</div>
	  
	  <div class="form slide exc" style="min-width:500px;">
EOT;
		  require_once('../mysqli_connect.php');
		  $q="select id_TA, tahun, ajaran from ta order by id_TA desc";
		  $r=mysqli_query($dbc, $q);
		  
		  if(mysqli_num_rows($r) > 0){
		    
			$i = 1;
			
		    while(list($id_ta, $tahun, $aj)=mysqli_fetch_row($r)){
			  if($aj==1){$ajaran='Ganjil';}else{$ajaran='Genap';}
			  echo'<p class="center">';
			  if(isset($_SESSION['adm_super']) or isset($_SESSION['adm_univ']) or isset($_SESSION['adm_fak'])){
				echo"<span class=\"vjx\"><a href=\"hapusdj.php?ta=$id_ta\" class=\"x del".$i++." deljad\" style=\"color:white;\">-</a></span> ";
			  }
			  echo<<<EOT
		        <a href="dj.php?ta=$id_ta&aj=$aj" class="vj">Tahun Akademik $tahun - $ajaran</a><br/><br/>
EOT;
			  echo'</p>';
			}
			echo"<br/>";
			
		  }else{
		    echo<<<EOT
			  <p class="center">
		        <span class="jad">Belum ada jadwal</span>
		      </p><br/>
EOT;
		  }
		
	  if(isset($_SESSION['adm_super']) or isset($_SESSION['adm_univ']) or isset($_SESSION['adm_fak'])){
	  
	    if(isset($_SESSION['struck_ta'])){
		  echo"<p class=\"center\"><font color=\"#e01515\">{$_SESSION['struck_ta']}</font></p>";
		  unset($_SESSION['struck_ta']);
		}elseif(isset($_SESSION['error_ta'])){
		  echo"<p class=\"center\"><font color=\"#e01515\">{$_SESSION['error_ta']}</font></p>";
		  unset($_SESSION['error_ta']);
		}
		
	  echo<<<EOT
	  <div class="newsheet">Buat Tahun Akademik Baru</div>
	  <div class="form jadwalbaru">
		<div id="checkjadwalwrap">
		<p class="center">Tahun Akademik:</p>
		<form action="input_ta.php" method="post" id="input_ta">
		  <p class="center"><select name="tahun" id="tahun">
EOT;
		    $year = date('Y');
		  
		    for($i=0; $i<=2; $i++){
		      $ya = $year + $i;
			  $yb = $ya - 1;
		      echo "<option value=" . $yb .'/'. $ya . ">" . $yb .'/'. $ya . "</option><br/>\n";
		    }
		  
		  echo<<<EOT
		  </select>
		  <select name="ajaran" id="ajaran">
		    <option value="1">Ganjil</option>
		    <option value="2">Genap</option>
		  </select></p>
          <p class="center"><input type="submit" name="submit" value="Submit" id="subnewjad"/></p>
          <input type="hidden" name="submitted" value="TRUE"/>
        </form>
		</div>
	  </div>
EOT;
	  }

	echo'
	  </div>
	</div>'; // END JADWAL
  ?> 
  
  <?php // UNIVERSITAS
  if(isset($_SESSION['adm_super'])){
    $q = "select id_univ, nama, username, aes_decrypt(password, 'f1sh6uts') from univ order by nama asc";
	$r = mysqli_query($dbc, $q);
    echo<<<EOT
	<div id="formwrapper" class="sliderWrapper">
	  <div id="pointer" class="slider com">Universitas</div>
	  <div class="form slide com">
EOT;
	if(mysqli_num_rows($r)>0){
	  echo<<<EOT
	  <p class="center" style="font-size:12px;">Ubah Data Universitas:</p>
	  <table id="tabAppend">
        <tr class="exc"><td></td><td class="xfont">Universitas</td><td class="xfont">Username</td><td class="xfont">Password</td><td></td></tr>
EOT;
	  $i = 1;
	  while(list($idu, $nama, $username, $password)=mysqli_fetch_row($r)){
		echo"
		<tr>
		  <form action=\"upuniv.php\" method=\"post\" id=\"upuniv".$i++."\">";
		  echo<<<EOT
		  <td class="vpad"><a class="x deluniv" href="hapusuniv.php?idu=$idu" title="Hapus">-</a></td>
		  <td><input type="text" name="nama" value="$nama"/></td>
		  <td><input type="text" name="username" value="$username"/></td>
		  <td><input type="password" name="password" value="$password"/></td>
		  <td>
		    <input type="hidden" name="idu" value="$idu"/>
			<input type="submit" name="submit" value="+" title="Update" class="update"/>
			<input type="hidden" name="submitted" value="TRUE"/>
		  </td>
		  </form>
		</tr>	
				
EOT;
	  }
	  echo'</table>';
	}else{
	  echo"<p class=\"center\"><span class=\"jad\">Belum ada data Universitas</span></p>";
	}
	
	echo<<<EOT
	  <div class="hr"><hr/></div>
		  
	  <p class="center" style="font-size:12px;">Tambah Data Universitas:</p>
		  
	  <div id="checkuniv">
        <form action="cekuniv.php" method="post" id="cekuniv">
	    <table>
          <tr class="exc"><td class="xfont">Universitas</td><td class="xfont">Username</td><td class="xfont">Password</td></tr>
		  <tr class="exc">
		    <td><input type="text" name="nama"/></td>
		    <td><input type="text" name="username"/></td>
		    <td><input type="password" name="password"/></td>
		    </tr>
	    </table>
        <p class="center"><input type="submit" name="submit" value="+ Tambah"/></p>
        <input type="hidden" name="submitted" value="TRUE"/>
        </form>
	  </div>

	  </div>
	</div>
EOT;
  } // END UNIVERSITAS
  ?>
	
  <?php // FAKULTAS
  if(isset($_SESSION['adm_super'])){
    $sql = "select id_univ, nama from univ order by nama asc";
	$res = mysqli_query($dbc, $sql);
  }
  if((isset($_SESSION['adm_super']) and mysqli_num_rows($res)>0) or isset($_SESSION['adm_univ'])){
	
	echo<<<EOT
	<div id="formwrapper" class="fakWrapper">
	  <div id="pointer" class="slider com">Fakultas</div>
	  <div class="form slide com">  
EOT;
	  if(isset($_SESSION['adm_super'])){
	
	    $q = "select id_univ, nama from univ order by nama asc";
		$r = mysqli_query($dbc, $q);
		echo'<div class="hr" style="margin:10px 10px;"><hr/></div>';
		while(list($idu, $univ) = mysqli_fetch_row($r)){
		  echo"<div class=\"divprodi\"><p class=\"center\"><span class=\"jad\">$univ</span></p>";
		  $qf = "select id_fak, nama, username, aes_decrypt(password, 'f1sh6uts') from fakultas where id_univ=$idu";
		  $rf = mysqli_query($dbc, $qf);
		  if(mysqli_num_rows($rf)>0){
		    echo'<table style="padding:15px 0;"><tr class="exc"><td class>Fakultas</td><td>Username</td><td>Password</td></tr>';
			while(list($idf, $fak, $user, $pass) = mysqli_fetch_row($rf)){
			  echo"
			  <tr>
			    <td class=\"vpad\"><span class=\"jad\">$fak</span></td>
				<td><span class=\"jad\">$user</span></td>
				<td><span class=\"jad\">$pass</span></td>
			  </tr>";
			}
			echo'</table>';
		  }else{
		    echo'Belum ada Fakultas';
		  }
		  echo'</div><div class="hr" style="margin:10px 10px;"><hr/></div>';
		}
	
	  }elseif(isset($_SESSION['adm_univ'])){
	
		$q = "select id_fak, nama, username, aes_decrypt(password, 'f1sh6uts') from fakultas where id_univ={$_SESSION['adm_univ']} order by nama asc";
		$r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r)>0){
		  echo<<<EOT
		  <p class="center" style="font-size:12px;">Ubah Data Fakultas:</p>
		  
		  <table id="tabAppend">
            <tr class="exc"><td></td><td class="xfont">Fakultas</td><td class="xfont">Username</td><td class="xfont">Password</td><td></td></tr>
EOT;
		  $i = 1;
		  while(list($idf, $nama, $username, $password)=mysqli_fetch_row($r)){
			  
			  echo"
			  <tr>
				<form action=\"upfak.php\" method=\"post\" id=\"upfak".$i++."\">";
				echo<<<EOT
			    <td class="vpad"><a class="x delfak" href="hapusfak.php?idf=$idf" title="Hapus">-</a></td>
			    <td><input type="text" name="nama" value="$nama"/></td>
				<td><input type="text" name="username" value="$username"/></td>
				<td><input type="password" name="password" value="$password"/></td>
				<td>
				  <input type="hidden" name="idf" value="$idf"/>
				  <input type="submit" name="submit" value="+" title="Update" class="update"/>
				  <input type="hidden" name="submitted" value="TRUE"/>
				</td>
				</form>
			  </tr>	
				
EOT;
		  }
		  echo'</table>';
		}else{
		  echo"<p class=\"center\"><span class=\"jad\">Belum ada data Fakultas</span></p>";
		}
		
		  echo<<<EOT
		  <div class="hr"><hr/></div>
		  
		  <p class="center" style="font-size:12px;">Tambah Data Fakultas:</p>
		  
		  <div id="checkfak">
          <form action="cekfak.php" method="post" id="cekfak">
		  
	        <table>
              <tr class="exc"><td class="xfont">Fakultas</td><td class="xfont">Username</td><td class="xfont">Password</td></tr>
			  <tr class="exc">
			    <td><input type="text" name="nama"/></td>
				<td><input type="text" name="username"/></td>
				<td><input type="password" name="password"/></td>
			  </tr>
	        </table>
          <p class="center"><input type="submit" name="submit" value="+ Tambah"/></p>
          <input type="hidden" name="submitted" value="TRUE"/>
          </form>
		  </div>
EOT;
	  } 
	
	echo'
	</div>
  </div>';
  }  // END FAKULTAS
  ?>
  
  <?php // PROGRAM STUDI
  if(isset($_SESSION['adm_univ'])){
    $sql = "select id_fak, nama from fakultas where id_univ={$_SESSION['adm_univ']} order by nama asc";
    $res = mysqli_query($dbc, $sql);
  }elseif(isset($_SESSION['adm_super'])){
    $sql = "select * from fakultas";
	$res = mysqli_query($dbc, $sql);
  }
  
  if((isset($_SESSION['adm_univ']) and mysqli_num_rows($res)>0) or (isset($_SESSION['adm_super']) and mysqli_num_rows($res)>0) or isset($_SESSION['adm_fak'])){

  echo<<<EOT
  <div id="formwrapper" class="sliderWrapper">
	<div id="pointer" class="slider com">Program Studi</div>
	<div class="form slide com">
EOT;
	  if(isset($_SESSION['adm_super'])){
	    echo'<div class="hr" style="margin:10px 10px;"><hr/></div>';
		$qu = "select id_univ, nama from univ order by nama asc";
		$ru = mysqli_query($dbc, $qu);
		while(list($idu, $univ) = mysqli_fetch_row($ru)){
		  echo"<div class=\"divprodi\"><p class=\"center\">$univ</p><br/>";
		  $qf = "select id_fak, nama from fakultas where id_univ=$idu order by nama asc";
		  $rf = mysqli_query($dbc, $qf);
		  if(mysqli_num_rows($rf)>0){
		    while(list($idf, $fak) = mysqli_fetch_row($rf)){
			  echo"<span class=\"jad\">$fak</span>";
			  echo'<table style="padding:15px 0;"><tr class="exc"><td>Program Studi</td><td>Username</td><td>Password</td></tr>';
			  $q = "select id_prodi, nama, username, aes_decrypt(password, 'f1sh6uts') from prodi where id_fak=$idf";
			  $r = mysqli_query($dbc, $q);
			  if(mysqli_num_rows($r)>0){
			    while(list($idp, $prodi, $user, $pass) = mysqli_fetch_row($r)){
				  echo"
				  <tr>
				    <td class=\"vpad\"><span class=\"jad\">$prodi</span></td>
					<td><span class=\"jad\">$user</span></td>
					<td><span class=\"jad\">$pass</span></td>
				  </tr>";
				}
			  }else{
			    echo'Belum ada Program Studi';
			  }
			  echo'</table>';
			}
		  }else{
		    echo'<span class="jad">Belum ada Fakultas</span>';
		  }
		  echo'</div><div class="hr" style="margin:10px 10px;"><hr/></div>';
		}
	  }elseif(isset($_SESSION['adm_univ'])){
		echo'<div class="hr" style="margin:10px 10px;"><hr/></div>';
	    while(list($idf, $fak) = mysqli_fetch_row($res)){
		  $q = "select id_prodi, nama, username, aes_decrypt(password, 'f1sh6uts') from prodi where id_fak=$idf order by nama asc";
		  $r = mysqli_query($dbc, $q);
		  echo"<div class=\"divprodi\"><p class=\"center\"><span class=\"jad\">$fak</span></p>";
		  if(mysqli_num_rows($r)>0){
			echo'<table style="padding:15px 0;"><tr class="exc"><td>Program Studi</td><td>Username</td><td>Password</td></tr>';
			while(list($idp, $prodi, $uProdi, $pProdi)=mysqli_fetch_row($r)){
			  echo<<<EOT
			  <tr>
				<td class="vpad"><span class="jad">$prodi<span></td>
				<td><span class="jad">$uProdi<span></td>
			    <td><span class="jad">$pProdi<span></td>
			  </tr>
EOT;
			}
			echo'</table>';
		  }else{
			echo'Belum ada Program Studi';
		  }
		  echo'</div><div class="hr" style="margin:10px 10px;"><hr/></div>';
		}
		  
	  }elseif(isset($_SESSION['adm_fak'])){
	  
	    echo'<div style="position:relative; display:table;">
		  <div id="refProdi" class="jpd" style="font-size:12px; cursor:pointer;  display:inline-block; text-shadow:none; border-radius:15px; padding:3px 5px; padding-top:4px;" title="refresh"><img src="../img/refresh.png" height="16px" width="16px"/></div>
		</div>
		<div id="ocRProdi">';
		  
		$q = "select id_prodi, nama, username, aes_decrypt(password, 'f1sh6uts') from prodi where id_fak={$_SESSION['adm_fak']} order by nama asc";
		$r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r)>0){
		  echo<<<EOT
		  <p class="center" style="font-size:12px;">Ubah Data Program Studi:</p>
		  <table id="tabAppend">
            <tr class="exc"><td></td><td class="xfont">Program Studi</td><td class="xfont">Username</td><td class="xfont">Password</td><td></td></tr>
EOT;
		  $i = 1;
		  while(list($idp, $nama, $username, $password)=mysqli_fetch_row($r)){
			  
		    echo"
			<tr>
			  <form action=\"upprodi.php\" method=\"post\" id=\"upprodi".$i++."\">";
			  echo<<<EOT
			  <td class="vpad"><a class="x delprodi" href="hapusprodi.php?idp=$idp" title="Hapus">-</a></td>
			  <td><input type="text" name="nama" value="$nama"/></td>
			  <td><input type="text" name="username" value="$username"/></td>
			  <td><input type="password" name="password" value="$password"/></td>
			  <td>
			    <input type="hidden" name="idp" value="$idp"/>
			    <input type="submit" name="submit" value="+" title="Update" class="update"/>
			    <input type="hidden" name="submitted" value="TRUE"/>
			  </td>
			  </form>
			</tr>	
				
EOT;
		  }
		  echo'</table></div>';
		}else{
		  echo"<p class=\"center\"><span class=\"jad\">Belum ada data Program Studi</span></p></div>";
		}
		  
		echo<<<EOT
		<div class="hr"><hr/></div>
		
		<p class="center" style="font-size:12px;">Tambah data Program Studi:</p>
		<div id="checkprodi">
          <form action="cekprodi.php" method="post" id="cekprodi">
	      <table>
			<tr class="exc"><td class="xfont">Program Studi</td><td class="xfont">Username</td><td class="xfont">Password</td></tr>
			<tr class="exc">
			  <td><input type="text" name="nama"/></td>
			  <td><input type="text" name="username"/></td>
			  <td><input type="password" name="password"/></td>
			</tr>
	      </table>
          <p class="center"><input type="submit" name="submit" value="+ Tambah"/></p>
          <input type="hidden" name="submitted" value="TRUE"/>
          </form>
		</div>
EOT;
	  }
		
	  echo'
	  </div>
	</div>';
  }
	
   // END PROGRAM STUDI
  ?>
  
    <?php // DOSEN
	if(isset($_SESSION['adm_fak'])){
	  $sql = "select * from dosen where id_fak={$_SESSION['adm_fak']}";
	  $res = mysqli_query($dbc, $sql);
	}elseif(isset($_SESSION['adm_univ'])){
	  $sql = "select * from dosen where id_univ={$_SESSION['adm_univ']}";
	  $res = mysqli_query($dbc, $sql);
	}elseif(isset($_SESSION['adm_super'])){
	  $sql = "select * from dosen";
	  $res = mysqli_query($dbc, $sql);
	}
	
	if((isset($_SESSION['adm_fak']) and mysqli_num_rows($res)>0) or (isset($_SESSION['adm_univ']) and mysqli_num_rows($res)>0) or (isset($_SESSION['adm_super']) and mysqli_num_rows($res)>0) or isset($_SESSION['adm_prodi'])){
	  echo<<<EOT
	  <div id="formwrapper" class="sliderWrapper">
	    <div id="pointer" class="slider dosen">Dosen</div>
	    <div class="form slide dosen" id="ocKurikulum">
EOT;
	  if(isset($_SESSION['adm_super'])){
	  
	    $q = "select id_univ, nama from univ order by nama asc";
		$r = mysqli_query($dbc, $q);
		echo'<div class="hr" style="margin:10px 10px;"><hr/></div>';
		while(list($idu, $univ) = mysqli_fetch_row($r)){
		  echo"<div class=\"divprodi  extDosen\"><p class=\"center\"><span class=\"jad\">$univ</span><br/><br/></p>";
		  $qf = "select id_fak, nama from fakultas where id_univ=$idu order by nama asc";
		  $rf = mysqli_query($dbc, $qf);
		  if(mysqli_num_rows($rf)>0){
		    while(list($idf, $fak) = mysqli_fetch_row($rf)){
			  echo"<p class=\"center\">$fak<br/><br/></p>";
			  $qp = "select id_prodi, nama from prodi where id_fak=$idf order by nama asc";
			  $rp = mysqli_query($dbc, $qp);
			  if(mysqli_num_rows($rp)>0){
			    while(list($idp, $prodi) = mysqli_fetch_row($rp)){
			      echo"<p class=\"center\"><span class=\"jad\">$prodi</span><br/><br/></p>";
			      $qd = "select nod, nip, nama, username, aes_decrypt(password, 'f1sh6uts'), email, telp from dosen where id_prodi=$idp order by nama asc";
			      $rd = mysqli_query($dbc, $qd);
				  if(mysqli_num_rows($rd)>0){
				    echo'<table><tr class="exc"><td>NIP</td><td>Dosen</td><td>Username</td><td>Password</td><td>Email</td><td>Telepon</td></tr>';
				    while(list($nod, $nip, $dosen, $user, $pass, $email, $telp) = mysqli_fetch_row($rd)){
					  echo"
					  <tr>
						<td class=\"vpad\"><span class=\"jad\">$nip</span></td>
						<td><span class=\"jad\">$dosen</span></td>
						<td><span class=\"jad\">$user</span></td>
						<td><span class=\"jad\">$pass</span></td>
						<td><span class=\"jad\">$email</span></td>
						<td><span class=\"jad\">$telp</span></td>
					  </tr>";
					}
					echo'</table><br/><br/>';
				  }else{
				    echo'Belum ada Dosen<br/><br/>';
				  }
			    }
			  }else{
			    echo'<span class="jad">Belum ada Prodi</span><br/><br/>';
			  }
			}
		  }else{
		    echo'Belum ada Fakultas<br/><br/>';
		  }
		  echo'</div><div class="hr" style="margin:10px 10px;"><hr/></div>';
		}
		
	  }elseif(isset($_SESSION['adm_univ'])){
	    
		$q = "select id_fak, nama from fakultas where id_univ={$_SESSION['adm_univ']} order by nama asc";
		$r = mysqli_query($dbc, $q);
		echo'<div class="hr" style="margin:10px 10px;"><hr/></div>';
		while(list($idf, $fak) = mysqli_fetch_row($r)){
		  echo"<div class=\"divprodi extDosen\"><p class=\"center\">$fak<br/><br/><br/></p>";
		  $qp = "select id_prodi, nama from prodi where id_fak=$idf order by nama asc";
		  $rp = mysqli_query($dbc, $qp);
		  if(mysqli_num_rows($rp)>0){
		    while(list($idp, $prodi) = mysqli_fetch_row($rp)){
			  echo"<p class=\"center\"><span class=\"jad\">$prodi</span></p>";
			  $qd = "select nod, nip, nama, username, aes_decrypt(password, 'f1sh6uts'), email, telp from dosen where id_prodi=$idp order by nama asc";
			  $rd = mysqli_query($dbc, $qd);
			  if(mysqli_num_rows($rd)>0){
				echo'<table><tr class="exc"><td>NIP</td><td>Dosen</td><td>Username</td><td>Password</td><td>Email</td><td>Telepon</td></tr>';
				while(list($nod, $nip, $dosen, $user, $pass, $email, $telp) = mysqli_fetch_row($rd)){
				  echo"
				  <tr>
					<td class=\"vpad\"><span class=\"jad\">$nip</span></td>
					<td><span class=\"jad\">$dosen</span></td>
					<td><span class=\"jad\">$user</span></td>
					<td><span class=\"jad\">$pass</span></td>
					<td><span class=\"jad\">$email</span></td>
					<td><span class=\"jad\">$telp</span></td>
				  </tr>";
				}	
				echo'</table><br/><br/>';
			  }else{
			    echo'Belum ada Dosen<br/><br/><br/>';
			  }
			}
		  }else{
		    echo'<span class=\"jad\">Belum ada Prodi</span>';
		  }
		  echo'</div><div class="hr" style="margin:10px 10px;"><hr/></div>';
		}
		
	  }elseif(isset($_SESSION['adm_fak'])){
	      
		  echo'<div id="hGPds">';
		  require_once('../mysqli_connect.php');
		  $q="select id_prodi, nama from prodi where id_fak={$_SESSION['adm_fak']} order by nama asc";
		  $r=mysqli_query($dbc, $q);
		  
		  if(mysqli_num_rows($r) > 0){
		    
			echo'<p class="center">Pilih Prodi:</p><br/>';
			$i = 1;
		    while(list($idp, $prodi)=mysqli_fetch_row($r)){
			  echo<<<EOT
			  <p class="center">
		          <a href="idp=$idp/ds" class="vj" id="gp">$prodi</a><br/><br/>
			  </p>
EOT;
			}
			echo"<br/>";
			
		  }else{
		    echo<<<EOT
			  <p class="center">
		        <span class="jad">Belum ada data prodi</span>
		      </p><br/>
EOT;
		  }
		  echo'</div>';
		  echo'<div id="ocGPds"></div>';
		  
	  }elseif(isset($_SESSION['adm_prodi'])){
	  
	    echo'<div style="position:relative; display:table;">
		  <div id="refDosen" class="jpd" style="font-size:12px; cursor:pointer;  display:inline-block; text-shadow:none; border-radius:15px; padding:3px 5px; padding-top:4px;" title="refresh"><img src="../img/refresh.png" height="16px" width="16px"/></div>
		</div>
		<div id="ocRDosen">';
	
		$qd = "select nod, nip, nama, username, aes_decrypt(password, 'f1sh6uts'), email, telp from dosen where
		id_prodi={$_SESSION['adm_prodi']} and id_fak={$_SESSION['idpf']} and id_univ={$_SESSION['idpu']} order by nama asc";
		$rd = mysqli_query($dbc, $qd);
		if(mysqli_num_rows($rd)>0){
		  echo<<<EOT
		  <p class="center" style="font-size:12px;">Ubah Data Dosen:</p>

		  <table id="tabAppend">
            <tr class="exc"><td></td><td class="xfont">NIP</td><td class="xfont">Dosen</td><td class="xfont">Username</td><td class="xfont">Password</td><td class="xfont">Email</td><td class="xfont">Telepon</td><td></td></tr>
EOT;
		  $i = 1;
		  while(list($nod, $nip, $dosen, $username, $password, $email, $telp)=mysqli_fetch_row($rd)){
			  
		    echo"
			<tr>
			  <form action=\"updosen.php\" method=\"post\" id=\"updosen".$i++."\">";
			  echo<<<EOT
			  <td class="vpad"><a class="x deldosen" href="hapusdosen.php?nod=$nod" title="Hapus">-</a></td>
			  <td><input type="text" name="nip" size="17" value="$nip"/></td>
			  <td><input type="text" name="nama" size="17" value="$dosen"/></td>
			  <td><input type="text" name="username" size="17" value="$username"/></td>
			  <td><input type="password" name="password" size="17" value="$password"/></td>
			  <td><input type="text" name="email" size="17" value="$email"/></td>
			  <td><input type="text" name="telp" size="17" value="$telp"/></td>
			  <td>
			    <input type="hidden" name="nod" value="$nod"/>
				<input type="hidden" name="idu" value="{$_SESSION['idpu']}"/>
			    <input type="submit" name="submit" value="+" title="Update" class="update"/>
			    <input type="hidden" name="submitted" value="TRUE"/>
			  </td>
			  </form>
			</tr>	
EOT;
		  }
		  echo'</table></div>';
		}else{
		  echo'<p class="center"><span class="jad" style="font-size:12px;">Belum ada data Dosen</span></p></div>';
		}
	     echo<<<EOT
		  <div class="hr"><hr/></div>
		  
		  <p class="center" style="font-size:12px;">Tambah Data Dosen:</p>
		  
		  <div id="checkdosen">
          <form action="cekdosen.php" method="post" id="cekdosen">
		  
	        <table>
              <tr class="exc"><td class="xfont">NIP</td><td class="xfont">Dosen</td><td class="xfont">Username</td><td class="xfont">Password</td><td class="xfont">Email</td><td class="xfont">Telepon</td></tr>
			  <tr class="exc">
			    <td><input type="text" size="17" name="nip"/></td>
			    <td><input type="text" size="17" name="nama"/></td>
				<td><input type="text" size="17" name="username"/></td>
				<td><input type="password" size="17" name="password"/></td>
				<td><input type="text" size="17" name="email"/></td>
				<td><input type="text" size="17" name="telp"/></td>
			  </tr>
	        </table>
          <p class="center"><input type="submit" name="submit" value="+ Tambah"/></p>
		  <input type="hidden" name="idp" value="{$_SESSION['adm_prodi']}"/>
		  <input type="hidden" name="idf" value="{$_SESSION['idpf']}"/>
		  <input type="hidden" name="idu" value="{$_SESSION['idpu']}"/>
          <input type="hidden" name="submitted" value="TRUE"/>
          </form>
		  </div>
		  
	    
EOT;

	  }
	  echo'
		</div>
	  </div>';
	} // end DOSEN
	?>
	
	<?php // MATAKULIAH
	if(isset($_SESSION['adm_super']) or isset($_SESSION['adm_univ']) or isset($_SESSION['adm_fak']) or isset($_SESSION['adm_prodi'])){
	
	  echo<<<EOT
	  <div id="formwrapper" class="sliderWrapper">
	    <div id="pointer" class="slider dosen">Matakuliah</div>
	    <div class="form slide dosen" id="ocKurikulum">
EOT;
	  if(isset($_SESSION['adm_super'])){
	  	    		
	  }elseif(isset($_SESSION['adm_univ'])){
	    			
	  }elseif(isset($_SESSION['adm_fak'])){
	    
		echo'<div id="hGPmk">';
		  require_once('../mysqli_connect.php');
		  $q="select id_prodi, nama from prodi where id_fak={$_SESSION['adm_fak']} order by nama asc";
		  $r=mysqli_query($dbc, $q);
		  
		  if(mysqli_num_rows($r) > 0){
		    
			echo'<p class="center">Pilih Prodi:</p><br/>';
			
			$i = 1;
		    while(list($idp, $prodi)=mysqli_fetch_row($r)){
			  echo<<<EOT
			  <p class="center">
		          <a href="idp=$idp/mk" class="vj" id="gp">$prodi</a><br/><br/>
			  </p>
EOT;
			}
			echo"<br/>";
			
		  }else{
		    echo<<<EOT
			  <p class="center">
		        <span class="jad">Belum ada data prodi</span>
		      </p><br/>
EOT;
		  }
		  echo'</div>';
		  echo'<div id="ocGPmk"></div>';
		
	  }elseif(isset($_SESSION['adm_prodi'])){
	  
	    echo'<div style="position:relative; display:table;">
		  <div id="refMkmaster" class="jpd" style="font-size:12px; cursor:pointer;  display:inline-block; text-shadow:none; border-radius:15px; padding:3px 5px; padding-top:4px;" title="refresh"><img src="../img/refresh.png" height="16px" width="16px"/></div>
		</div>
		<div id="ocRMkmaster">';
	
		$qd = "select id_matkul, kd_matkul, nama, sks from matkulmaster where
		id_prodi={$_SESSION['adm_prodi']} and id_fak={$_SESSION['idpf']} and id_univ={$_SESSION['idpu']} order by kd_matkul asc";
		$rd = mysqli_query($dbc, $qd);
		if(mysqli_num_rows($rd)>0){
		  echo<<<EOT
		  <p class="center" style="font-size:12px;">Ubah Data Matakuliah:</p>

		  <table id="tabMKappend">
            <tr class="exc"><td></td><td class="xfont">Kode</td><td class="xfont">Matakuliah</td><td class="xfont">SKS</td><td></td></tr>
EOT;
		  $i = 1;
		  while(list($idm, $kdm, $matkul, $sks)=mysqli_fetch_row($rd)){
			  
		    echo"
			<tr>
			  <form action=\"upmkmaster.php\" method=\"post\" id=\"upmkmaster".$i++."\">";
			  echo<<<EOT
			  <td class="vpad"><a class="x delmkmaster" href="hapusmkmaster.php?idm=$idm" title="Hapus">-</a></td>
			  <td><input type="text" name="kdm" size="12" value="$kdm"/></td>
			  <td><input type="text" name="matkul" size="25" value="$matkul"/></td>
			  <td><select name="sks">
			    <option value="$sks" selected="selected">$sks</option>
EOT;
			  $ss = range(1, 6);
			  foreach($ss as $k => $v){
			    if($v==$sks){
				  $v=false;
				}else{
				  echo"<option value=\"$v\">$v</option>";
				}
			  }
			  echo<<<EOT
			  </select></td>
			  <td>
			    <input type="hidden" name="idm" value="$idm"/>
				<input type="hidden" name="idu" value="{$_SESSION['idpu']}"/>
			    <input type="submit" name="submit" value="+" title="Update" class="update"/>
			    <input type="hidden" name="submitted" value="TRUE"/>
			  </td>
			  </form>
			</tr>	
EOT;
		  }
		  echo'</table></div>';
		}else{
		  echo'<p class="center"><span class="jad" style="font-size:12px;">Belum ada data Matakuliah</span></p></div>';
		}
	     echo<<<EOT
		  <div class="hr"><hr/></div>
		  
		  <p class="center" style="font-size:12px;">Tambah Data Matakuliah:</p>
		  
		  <div id="checkmkmaster">
          <form action="cekmkmaster.php" method="post" id="cekmkmaster">
		  
	        <table>
              <tr class="exc"><td class="xfont">Kode</td><td class="xfont">Matakuliah</td><td class="xfont">SKS</td></tr>
			  <tr class="exc">
			    <td><input type="text" size="12" name="kdm"/></td>
			    <td><input type="text" size="25" name="matkul"/></td>
EOT;
		  echo<<<EOT
				<td><select name="sks">
				  <option value="-" selected="selected">-</option>
EOT;
		  $ss = range(1, 6);
		  foreach($ss as $k => $v){
		    echo"<option value=\"$v\">$v</option>";
		  }
		   echo<<<EOT
			  </tr>
	        </table>
          <p class="center"><input type="submit" name="submit" value="+ Tambah"/></p>
		  <input type="hidden" name="idp" value="{$_SESSION['adm_prodi']}"/>
		  <input type="hidden" name="idf" value="{$_SESSION['idpf']}"/>
		  <input type="hidden" name="idu" value="{$_SESSION['idpu']}"/>
          <input type="hidden" name="submitted" value="TRUE"/>
          </form>
		  </div>
EOT;
	  }
	  echo'
		</div>
	  </div>';
	}
	// end MATAKULIAH
	?>
	
	<?php // KURIKULUM
	if(isset($_SESSION['adm_fak'])){
	  $sql = "select * from matkulmaster where id_fak={$_SESSION['adm_fak']}";
	  $res = mysqli_query($dbc, $sql);
	}elseif(isset($_SESSION['adm_univ'])){
	  $sql = "select * from kurikulum where id_univ={$_SESSION['adm_univ']}";
	  $res = mysqli_query($dbc, $sql);
	}elseif(isset($_SESSION['adm_super'])){
	  $sql = "select * from kurikulum";
	  $res = mysqli_query($dbc, $sql);
	}elseif(isset($_SESSION['adm_prodi'])){
	  $sql = "select * from matkulmaster where id_prodi={$_SESSION['adm_prodi']}";
	  $res = mysqli_query($dbc, $sql);
	}
	
	if((isset($_SESSION['adm_fak']) and mysqli_num_rows($res)>0) or (isset($_SESSION['adm_univ']) and mysqli_num_rows($res)>0) or (isset($_SESSION['adm_super']) and mysqli_num_rows($res)>0) or (isset($_SESSION['adm_prodi']) and mysqli_num_rows($res)>0)){
	  echo<<<EOT
	  <div id="formwrapper" class="sliderWrapper">
	    <div id="pointer" class="slider kuri">Kurikulum</div>
	    <div class="form slide kuri" id="kurikulumWrap">
EOT;
	  if(isset($_SESSION['adm_super'])){
	  
	    $q = "select id_univ, nama from univ order by nama asc";
		$r = mysqli_query($dbc, $q);
		echo'<div id="kurikulumSet"></div>
		<div class="hr" style="margin:10px 10px;"><hr/></div>';
		while(list($idu, $univ) = mysqli_fetch_row($r)){
		  echo"<div><p class=\"center\"><br/>$univ<br/><br/><br/></p>";
		  $qf = "select id_fak, nama from fakultas where id_univ=$idu order by nama asc";
		  $rf = mysqli_query($dbc, $qf);
		  if(mysqli_num_rows($rf)>0){
		    while(list($idf, $fak) = mysqli_fetch_row($rf)){
			  echo"<p class=\"center\"><span class=\"jad\">$fak</span><br/><br/></p>";
			  $qp = "select id_prodi, nama from prodi where id_fak=$idf order by nama asc";
			  $rp = mysqli_query($dbc, $qp);
			  if(mysqli_num_rows($rp)>0){
			    while(list($idp, $prodi) = mysqli_fetch_row($rp)){
			      echo"<p class=\"center\">$prodi<br/><br/></p>";
			      $qkr = "select id_krklm, tahun from kurikulum where id_prodi=$idp order by tahun desc";
				  $rkr = mysqli_query($dbc, $qkr);
				  if(mysqli_num_rows($rkr)>0){
				    while(list($idkr, $thn)=mysqli_fetch_row($rkr)){
					  echo<<<EOT
					    <p class="center"><a href="vKurikulum.php?kr=$idkr" class="vj" id="VKR">Kurikulum $thn</a></p><br/>
EOT;
					}
				  }else{
				    echo'<p class="center"><span class="jad" style="font-size:12px;">Belum ada data Kurikulum</span></p>';
				  }
			    }
			  }else{
			    echo'<span class="jad">Belum ada Prodi</span><br/><br/>';
			  }
			}
		  }else{
		    echo'Belum ada Fakultas<br/><br/>';
		  }
		  echo'</div><div class="hr" style="margin:10px 10px;"><hr/></div>';
		}
		
	  }elseif(isset($_SESSION['adm_univ'])){
	    
		$q = "select id_fak, nama from fakultas where id_univ={$_SESSION['adm_univ']} order by nama asc";
		$r = mysqli_query($dbc, $q);
		echo'<div id="kurikulumSet"></div>
		<div class="hr" style="margin:10px 10px;"><hr/></div>';
		while(list($idf, $fak) = mysqli_fetch_row($r)){
		  echo"<div><p class=\"center\"><br/><span class=\"jad\">$fak</span><br/><br/><br/></p>";
		  $qp = "select id_prodi, nama from prodi where id_fak=$idf order by nama asc";
		  $rp = mysqli_query($dbc, $qp);
		  if(mysqli_num_rows($rp)>0){
		    while(list($idp, $prodi) = mysqli_fetch_row($rp)){
			  echo"<p class=\"center\">$prodi</p>";
			  $qkr = "select id_krklm, tahun from kurikulum where id_prodi=$idp order by tahun desc";
			  $rkr = mysqli_query($dbc, $qkr);
			  if(mysqli_num_rows($rkr)>0){
				while(list($idkr, $thn)=mysqli_fetch_row($rkr)){
				  echo<<<EOT
				<p class="center"><a href="vKurikulum.php?kr=$idkr" class="vj" id="VKR">Kurikulum $thn</a></p><br/>
EOT;
				}	
			  }else{
			    echo'<p class="center"><span class="jad" style="font-size:12px;">Belum ada data Kurikulum</span></p>';
			  }
			}
		  }else{
		    echo'<span class=\"jad\">Belum ada Prodi</span>';
		  }
		  echo'</div><div class="hr" style="margin:10px 10px;"><hr/></div>';
		}
		
	  }elseif(isset($_SESSION['adm_fak'])){
	    
		echo'<div id="hGPkr">';
		  require_once('../mysqli_connect.php');
		  $q="select id_prodi, nama from prodi where id_fak={$_SESSION['adm_fak']} order by nama asc";
		  $r=mysqli_query($dbc, $q);
		  
		  if(mysqli_num_rows($r) > 0){
		    
			echo'<p class="center">Pilih Prodi:</p><br/>';
			
			$i = 1;
		    while(list($idp, $prodi)=mysqli_fetch_row($r)){
			  echo<<<EOT
			  <p class="center">
		          <a href="idp=$idp/kr" class="vj" id="gp">$prodi</a><br/><br/>
			  </p>
EOT;
			}
			echo"<br/>";
			
		  }else{
		    echo<<<EOT
			  <p class="center">
		        <span class="jad">Belum ada data prodi</span>
		      </p><br/>
EOT;
		  }
		  echo'</div>';
		  echo'<div id="ocGPkr"></div>';
		
	  }elseif(isset($_SESSION['adm_prodi'])){
	  
	    if(isset($_SESSION['struck_kr'])){
		  echo"<p class=\"center\"><font color=\"#e01515\">{$_SESSION['struck_kr']}</font></p>";
		  unset($_SESSION['struck_kr']);
		}elseif(isset($_SESSION['error_kr'])){
		  echo"<p class=\"center\"><font color=\"#e01515\">{$_SESSION['error_kr']}</font></p>";
		  unset($_SESSION['error_kr']);
		}
	  
	    echo<<<EOT
	  <div class="newsheet">Buat Kurikulum Baru</div>
	  <div class="form jadwalbaru">
		<div id="checkkurikulumwrap">
		<p class="center">Tahun Kurikulum:</p>
		<form action="kurikulum.php" method="post" id="cekKurikulum">
		  <p class="center"><select name="tahun" id="tahun">
EOT;
		    $year = date('Y');
		  
		    for($i=$year-5; $i<=$year+1; $i++){
		      echo "<option value=" . $i . ">" . $i . "</option><br/>\n";
		    }
		  
		  echo<<<EOT
		  </select></p>
          <p class="center"><input type="submit" name="submit" value="Submit" id="subnewkuri"/></p>
          <input type="hidden" name="submitted" value="TRUE"/>
        </form>
		</div>
	  </div>
EOT;
	
	  echo'<div class="hr"><hr/></div>';
	  
	  echo'<div style="position:relative; display:table; margin-top:-30px; margin-bottom:20px;">
		  <div id="refKurikulum" class="jpd" style="font-size:12px; cursor:pointer; display:inline-block; text-shadow:none; border-radius:15px; padding:3px 5px; padding-top:4px;" title="refresh"><img src="../img/refresh.png" height="16px" width="16px"/></div>
		</div>
		<div id="ocRKurikulum">';
	  
	  echo'<div class="form" style="margin-top:-30px; margin-bottom:10px;" id="krVault">';
	  
	  $q = "select id_krklm, tahun from kurikulum where id_prodi={$_SESSION['adm_prodi']} order by tahun desc";
	  $r = mysqli_query($dbc, $q);
	  if(mysqli_num_rows($r)>0){
		echo'<div id="kurikulumSet"></div>
		<span style="font-size:12px;">Ubah / Hapus Data Kurikulum:</span><br/><br/>
		<table>';
		
		while(list($idkr, $thn)=mysqli_fetch_row($r)){
		  echo<<<EOT
		   <tr class="exc">
		     <td class="vpad"><a class="x delkr" href="dKurikulum.php?kr=$idkr" title="Hapus">-</a></td>
		     <td><a href="eKurikulum.php?kr=$idkr" class="vj" id="EKR">Kurikulum $thn</a></td>
		   </tr>
EOT;
		}
		echo'</table>';
		
	  }else{
	    echo'<span class="jad" id="kurikulumNone" style="font-size:12px;">Belum ada data Kurikulum</span>';
	  }
	  
	  echo'</div></div>';

	  }
	  echo'
		</div>
	  </div>';
	} // end KURIKULUM
	?>
	
	<?php // LOKAL
	
	if(isset($_SESSION['adm_fak']) or isset($_SESSION['adm_univ']) or isset($_SESSION['adm_super'])){
	  echo<<<EOT
	  <div id="formwrapper" class="sliderWrapper">
	    <div id="pointer" class="slider com">Lokal</div>
	    <div class="form slide com" id="lokalWrap">
EOT;
	  if(isset($_SESSION['adm_super'])){
	  
	  }elseif(isset($_SESSION['adm_univ'])){
	  
	  }elseif(isset($_SESSION['adm_fak'])){
	  
	    echo'<div style="position:relative; display:table;">
		  <div id="refLokal" class="jpd" style="font-size:12px; cursor:pointer;  display:inline-block; text-shadow:none; border-radius:15px; padding:3px 5px; padding-top:4px;" title="refresh"><img src="../img/refresh.png" height="16px" width="16px"/></div>
		</div>
		<div id="ocRLokal">';
	  
	    $q = "select id_lkl, nama, muat from lokalmaster where id_fak={$_SESSION['adm_fak']} order by nama asc";
		$r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r)>0){
		  echo<<<EOT
		  <p class="center" style="font-size:12px;">Ubah Data Lokal:</p>
		  
		  <table id="tabElokals">
			<tr class="exc"><td></td><td class="xfont">Lokal</td><td class="xfont">Kapasitas</td><td></td></tr>
EOT;
		  $i = 1;
		  while(list($idl, $lokal, $muat)=mysqli_fetch_row($r)){
			echo"
			<tr>
			  <form action=\"uplokals.php\" method=\"post\" id=\"uplokals".$i++."\">";
			  echo<<<EOT
			  <td class="vpad"><a class="x dellokals" href="hapuslokals.php?idl=$idl" title="Hapus">-</a></td>
			  <td><input type="text" name="nama" size="10" value="$lokal"/></td>
			  <td><input type="text" name="muat" size="2" value="$muat"/></td>
			  <td>
				<input type="hidden" name="idl" value="$idl"/>
				<input type="submit" name="submit" value="+" title="Update" class="update"/>
				<input type="hidden" name="submitted" value="TRUE"/>
			  </td>
			  </form>
			</tr>			
EOT;
		  }
		  echo'</table></div>';
		}else{
		  echo'<p class="center"><span class="jad" style="font-size:12px;">Belum ada data Lokal</span></p></div>';
		}
		echo<<<EOT
	    <div class="hr"><hr/></div>
		  
	    <p class="center" style="font-size:12px;">Tambah Data Lokal:</p>
		  
		<div id="checklokals">
          <form action="ceklokals.php" method="post" id="ceklokals">
		  <table>
            <tr class="exc"><td class="xfont">Lokal</td><td class="xfont">Kapasitas</td></tr>
		    <tr class="exc">
		      <td><input type="text" size="10" name="nama"/></td>
			  <td><input type="text" size="2" name="muat"/></td>
		    </tr>
	      </table>
          <p class="center"><input type="submit" name="submit" value="+ Tambah"/></p>
          <input type="hidden" name="submitted" value="TRUE"/>
          </form>
	    </div>
EOT;
	  }
	  
	  echo'
		</div>
	  </div>';
	} // end LOKAL
	?>
	
	<?php
	// JAM PERKULIAHAN
	if(isset($_SESSION['adm_super']) or isset($_SESSION['adm_univ']) or isset($_SESSION['adm_fak'])){
	  echo<<<EOT
	  <div id="formwrapper" class="sliderWrapper">
	    <div id="pointer" class="slider com">Jam Perkuliahan</div>
	    <div class="form slide com">
EOT;

	  if(isset($_SESSION['adm_super'])){
	  
	    /*$q = "select id_univ, nama from univ order by nama asc";
		$r = mysqli_query($dbc, $q);
		echo'<div class="hr" style="margin:10px 10px;"><hr/></div>';
		while(list($idu, $univ) = mysqli_fetch_row($r)){
		  echo"<div class=\"divprodi\"><p class=\"center\">$univ<br/><br/></p>";
		  $qf = "select id_fak, nama from fakultas where id_univ=$idu order by nama asc";
		  $rf = mysqli_query($dbc, $qf);
		  if(mysqli_num_rows($rf)>0){
		    while(list($idf, $fak) = mysqli_fetch_row($rf)){
			  echo"<p class=\"center\"><span class=\"jad\">$fak</span><br/></p>";
			  $qj = "select jam_kul, mulai_jam, mulai_menit, selesai_jam, selesai_menit from tabeljam where id_fak=$idf and id_TA={$_SESSION['id_ta']} order by jam_kul asc";
			  $rj = mysqli_query($dbc, $qj);
			  if(mysqli_num_rows($rj)>0){
				echo'<table><tr><td>Jam <font style="font-size:10px;">Ke-</font></td><td></td><td>Mulai <font style="font-size:10px;">Jam</font></td><td></td><td>Mulai <font style="font-size:10px;">Menit</font></td><td> </td><td>Selesai <font style="font-size:10px;">Jam</font></td><td></td><td>Selesai <font style="font-size:10px;">Menit</font></td></tr>';
				while(list($jk, $mj, $mm, $sj, $sm) = mysqli_fetch_row($rj)){
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
				echo'</table><br/><br/>'; 
			  }else{
				echo'Belum ada Jam Perkuliahan<br/><br/>';
			  }
			}
		  }else{
		    echo'Belum ada Fakultas<br/><br/>';
		  }
		  echo'</div><div class="hr" style="margin:10px 10px;"><hr/></div>';
		}*/
		
	  }elseif(isset($_SESSION['adm_univ'])){
	    
		/*$q = "select id_fak, nama from fakultas where id_univ={$_SESSION['adm_univ']} order by nama asc";
		$r = mysqli_query($dbc, $q);
		echo'<div class="hr" style="margin:10px 10px;"><hr/></div>';
		while(list($idf, $fak) = mysqli_fetch_row($r)){
		  echo"<div class=\"divprodi\"><p class=\"center\"><span class=\"jad\">$fak</span><br/></p>";
		  $qj = "select jam_kul, mulai_jam, mulai_menit, selesai_jam, selesai_menit from tabeljam where id_fak=$idf and id_TA={$_SESSION['id_ta']} order by jam_kul asc";
		  $rj = mysqli_query($dbc, $qj);
		  if(mysqli_num_rows($rj)>0){
		    echo'<table><tr><td>Jam <font style="font-size:10px;">Ke-</font></td><td></td><td>Mulai <font style="font-size:10px;">Jam</font></td><td></td><td>Mulai <font style="font-size:10px;">Menit</font></td><td> </td><td>Selesai <font style="font-size:10px;">Jam</font></td><td></td><td>Selesai <font style="font-size:10px;">Menit</font></td></tr>';
			while(list($jk, $mj, $mm, $sj, $sm) = mysqli_fetch_row($rj)){
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
		    echo'</table><br/><br/>'; 
		  }else{
		    echo'Belum ada Jam Perkuliahan<br/><br/>';
		  }
		  echo'</div><div class="hr" style="margin:10px 10px;"><hr/></div>';
		}*/
		
	  }elseif(isset($_SESSION['adm_fak'])){
	  
	    echo'<div style="position:relative; display:table;">
		  <div id="refJKx" class="jpd" style="font-size:12px; cursor:pointer; display:inline-block; text-shadow:none; border-radius:15px; padding:3px 5px; padding-top:4px;" title="refresh"><img src="../img/refresh.png" height="16px" width="16px"/></div>
		</div>
		<div id="ocRJK">';
	  
	    $q = "select id_jam, jam_kul, mulai_jam, mulai_menit, selesai_jam, selesai_menit from tabeljammaster where id_fak={$_SESSION['adm_fak']} order by jam_kul asc";
		$r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r) > 0){
		  echo"<p class=\"center\" style=\"font-size:12px;\">Ubah Data Jam Perkuliahan:</span><br/></p>
		  <table id=\"tabEjam\">
		  <tr class=\"exc\"><td></td><td>Jam ke-</td><td></td><td>Mulai <font style=\"font-size:10px;\">Jam</font></td><td></td><td>Mulai <font style=\"font-size:10px;\">Menit</font></td><td> </td><td>Selesai <font style=\"font-size:10px;\">Jam</font></td><td></td><td>Selesai <font style=\"font-size:10px;\">Menit</font></td><td></td></tr>";
		  $rj = mysqli_query($dbc, "select MAX(jam_kul) from tabeljammaster where id_fak={$_SESSION['adm_fak']}");
		  if(mysqli_num_rows($rj)>0){
			list($maxJ) = mysqli_fetch_row($rj);
		  }else{
		    $maxJ = 1;
		  } 
		  $i = 1; $ic = 1;
		  while(list($idj, $jk, $mj, $mm, $sj, $sm)=mysqli_fetch_row($r)){
			  
		    if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
		    if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
			if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
			if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
			
			echo"<tr><form action=\"upjamx.php\" method=\"post\" id=\"upjamx".$i++."\">";
			echo'
			  <td class="vpad"><a class="x deljamx atc'.$ic++.'" href="hapusjamx.php?idj='.$idj.'" title="Hapus"'; if($jk!=$maxJ){echo'style="display:none;"';} echo'>-</a></td>
		      <td><span class="jad">';
			  echo $jk;
			  /*$jamkul = range(1, 20);
			  foreach($jamkul as $k => $v){
			    echo"<option value=\"$v\">$v</option>";
			  }*/
			  echo<<<EOT
			  </span></td>
			  <td> </td>
		      <td><select name="mulai_jam">
			  <option value="$mj" selected="selected">$mjl</option>
EOT;
			  $jam = array(1=>'01','02','03','04','05','06','07','08','09','10','11',
			  '12','13','14','15','16','17','18','19','20','21','22','23','24');
			  foreach($jam as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td>:</td>
		      <td><select name="mulai_menit">
			  <option value="$mm" selected="selected">$mml</option>
EOT;
			  $menit = array(0=>'00',10=>'10',20=>'20',30=>'30',40=>'40',50=>'50');
			  foreach($menit as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td> </td>
		      <td><select name="selesai_jam">
			  <option value="$sj" selected="selected">$sjl</option>
EOT;
			  $jam = array(1=>'01','02','03','04','05','06','07','08','09','10','11',
			  '12','13','14','15','16','17','18','19','20','21','22','23','24');
			  foreach($jam as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td>:</td>
		      <td><select name="selesai_menit">
			  <option value="$sm" selected="selected">$sml</option>
EOT;
			  $menit = array(0=>'00',10=>'10',20=>'20',30=>'30',40=>'40',50=>'50');
			  foreach($menit as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td>
			    <input type="submit" name="submit" value="+" title="Update" class="update"/>
			    <input type="hidden" name="jamkul" value="$jk"/>
			    <input type="hidden" name="idj" value="$idj"/>
			    <input type="hidden" name="submitted" value="TRUE"/>
			  </td>
			  </form>
		    </tr>
EOT;
		  }
		  echo'</table></div>';
		}else{
		  echo"<p class=\"center\" style=\"font-size:13px;\">Belum ada data Jam Perkuliahan</p></div>";
		}
		echo<<<EOT
	    <div class="hr"><hr/></div>
		  
	    <p class="center" style="font-size:12px;">Tambah Data Jam Perkuliahan:<br/></p>
		  
		<div id="checkjam">
          <form action="cekjamx.php" method="post" id="cekjamx">
		  <table>
		    <tr class="exc"><td>Jam ke-</td><td></td><td>Mulai <font style="font-size:10px;">Jam</font></td><td></td><td>Mulai <font style="font-size:10px;">Menit</font></td><td> </td><td>Selesai <font style="font-size:10px;">Jam</font></td><td></td><td>Selesai <font style="font-size:10px;"> &nbsp;Menit </font></td></tr>
		    <tr class="exc">
		      <td><span class="jad ocCJ">
EOT;
			  $minJ = 1;
			  $rj = mysqli_query($dbc, "select MAX(jam_kul) from tabeljammaster where id_fak={$_SESSION['adm_fak']}");
			  if(mysqli_num_rows($rj)>0){
			    list($maxJ) = mysqli_fetch_row($rj);
				$pJ = $minJ + $maxJ;
				echo $pJ;
			  }else{
			    $pJ = $minJ;
				echo $pJ;
			  }
			  /*$jamkul = range(1, 20);
			  foreach($jamkul as $k => $v){
			    echo"<option value=\"$v\">$v</option>";
			  }*/
			  echo<<<EOT
			  </span></td>
			  <td> </td>
		      <td><select name="mulai_jam">
EOT;
			  $jam = array(1=>'01','02','03','04','05','06','07','08','09','10','11',
			  '12','13','14','15','16','17','18','19','20','21','22','23','24');
			  foreach($jam as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td>:</td>
		      <td><select name="mulai_menit">
EOT;
			  $menit = array(0=>'00',10=>'10',20=>'20',30=>'30',40=>'40',50=>'50');
			  foreach($menit as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td> </td>
		      <td><select name="selesai_jam">
EOT;
			  $jam = array(1=>'01','02','03','04','05','06','07','08','09','10','11',
			  '12','13','14','15','16','17','18','19','20','21','22','23','24');
			  foreach($jam as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td>:</td>
		      <td><select name="selesai_menit">
EOT;
			  $menit = array(0=>'00',10=>'10',20=>'20',30=>'30',40=>'40',50=>'50');
			  foreach($menit as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
		    </tr>
	      </table>
          <p class="center"><input type="submit" name="submit" value="+ Tambah"/></p>
	    <input type="hidden" name="jamkul" value="$pJ" class="hidCJ"/>  
          <input type="hidden" name="submitted" value="TRUE"/>
          </form>
	    </div>
EOT;
		
	  }/*elseif(isset($_SESSION['adm_prodi'])){
	  
	    $q = "select jam_kul, mulai_jam, mulai_menit, selesai_jam, selesai_menit from tabeljam where id_fak={$_SESSION['idpf']} and id_TA={$_SESSION['id_ta']} order by jam_kul asc";
		$r = mysqli_query($dbc, $q);
		$qf = "select nama from fakultas where id_fak={$_SESSION['idpf']}";
		$rf = mysqli_query($dbc, $qf);
		list($fak) = mysqli_fetch_row($rf);
		echo"<div class=\"divprodi\"><p class=\"center\"><span class=\"jad\">$fak</span></p>";
		if(mysqli_num_rows($r)>0){		  
		  echo'<table><tr><td>Jam <font style="font-size:10px;">Ke-</font></td><td></td><td>Mulai <font style="font-size:10px;">Jam</font></td><td></td><td>Mulai <font style="font-size:10px;">Menit</font></td><td> </td><td>Selesai <font style="font-size:10px;">Jam</font></td><td></td><td>Selesai <font style="font-size:10px;">Menit</font></td></tr>';
		  while(list($jk, $mj, $mm, $sj, $sm) = mysqli_fetch_row($r)){
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
		  echo'</table><br/><br/>'; 
		}else{
		  echo'Belum ada Jam Perkuliahan<br/><br/>';
		}
		echo'</div>';
	  }*/

	  echo'
	    </div>
	  </div>';
	  
	}// end JAM PERKULIAHAN
	?>

	<div id="formwrapper" class="sliderWrapper">
	  <div id="pointer" class="slider exc">Ubah Password</div>
	  <div class="form slide exc">
	    <div id="checkpasswrap">
        <form action="uppass.php" method="post" id="checkpass">
	      <table>
            <tr class="exc"><td class="xfont">Password Lama</td><td>:</td><td class="xfont"><input type="password" name="password" id="password" size="20"/></td></tr>
            <tr class="exc"><td class="xfont">Password Baru</td><td>:</td><td class="xfont"><input type="password" name="newpass1" id="newpass1" size="20"/></td></tr>
			<tr class="exc"><td class="xfont">Ulangi Password</td><td>:</td><td class="xfont"><input type="password" name="newpass2" id="newpass2" size="20"/></td></tr>
	      </table>
          <p class="center"><input type="submit" name="submit" value="+ Ubah"/></p>
          <input type="hidden" name="submitted" value="TRUE"/>
        </form>
		</div>
	  </div>
	</div>
	
	

  </div>
  
</div>

<?php include('../footer.html'); ?>

<div class="clock">
<ul id="clock">
  <li id="sec"></li>
  <li id="hour"></li>
  <li id="min"></li>
</ul>
</div>