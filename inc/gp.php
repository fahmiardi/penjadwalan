<?php
  session_start();
  if(!isset($_SESSION['adm_fak']) or !isset($_POST['idp'])){
    header('Location:../index.php');
  }else{
    
	require("../mysqli_connect.php");
	
	list($idp, $sec) = explode('/', $_POST['idp']);
	if(!isset($_SESSION['idpx'])){
	  $_SESSION['idpx']=$idp;  
	}else{
	  unset($_SESSION['idpx']);
	  $_SESSION['idpx']=$idp; 
	}
	$idpjk = $idp;
	$idpx = $idp;
	
	
	if($sec=='ds'){
	
	  $r = mysqli_query($dbc, "select nama from prodi where id_prodi=$idp");
	  list($prodi)=mysqli_fetch_row($r); 
	
	  echo<<<EOT
	  <p id="right"><a class="agp" href="idp=$idp"><</a> &nbsp;&nbsp;<span class="jad">$prodi</span></p>  
EOT;
	  
	  echo'<div style="position:relative; display:table;">
		  <div id="refDosen" class="jpd" style="font-size:12px; cursor:pointer;  display:inline-block; text-shadow:none; border-radius:15px; padding:3px 5px; padding-top:4px;" title="refresh"><img src="../img/refresh.png" height="16px" width="16px"/></div>
		</div>
		<div id="ocRDosen">';
		
	  $qd = "select nod, nip, nama, username, aes_decrypt(password, 'f1sh6uts'), email, telp from dosen where
		id_prodi=$idpx and id_fak={$_SESSION['adm_fak']} and id_univ={$_SESSION['idfu']} order by nama asc";
		$rd = mysqli_query($dbc, $qd);
		if(mysqli_num_rows($rd)>0){
		  echo<<<EOT
		  <p class="center" style="font-size:12px;">Ubah Data Dosen:</p>

		  <table id="tabXupds">
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
				<input type="hidden" name="idu" value="{$_SESSION['idfu']}"/>
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
		  <input type="hidden" name="idp" value="$idpx"/>
		  <input type="hidden" name="idf" value="{$_SESSION['adm_fak']}"/>
		  <input type="hidden" name="idu" value="{$_SESSION['idfu']}"/>
          <input type="hidden" name="submitted" value="TRUE"/>
          </form>
		  </div>
		  
	    
EOT;
	  
    }elseif($sec=='mk'){
	
	  $r = mysqli_query($dbc, "select nama from prodi where id_prodi=$idp");
	  list($prodi)=mysqli_fetch_row($r); 
	
	  echo<<<EOT
	  <p id="right"><a class="agp" href="idp=$idp"><</a> &nbsp;&nbsp;<span class="jad">$prodi</span></p>
EOT;

	  echo'<div style="position:relative; display:table;">
		  <div id="refMkmaster" class="jpd" style="font-size:12px; cursor:pointer;  display:inline-block; text-shadow:none; border-radius:15px; padding:3px 5px; padding-top:4px;" title="refresh"><img src="../img/refresh.png" height="16px" width="16px"/></div>
		</div>
		<div id="ocRMkmaster">';
	
		$qd = "select id_matkul, kd_matkul, nama, sks from matkulmaster where
		id_prodi=$idp and id_fak={$_SESSION['adm_fak']} and id_univ={$_SESSION['idfu']} order by kd_matkul asc";
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
				<input type="hidden" name="idu" value="{$_SESSION['idfu']}"/>
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
		  <input type="hidden" name="idp" value="$idp"/>
		  <input type="hidden" name="idf" value="{$_SESSION['adm_fak']}"/>
		  <input type="hidden" name="idu" value="{$_SESSION['idfu']}"/>
          <input type="hidden" name="submitted" value="TRUE"/>
          </form>
		  </div>
EOT;

    }elseif($sec=='kr'){
	
	  $r = mysqli_query($dbc, "select nama from prodi where id_prodi=$idp");
	  list($prodi)=mysqli_fetch_row($r); 
	
	  echo<<<EOT
	  <p id="right"><a class="agp" href="idp=$idp" id="refXkr"><</a> &nbsp;&nbsp;<span class="jad">$prodi</span></p>
EOT;
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
	  
	  $q = "select id_krklm, tahun from kurikulum where id_prodi=$idp order by tahun desc";
	  $r = mysqli_query($dbc, $q);
	  if(mysqli_num_rows($r)>0){
		echo'<div id="kurikulumSet"></div>
		<span style="font-size:12px;">Ubah / Hapus Data Kurikulum:</span><br/><br/>
		<table>';
		
		while(list($idkr, $thn)=mysqli_fetch_row($r)){
		  echo<<<EOT
		   <tr>
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

    }elseif($sec=='jk'){
	
	  $r = mysqli_query($dbc, "select nama from prodi where id_prodi=$idpjk");
	  list($prodijk)=mysqli_fetch_row($r); 
	
	  echo<<<EOT
	  <p id="right"><a class="agp" href="idp=$idpjk"><</a> &nbsp;&nbsp;<span class="jad">$prodijk</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
EOT;
	  	  
	  echo'<div style="position:relative; left:170px; display:table;">
		  <a class="fprint" id="printPJ" style="text-shadow:none;" href="pdf.php?aj='.$_SESSION['aj'].'" target="_blank" title="Print to PDF">pdf</a>
		  <a class="fprint" id="printPJ" style="text-shadow:none;" href="xls.php?aj='.$_SESSION['aj'].'" title="Export to Excel" target="_blank">xls</a>&nbsp;&nbsp;
		  <div id="refPD" class="jpd" style="font-size:12px; cursor:pointer;  display:inline-block; text-shadow:none; border-radius:15px; padding:3px 5px; padding-top:4px;" title="refresh"><img src="../img/refresh.png" height="16px" width="16px"/></div>
		  <div style="font-size:12px; display:inline-block; margin-left:2px;">
		    <div id="searchPD" class="jpd" style="font-size:12px; cursor:pointer;  display:inline-block; text-shadow:none; border-radius:15px; padding:3px 5px; padding-top:4px;" title="cari jadwal"><img src="../img/search.png" height="16px" width="16px"/></div>
		    <input type="text" name="cs" size="20" id="search" value="cari jadwal..." style="text-align:left; font-style:italic; padding-left:5px; color:#909090;"/>
		  </div>
		</div>
		<div id="pdosen">';
		
	    $sql = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi=$idpjk order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		$res = mysqli_query($dbc, $sql);
		if(mysqli_num_rows($res)>0){
		
		  $ract = mysqli_query($dbc, "select status from status where id_prodi=$idpjk and id_TA={$_SESSION['id_ta']}");
		  list($act) = mysqli_fetch_row($ract);
		  
		  $sqlP = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		  join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi=$idpjk order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		  $resP = mysqli_query($dbc, $sqlP);
		  
		  echo'<p class="center" style="font-size:12px;">Tetapkan / Hapus Jadwal Kuliah:</p>
		  <table id="tabEjadwal">
		    <tr class="exc"><td></td><td>Dosen</td><td>Matakuliah</td><td>SKS</td><td>Split</td><td>Paralel</td><td>Semester</td><td>Kelas</td><td>Hari</td><td>Jam Kuliah</td><td>Lokal</td><td></td></tr>';
		  $idform=1; $ht=1; $htOC=1; $jk=1; $lk=1;
		  while(list($idjad, $nod, $nods, $idm, $split, $paralel, $idk, $idh, $sJam, $mj, $mm, $eJam, $sj, $sm, $idl) = mysqli_fetch_row($res)){
		    $qd = "select nama from dosen where nod=$nod";
			$rd = mysqli_query($dbc, $qd);
			list($dosen) = mysqli_fetch_row($rd);
			$qm = "select nama, sks from matkulmaster where id_matkul=$idm";
			$rm = mysqli_query($dbc, $qm);
			list($matkul, $sks) = mysqli_fetch_row($rm);
			$qk = "select smstr, nama from kelas where id_kls=$idk";
			$rk = mysqli_query($dbc, $qk);
			list($smstr, $kelas) = mysqli_fetch_row($rk);
			$qh = "select nama from hariaktif where id_hari=$idh and id_fak={$_SESSION['adm_fak']}";
			$rh = mysqli_query($dbc, $qh);
			list($hari) = mysqli_fetch_row($rh);
			if($paralel!='-'){ $prl = str_replace("."," - ",$paralel); }else{ $prl = $paralel; }
		    echo'
			<tr>
			  <td '; if($nods==0){ echo'class="vpad"'; } 
			  echo"><a class=\"x deljadwal "; if($act==1){ echo'none'; } echo"\" href=\"hapusjadwal.php?idjad=$idjad\" title=\"Hapus\">-</a>"; 
			  echo<<<EOT
			  </td>
			  <td><span class="jad">$dosen</span></td>
			  <td><span class="jad">$matkul</span></td>
			  <td><span class="jad">$sks</span></td>
			  <td><span class="jad">$split</span></td>
			  <td><span class="jad">$prl</span></td>
			  <td><span class="jad">$smstr</span></td>
			  <td><span class="jad">$kelas</span></td>
EOT;
			  echo"
			  <form action=\"uppd.php\" method=\"post\" id=\"uppd".$idform++."\">
			  <td><select name=\"idh\" dis=\"prodi\" class=\"hariTrig".$ht++."\" "; if($act==1){ echo' disabled=disabled'; } echo'>';
			  
			  if($idh!=0){
			    if($nods!=0){  
				  echo"<option value=\"$split-$idh-$nod-$nods-$idk-$idjad\">$hari</option>";
				}else{
				  echo"<option value=\"$split-$idh-$nod-$idk-$idjad\">$hari</option>";
				}
				$qhs = "select id_hari, nama from hariaktif where id_fak={$_SESSION['adm_fak']} and id_TA={$_SESSION['id_ta']} and id_hari!='$idh' order by id_hari asc";
			    $rhs = mysqli_query($dbc, $qhs);
			    while(list($idhs, $haris) = mysqli_fetch_row($rhs)){
				  if($nods!=0){
			        echo"<option value=\"$split-$idhs-$nod-$nods-$idk-$idjad\">$haris</option>";
				  }else{
				    echo"<option value=\"$split-$idhs-$nod-$idk-$idjad\">$haris</option>";
				  }
			    }
			  }else{
			    echo"<option value=\"-\">-</option>";
			    $qhs = "select id_hari, nama from hariaktif where id_fak={$_SESSION['adm_fak']} and id_TA={$_SESSION['id_ta']} order by id_hari asc";
			    $rhs = mysqli_query($dbc, $qhs);
			    while(list($idhs, $haris) = mysqli_fetch_row($rhs)){
			      if($nods!=0){
			        echo"<option value=\"$split-$idhs-$nod-$nods-$idk-$idjad\">$haris</option>";
				  }else{
				    echo"<option value=\"$split-$idhs-$nod-$idk-$idjad\">$haris</option>";
				  }
			    }
			  }
			  echo'</select></td>';
			  echo"
			  <td><select name=\"jk\" dis=\"prodi\" class=\"jkTrig".$jk++."\" id=\"hariTrig".$htOC++."\" "; if($act==1){ echo' disabled=disabled'; } echo'>';
			  if($sJam!=0){
				if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
			    if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
			    if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
			    if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
				echo'<option value="'.$sJam.'-'.$eJam.'-'.$idhs.'-'.$mj.'-'.$mm.'-'.$sj.'-'.$sm.'">'.$sJam.' - '.$eJam.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</option>';
			  }else{
			    echo'<option value="-">-</option>';
			  }
			  echo"</select></td>
			  <td><select name=\"idl\" dis=\"prodi\" class=\"lokalTrig\" id=\"jkTrig".$lk++."\" "; if($act==1){ echo' disabled=disabled'; } echo'>';
			  if($idl!=0){
			    $ql = "select nama from lokal where id_lkl=$idl";
				$rl = mysqli_query($dbc, $ql);
				list($lokal) = mysqli_fetch_row($rl);
				echo"<option value=\"$idl\">$lokal</option>";
			  }else{
				echo'<option value="-">-</option>';
			  }
			  echo"</select></td>
			  <td>
			    <input type=\"submit\" name=\"submit\" value=\"+\" title=\"Update\" dis=\"prodi\" class=\"update "; if($act==1){ echo'none'; } echo"\" />
				<input type=\"hidden\" name=\"idjad\" value=\"$idjad\"/>
				<input type=\"hidden\" name=\"submitted\" value=\"TRUE\"/>
				</form>
			  </td>
			</tr>";

		    if($nods!=0){
			  $qds = "select nama from dosen where nod=$nods";
			  $rds = mysqli_query($dbc, $qds);
			  list($subdosen) = mysqli_fetch_row($rds);
			  echo<<<EOT
			  <tr class="exc">
			    <td class="tpad"><span class="sub"><font color="#e01515">tim</font></span></td>
			    <td class="tpad">$subdosen</td>
			  </tr>
EOT;
			}
			
		  }
		  echo'
		  </table>';
		 
		  if($act==0){ 
		    echo'<div style="position:relative; margin-top:20px;">
		      <form method="post" action="act.php" id="status">
		        <input type="hidden" name="act" value="1"/>
		        <input type="hidden" name="submitted" value="TRUE"/>
		        <input type="submit" name="submit" value="Jadwal Non-Aktif" class="actSubmit"/>
		      </form>
		    </div>'; 
		  }else{ 
		    echo'<div style="position:relative; margin-top:30px;">
		      <form method="post" action="act.php" id="status">
		        <input type="hidden" name="act" value="0"/>
		        <input type="hidden" name="submitted" value="TRUE"/>
		        <input type="submit" name="submit" value="Jadwal Aktif" class="deactSubmit"/>
		      </form>
		    </div>'; 
		  }
		  
		  /*$rp = mysqli_query($dbc, "select nama from prodi where id_prodi=$idpx");
	      $rt = mysqli_query($dbc, "select tahun, ajaran from ta where id_ta={$_SESSION['id_ta']}");
	      list($prodi) = mysqli_fetch_row($rp);
		  list($tahun, $aj) = mysqli_fetch_row($rt);
		  if($aj==1){$ajaran='Ganjil';}else{$ajaran='Genap';} 
		  
		  echo'<div style="display:none"><div id="inline_print" class="form" style="width:900px;">
		  <a class="fprint" style="text-shadow:none;" href="javascript:window.print()" title="Print">P</a><br/><br/>
		  <p class="center" style="font-size:17px;"><b>Jadwal Perkuliahan</font></b><br/>
		  <span style="font-size:15px;">Program Studi '.$prodi.'</font><br/><span style="font-size:15px;">Fakultas '.$_SESSION['fak'].'</span></p><hr/>
		  <p style="text-align:center; padding:0 80px; font-size:14px;">Tahun Akademik '.$tahun.' - '.$ajaran.'</p><br/>
		  <table id="tabEjadwal" border="1" cellpadding="0" cellspacing="0">
		    <tr><td>Dosen</td><td>Matakuliah</td><td>SKS</td><td>Split</td><td>Paralel</td><td>Semester</td><td>Kelas</td><td>Hari</td><td>Jam Kuliah</td><td>Lokal</td></tr>';
		  
		  while(list($idjad, $nod, $nods, $idm, $split, $paralel, $idk, $idh, $sJam, $mj, $mm, $eJam, $sj, $sm, $idl) = mysqli_fetch_row($resP)){
		    $qd = "select nama from dosen where nod=$nod";
			$rd = mysqli_query($dbc, $qd);
			list($dosen) = mysqli_fetch_row($rd);
			$qm = "select nama, sks from matkulmaster where id_matkul=$idm";
			$rm = mysqli_query($dbc, $qm);
			list($matkul, $sks) = mysqli_fetch_row($rm);
			$qk = "select smstr, nama from kelas where id_kls=$idk";
			$rk = mysqli_query($dbc, $qk);
			list($smstr, $kelas) = mysqli_fetch_row($rk);
			$qh = "select nama from hariaktif where id_hari=$idh and id_fak={$_SESSION['adm_fak']}";
			$rh = mysqli_query($dbc, $qh);
			list($hari) = mysqli_fetch_row($rh);
			if($paralel!='-'){ $prl = str_replace("."," - ",$paralel); }else{ $prl = $paralel; }
			  echo<<<EOT
			  <tr>
			    <td>$dosen</td>
			    <td>$matkul</td>
			    <td>$sks</td>
			    <td>$split</td>
			    <td>$prl</td>
			    <td>$smstr</td>
			    <td>$kelas</td>
EOT;
			  if($idh!=0){echo"<td>$hari</td>";}else{echo"<td>-</td>";}
			  if($sJam!=0){
				if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
			    if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
			    if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
			    if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
				echo'<td>'.$sJam.' - '.$eJam.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</td>';
			  }else{
			    echo'<td>-</td>';
			  }
			  if($idl!=0){
			    $ql = "select nama from lokal where id_lkl=$idl";
				$rl = mysqli_query($dbc, $ql);
				list($lokal) = mysqli_fetch_row($rl);
				echo"<td>$lokal</td>";
			  }else{
				echo'<td>-</td>';
			  }
			  echo'
			  </tr>';
		  }
		  echo'</table></div></div>';*/echo'</div>';
		}else{
		  echo'<br/><span class="jad" style="font-size:12px;">Belum ada data Jadwal Kuliah</span></div>';
		}
	  
		echo<<<EOT
		<div class="hr"><hr/></div>
		
		<p class="center" style="font-size:12px;">Tambah Data Jadwal Kuliah:</p>
		
		<div id="checkpd">
		  <form action="cekpd.php" method="post" id="cekpd">
		  <table>
		    <tr class="exc"><td class="tim">Tim</td><td>Prodi</td><td>Dosen</td><td>Kurikulum</td><td>Semester</td><td>Matakuliah</td><td>SKS</td><td class="paralel">Pararel</td><td>Kelas</td></tr>
			<tr class="exc">
EOT;
			  echo"
			  <td><input type=\"checkbox\" name=\"tim\" value=\"1\" class=\"timTrig\"/></td>
			  <td><select name=\"idp\" class=\"prodiTrig\">
			    <option value=\"$idpjk\" selected=\"selected\">$prodijk</option>";
			  $qp = "select id_prodi, nama from prodi where id_univ={$_SESSION['idfu']} and id_prodi!=$idpjk order by nama asc";
			  $qp = mysqli_query($dbc, $qp);
			  while(list($idp, $prodi) = mysqli_fetch_row($qp)){
			    echo"<option value=\"$idp\">$prodi</option>";
			  }
			  echo'
			  </select></td>
			  <td><select name="nod" class="nodTrig ocDosen">
			    <option value="-" selected="selected">-</option>';
			  $qd = "select nod, nama from dosen where id_prodi=$idpjk order by nama asc";
			  $rd = mysqli_query($dbc, $qd);
			  while(list($nod, $dosen) = mysqli_fetch_row($rd)){
			    echo"<option value=\"$nod\">$dosen</option>";
			  }
			  echo'
			  </select></td>
			  <td><select name="idkr" class="idkrTrig">
			    <option value="-" selected="selected">-</option>';
			  $rkr = mysqli_query($dbc, "select id_krklm, tahun from kurikulum where id_prodi=$idpjk order by tahun desc");
			  while(list($idkr, $thn)=mysqli_fetch_row($rkr)){
			    echo"<option value=\"{$_SESSION['aj']}$idkr\">$thn</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td><select name="smstr" class="smstrTrig ocSmstr">
			  <option value="-" selected="selected">-</option>			  
			  </select></td>
			  <td><select name="idm" class="idmTrig ocMatkul">
			    <option value="-" selected="selected">-</option>
			  </select></td>
			  <td><span class="jad sks"><span class="ocSks">-</span></span></td>
			  <td class="paralel"><select name="paralel" class="ocPrl">
			  </select></td>
			  <td><select name="idk" class="ocKelas">
			    <option value="-">-</option>
			  </select></td>
EOT;
			echo"
			</tr>
			<tr class=\"subdosen exc\">
			  <td></td>
			  <td><select name=\"idp\" class=\"subprodiTrig\">
			    <option value=\"$idpjk\" selected=\"selected\">$prodijk</option>";
			  $qp = "select id_prodi, nama from prodi where id_univ={$_SESSION['idfu']} and id_prodi!=$idpjk order by nama asc";
			  $qp = mysqli_query($dbc, $qp);
			  while(list($idp, $prodi) = mysqli_fetch_row($qp)){
			    echo"<option value=\"$idp\">$prodi</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td><select name="subnod" class="ocSubDosen">
			  <option value="-" selected="selected">-</option>
EOT;
			  $qds = "select nod, nama from dosen where id_prodi=$idpjk order by nama asc";
			  $rds = mysqli_query($dbc, $qds);
			  while(list($subnod, $subdosen) = mysqli_fetch_row($rds)){
			    echo"<option value=\"$subnod\">$subdosen</option>";
			  }
			  echo<<<EOT
			  </select></td>
			</tr>
		  </table>
		  <p class="center"><br/><input type="submit" name="submit" value="+ Tambah" id="subnewdata"/></p>
	      <input type="hidden" name="submitted" value="true"/>
		  </form>
		</div>
EOT;

    }elseif($sec=='kls'){
	  	   
	  $r = mysqli_query($dbc, "select nama from prodi where id_prodi=$idp");
	  list($prodi)=mysqli_fetch_row($r); 
	
	  echo<<<EOT
	  <p id="right"><a class="agp" href="idp=$idp"><</a> &nbsp;&nbsp;<span class="jad">$prodi</span></p>
EOT;
	  
	  echo'<div style="position:relative; display:table;">
		  <div id="refKelas" class="jpd" style="font-size:12px; cursor:pointer;  display:inline-block; text-shadow:none; border-radius:15px; padding:3px 5px; padding-top:4px;" title="refresh"><img src="../img/refresh.png" height="16px" width="16px"/></div>
		</div>
		<div id="ocRKelas">';
		
	  
	  $q = "select id_kls, nama, smstr, mhs from kelas where id_TA={$_SESSION['id_ta']} and id_prodi=$idp order by smstr, nama asc";
		$r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r)>0){
		  echo<<<EOT
		  <p class="center" style="font-size:12px;">Ubah Data Kelas:</p>
		  
		  <table id="tabEkelas">
			<tr class="exc"><td></td><td class="xfont">Semester</td><td class="xfont">Kelas</td><td class="xfont">Mahasiswa</td><td></td></tr>
EOT;
		  $i = 1;
		  while(list($idk, $kelas, $smstr, $mhs)=mysqli_fetch_row($r)){
			echo"
			<tr>
			  <form action=\"upkelas.php\" method=\"post\" id=\"upkelas".$i++."\">";
			  echo<<<EOT
			  <td class="vpad"><a class="x delkelas" href="hapuskelas.php?idk=$idk" title="Hapus">-</a></td>
			  <td><select name="smstr">
			    echo"<option value="$smstr" selected="selected">$smstr</option>";
EOT;
			  if($_SESSION['aj']==1){
				$c = array(1=>1, 3=>3, 5=>5, 7=>7);
				foreach($c as $k){
				  echo"<option value=\"$k\">$k</option>";
				}
			  }else{
				$c = array(2=>2, 4=>4, 6=>6, 8=>8);
				foreach($c as $k){
				  echo"<option value=\"$k\">$k</option>";
				}
			  }
			  echo<<<EOT
			  </select></td>
			  <td><input type="text" name="nama" size="10" value="$kelas"/></td>
			  <td><input type="text" name="mhs" size="2" value="$mhs"/></td>
			  <td>
				<input type="hidden" name="idk" value="$idk"/>
				<input type="submit" name="submit" value="+" title="Update" class="update"/>
				<input type="hidden" name="submitted" value="TRUE"/>
			  </td>
			  </form>
			</tr>			
EOT;
		  }
		  echo'</table></div>';
		}else{
		  echo'<p class="center"><span class="jad" style="font-size:12px;">Belum ada data Kelas</span></p></div>';
		}
		echo<<<EOT
	    <div class="hr"><hr/></div>
		  
	    <p class="center" style="font-size:12px;">Tambah Data Kelas:</p>
		  
		<div id="checkkelas">
          <form action="cekkelas.php" method="post" id="cekkelas">
		  <table>
            <tr class="exc"><td class="xfont">Semester</td><td class="xfont">Kelas</td><td class="xfont">Mahasiswa</td></tr>
		    <tr class="exc">
			  <td>
			    <select name="smstr">
EOT;
				if($_SESSION['aj']==1){
				  $c = array(1=>1, 3=>3, 5=>5, 7=>7);
				  foreach($c as $k){
				    echo"<option value=\"$k\">$k</option>";
				  }
				}else{
				  $c = array(2=>2, 4=>4, 6=>6, 8=>8);
				  foreach($c as $k){
				    echo"<option value=\"$k\">$k</option>";
				  }
				}
				echo<<<EOT
				</select>
			  </td>
		      <td><input type="text" size="10" name="nama"/></td>
			  <td><input type="text" size="2" name="mhs"/></td>
		    </tr>
	      </table>
          <p class="center"><input type="submit" name="submit" value="+ Tambah"/></p>
          <input type="hidden" name="submitted" value="TRUE"/>
          </form>
	    </div>
EOT;

    }
	
  }
?>