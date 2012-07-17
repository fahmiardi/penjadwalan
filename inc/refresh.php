<?php
session_start();
if(!isset($_SESSION['adm_prodi']) and !isset($_SESSION['adm_fak']) and !isset($_SESSION['nod'])){
  header("Location:../index.php");
}else{
  
  require_once('../mysqli_connect.php');
  if(isset($_POST['ref']) and isset($_SESSION['adm_prodi']) and $_POST['ref']=='PD'){
  
		$sql = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['adm_prodi']} order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		$res = mysqli_query($dbc, $sql);
		if(mysqli_num_rows($res)>0){
		
		  $ract = mysqli_query($dbc, "select status from status where id_prodi={$_SESSION['adm_prodi']} and id_TA={$_SESSION['id_ta']}");
		  list($act) = mysqli_fetch_row($ract);
		  
		  $sqlP = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		  join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['adm_prodi']} order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		  $resP = mysqli_query($dbc, $sqlP);
		  
		  echo'<p class="center" style="font-size:12px;">Tetapkan / Hapus Jadwal Penugasan Dosen:</p>
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
			$qh = "select nama from hariaktif where id_hari=$idh and id_fak={$_SESSION['idpf']}";
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
				$qhs = "select id_hari, nama from hariaktif where id_fak={$_SESSION['idpf']} and id_TA={$_SESSION['id_ta']} and id_hari!='$idh' order by id_hari asc";
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
			    $qhs = "select id_hari, nama from hariaktif where id_fak={$_SESSION['idpf']} and id_TA={$_SESSION['id_ta']} order by id_hari asc";
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
		  
		  /*$rf = mysqli_query($dbc, "select f.nama from fakultas as f join prodi as p using(id_fak) where id_prodi={$_SESSION['adm_prodi']}");
		  $rt = mysqli_query($dbc, "select tahun, ajaran from ta where id_ta={$_SESSION['id_ta']}");
		  list($fak) = mysqli_fetch_row($rf);
		  list($tahun, $aj) = mysqli_fetch_row($rt);
		  if($aj==1){$ajaran='Ganjil';}else{$ajaran='Genap';}
		  
		  echo'<div style="display:none"><div id="inline_print" class="form" style="width:900px;">
		  <a class="fprint" style="text-shadow:none;" href="javascript:window.print()" title="Print">P</a><br/><br/>
		  <p class="center" style="font-size:17px;"><b>Jadwal Perkuliahan</font></b><br/>
		  <span style="font-size:15px;">Program Studi '.$_SESSION['prodi'].'</font><br/><span style="font-size:15px;">Fakultas '.$fak.'</span></p><hr/>
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
			$qh = "select nama from hariaktif where id_hari=$idh and id_fak={$_SESSION['idpf']}";
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
		  echo'<br/><span class="jad" style="font-size:12px;">Belum ada data Penugasan Dosen</span>';
		}
		
  }if(isset($_POST['ref']) and isset($_SESSION['adm_fak']) and $_POST['ref']=='PD'){
  
		$sql = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['idpx']} order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		$res = mysqli_query($dbc, $sql);
		if(mysqli_num_rows($res)>0){
		
		  $ract = mysqli_query($dbc, "select status from status where id_prodi={$_SESSION['idpx']} and id_TA={$_SESSION['id_ta']}");
		  list($act) = mysqli_fetch_row($ract);
		  
		  $sqlP = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		  join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['idpx']} order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		  $resP = mysqli_query($dbc, $sqlP);
		  
		  echo'<p class="center" style="font-size:12px;">Tetapkan / Hapus Jadwal Penugasan Dosen:</p>
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
		  
		  /*$rp = mysqli_query($dbc, "select nama from prodi where id_prodi={$_SESSION['idpx']}");
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
		  echo'<br/><span class="jad" style="font-size:12px;">Belum ada data Penugasan Dosen</span>';
		}
		
  }elseif(isset($_POST['ref']) and isset($_SESSION['adm_prodi']) and $_POST['ref']=='kelas'){
    
		$q = "select id_kls, nama, smstr, mhs from kelas where id_TA={$_SESSION['id_ta']} and id_prodi={$_SESSION['adm_prodi']} order by smstr, nama asc";
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
			  if($_GET['aj']==1){
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
		  echo'</div><p class="center"><span class="jad" style="font-size:12px;">Belum ada data Kelas</span></p>';
		}
	
  }elseif(isset($_POST['ref']) and isset($_SESSION['adm_fak']) and $_POST['ref']=='kelas'){
    
		$q = "select id_kls, nama, smstr, mhs from kelas where id_TA={$_SESSION['id_ta']} and id_prodi={$_SESSION['idpx']} order by smstr, nama asc";
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
			  if($_GET['aj']==1){
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
		  echo'</div><p class="center"><span class="jad" style="font-size:12px;">Belum ada data Kelas</span></p>';
		}
	
  }elseif(isset($_POST['ref']) and isset($_SESSION['adm_prodi']) and $_POST['ref']=='dosen'){
  
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
		  echo'</table>';
		}else{
		  echo'<p class="center"><span class="jad" style="font-size:12px;">Belum ada data Dosen</span></p>';
		}
  
  }elseif(isset($_POST['ref']) and isset($_SESSION['adm_fak']) and $_POST['ref']=='dosen'){
  
		$qd = "select nod, nip, nama, username, aes_decrypt(password, 'f1sh6uts'), email, telp from dosen where
		id_prodi={$_SESSION['idpx']} and id_fak={$_SESSION['adm_fak']} and id_univ={$_SESSION['idfu']} order by nama asc";
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
		  echo'</table>';
		}else{
		  echo'<p class="center"><span class="jad" style="font-size:12px;">Belum ada data Dosen</span></p>';
		}
  
  }elseif(isset($_POST['ref']) and isset($_SESSION['adm_prodi']) and $_POST['ref']=='kurikulum'){
  
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
	  
	  echo'</div>';
  
  }elseif(isset($_POST['ref']) and isset($_SESSION['adm_fak']) and $_POST['ref']=='kurikulum'){
  
      echo'<div class="form" style="margin-top:-30px; margin-bottom:10px;" id="krVault">';
	  
	  $q = "select id_krklm, tahun from kurikulum where id_prodi={$_SESSION['idpx']} order by tahun desc";
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
	  
	  echo'</div>';
  
  }elseif(isset($_POST['ref']) and $_POST['ref']=='JK'){
    
		$q = "select id_jam, jam_kul, mulai_jam, mulai_menit, selesai_jam, selesai_menit from tabeljam where id_fak={$_SESSION['adm_fak']} and id_TA={$_SESSION['id_ta']} order by jam_kul asc";
		$r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r) > 0){
		  echo"<p class=\"center\" style=\"font-size:12px;\">Ubah Data Jam Perkuliahan:</span><br/></p>
		  <table id=\"tabEjam\">
		  <tr class=\"exc\"><td></td><td>Jam ke-</td><td></td><td>Mulai <font style=\"font-size:10px;\">Jam</font></td><td></td><td>Mulai <font style=\"font-size:10px;\">Menit</font></td><td> </td><td>Selesai <font style=\"font-size:10px;\">Jam</font></td><td></td><td>Selesai <font style=\"font-size:10px;\">Menit</font></td><td></td></tr>";
		  $rj = mysqli_query($dbc, "select MAX(jam_kul) from tabeljam where id_fak={$_SESSION['adm_fak']} and id_TA={$_SESSION['id_ta']}");
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
			
			echo"<tr><form action=\"upjam.php\" method=\"post\" id=\"upjam".$i++."\">";
			echo'
			  <td class="vpad"><a class="x deljam atc'.$ic++.'" href="hapusjam.php?idj='.$idj.'" title="Hapus"'; if($jk!=$maxJ){echo' style="display:none;"';} echo'>-</a></td>
		      <td><span class="jad">';
			  echo $jk;
			  /*$jamkul = range(1, 20);
			  foreach($jamkul as $k => $v){
			    echo"<option value=\"$v\">$v</option>";
			  }*/
			  echo<<<EOT
			  </select></td>
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
			    <input type="hidden" name="idj" value="$idj"/>
			    <input type="hidden" name="jamkul" value="$jk"/>
			    <input type="hidden" name="submitted" value="TRUE"/>
			  </td>
			  </form>
		    </tr>
EOT;
		  }
		  echo'</table>';
		}else{
		  echo"<p class=\"center\" style=\"font-size:13px;\">Belum ada data Jam Perkuliahan</p>";
		}
	
  }elseif(isset($_POST['ref']) and $_POST['ref']=='JKx'){
    
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
			  <td class="vpad"><a class="x deljamx atc'.$ic++.'" href="hapusjamx.php?idj='.$idj.'" title="Hapus"'; if($jk!=$maxJ){echo' style="display:none;"';} echo'>-</a></td>
		      <td><span class="jad">';
			  echo $jk;
			  /*$jamkul = range(1, 20);
			  foreach($jamkul as $k => $v){
			    echo"<option value=\"$v\">$v</option>";
			  }*/
			  echo<<<EOT
			  </select></td>
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
			    <input type="hidden" name="idj" value="$idj"/>
			    <input type="hidden" name="jamkul" value="$jk"/>
			    <input type="hidden" name="submitted" value="TRUE"/>
			  </td>
			  </form>
		    </tr>
EOT;
		  }
		  echo'</table>';
		}else{
		  echo"<p class=\"center\" style=\"font-size:13px;\">Belum ada data Jam Perkuliahan</p>";
		}
	}elseif(isset($_POST['ref']) and isset($_SESSION['adm_prodi']) and $_POST['ref']=='matkul'){
    
	$idkr = $_SESSION['id_krklm'];
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
	    echo'</table>';
    }else{
      echo'<p class="center"><span class="jad">Kurikulum '.$tahun.'</span><br/><br/><span style="font-size:12px;">Belum ada data Matakuliah</span></p>'; 
    }
  
  }elseif(isset($_POST['ref']) and isset($_SESSION['fak']) and $_POST['ref']=='matkul'){
    
	$idkr = $_SESSION['id_krklm'];
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
	    echo'</table>';
    }else{
      echo'<p class="center"><span class="jad">Kurikulum '.$tahun.'</span><br/><br/><span style="font-size:12px;">Belum ada data Matakuliah</span></p>'; 
    }
  
  }elseif(isset($_POST['ref']) and $_POST['ref']=='lokal'){
  
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
		  echo'</table>';
		}else{
		  echo'<p class="center"><span class="jad" style="font-size:12px;">Belum ada data Lokal</span></p>';
		}
  
  }elseif(isset($_POST['ref']) and $_POST['ref']=='prodi'){
 
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
		  echo'</table>';
		}else{
		  echo"<p class=\"center\"><span class=\"jad\">Belum ada data Program Studi</span></p>";
		}
 
  }elseif(isset($_POST['ref']) and $_POST['ref']=='hari'){
  
		$q = "select id_hari, nama from hariaktif where id_fak={$_SESSION['adm_fak']} and id_TA={$_SESSION['id_ta']} order by id_hari asc";
		$r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r) > 0){
		  echo'<p class="center" style="font-size:12px;">Hapus Hari Perkuliahan:</p><table><tr class="exc">';
		  while(list($idh, $nama)=mysqli_fetch_row($r)){
		    echo<<<EOT
			<td><a class="x delhari" href="hapushari.php?idh=$idh" title="Hapus">-</a><br/><span class="jad" style="line-height:20px;">$nama</span></td>
EOT;
		  }
		  echo'</tr></table>';
		}else{
		  echo"<p class=\"center\"><span class=\"jad\" style=\"font-size:13px;\">Belum ada data Hari Perkuliahan</span></p>";
		} 
  
  }elseif(isset($_POST['ref']) and $_POST['ref']=='JD'){
	  
	  $sql="select tahun from ta where id_TA={$_SESSION['id_ta']} limit 1";
	  $res=mysqli_query($dbc, $sql); 
	  list($tahun)=mysqli_fetch_row($res);
	  if($_SESSION['aj']==1){$ajaran='Ganjil';}else{$ajaran='Genap';}
	  
	  $q = "select j.id_jad, j.id_prodi, j.nod, j.nods, j.id_matkul, j.id_hari, j.paralel, j.sks, j.id_kls, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j
	   join kelas as k using(id_kls) join matkulmaster as m using(id_matkul) where j.id_TA={$_SESSION['id_ta']} and (j.nod={$_SESSION['nod']} or j.nods={$_SESSION['nod']}) order by k.smstr, k.nama, j.id_hari, j.jk_start, m.nama asc";
	  $r = mysqli_query($dbc, $q);
	  if(mysqli_num_rows($r)>0){
	    echo' 
		<p class="center" style="font-size:12px;">Tetapkan Jadwal:</p>
		<div>
		<table id="tabEjadwald"><tr class="exc"><td class="vpad"></td><td>Matakuliah</td><td>SKS</td><td>Split</td><td>Paralel</td><td>Prodi</td><td>Semester</td><td>Kelas</td><td>Hari</td><td>Jam Kuliah</td><td>Lokal</td><td>Kapasitas</td><td></td></tr>';
		$iform =1; $dht=1; $dhtOC=1; $djk=1; $djk=1; $djkOC=1; $dlt=1; $dltOC=1;
		while(list($idjad, $id_p, $nod, $nods, $idm, $idh, $paralel, $split, $idk, $sJam, $mj, $mm, $eJam, $sj, $sm, $idl) = mysqli_fetch_row($r)){
		  $qm = "select nama, sks from matkulmaster where id_matkul=$idm";
		  $rm = mysqli_query($dbc, $qm);
		  $qk = "select nama, smstr from kelas where id_kls=$idk";
		  $rk = mysqli_query($dbc, $qk);
		  $rfu = mysqli_query($dbc, "select f.nama, u.nama from fakultas as f join univ as u using(id_univ) where id_fak={$_SESSION['iddf']}");
		  $rf = mysqli_query($dbc, "select m.id_fak, p.nama from matkulmaster as m join prodi as p using(id_prodi) where m.id_matkul=$idm");
		  $rs = mysqli_query($dbc, "select status from status where id_TA={$_SESSION['id_ta']} and id_prodi=$id_p");
		  $rh = mysqli_query($dbc, "select nama from hariaktif where id_hari=$idh and id_fak={$_SESSION['iddf']}");
		  list($fak, $univ) = mysqli_fetch_row($rfu);
		  list($hari) = mysqli_fetch_row($rh);
		  list($act) = mysqli_fetch_row($rs);
		  list($idf, $prodi) = mysqli_fetch_row($rf);
		  list($matkul, $sks) = mysqli_fetch_row($rm);
		  list($kelas, $smstr) = mysqli_fetch_row($rk);
		  if($paralel!='-'){ $prl = str_replace("."," - ",$paralel); }else{ $prl = $paralel; }
		  
		  echo<<<EOT
		  <tr>
		    <td class="vpad"></td>
		    <td><span class="jad">$matkul</span></td>
			<td><span class="jad">$sks</span></td>
			<td><span class="jad">$split</span></td>
			<td><span class="jad">$prl</span></td>
			<td><span class="jad">$prodi</span></td>
			<td><span class="jad">$smstr</span></td>
			<td><span class="jad">$kelas</span></td>
EOT;
		  echo"<form action=\"uppd_d.php\" method=\"post\" id=\"uppd_d".$iform++."\" "; if($act==0){ echo'disabled="disabled"'; } echo' >
			<td>';
			if($idh!=0){
			  echo"<select name=\"idh\" class=\"dHariTrig".$dht++."\" "; if($act==0){ echo'disabled="disabled"'; } echo' >';
			  if($nods!=0){  
				echo"<option value=\"$split-$idh-$nod-$nods-$idm-$idk-$idjad\">$hari</option>";
			  }else{
				echo"<option value=\"$split-$idh-$nod-$idm-$idk-$idjad\">$hari</option>";
			  }
			  $qhs = "select id_hari, nama from hariaktif where id_fak=$idf and id_TA={$_SESSION['id_ta']} and id_hari!=$idh order by id_hari asc";
			  $rhs = mysqli_query($dbc, $qhs);
			  while(list($idhs, $haris) = mysqli_fetch_row($rhs)){
				if($nods!=0){
			      echo"<option value=\"$split-$idhs-$nod-$nods-$idm-$idk-$idjad\">$haris</option>";
				}else{
				  echo"<option value=\"$split-$idhs-$nod-$idm-$idk-$idjad\">$haris</option>";
				}
			  }
			  echo'</select>';
			}else{
			  echo"<select name=\"idh\" class=\"dHariTrig".$dht++."\" "; if($act==0){ echo'disabled="disabled"'; } echo' >
			  <option value="-">-</option>';
			  $qhs = "select id_hari, nama from hariaktif where id_fak=$idf and id_TA={$_SESSION['id_ta']} order by id_hari asc";
			  $rhs = mysqli_query($dbc, $qhs);
			  while(list($idhs, $haris) = mysqli_fetch_row($rhs)){
			    if($nods!=0){
			      echo"<option value=\"$split-$idhs-$nod-$nods-$idm-$idk-$idjad\">$haris</option>";
				}else{
				  echo"<option value=\"$split-$idhs-$nod-$idm-$idk-$idjad\">$haris</option>";
				}
			  }
			  echo'</select>';
			}
		  echo"</td>
			<td>";
			if($sJam!=0){
			  echo"<select name=\"jk\" class=\"dJkTrig".$djk++."\" id=\"dHariTrig".$dhtOC++."\" "; if($act==0){ echo'disabled="disabled"'; } echo' >';
			  if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
			  if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
			  if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
			  if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
			  echo'<option value="'.$sJam.'-'.$eJam.'-'.$idhs.'-'.$mj.'-'.$mm.'-'.$sj.'-'.$sm.'">'.$sJam.' - '.$eJam.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</option>
			  </select>';
			}else{
			  echo"<select name=\"jk\" class=\"dJkTrig".$djk++."\" id=\"dHariTrig".$dhtOC++."\" "; if($act==0){ echo'disabled="disabled"'; } echo' >';
			  echo'<option value="-">-</option>
			  </select>';
			}
		  echo"</td>
			<td>";
			if($idl!=0){
			  echo"<select name=\"idl\" class=\"dLokalTrig".$dlt++."\" id=\"dJkTrig".$djkOC++."\" "; if($act==0){ echo'disabled="disabled"'; } echo' >';
			  $ql = "select nama, muat from lokal where id_lkl=$idl";
			  $rl = mysqli_query($dbc, $ql);
			  list($lokal, $muat) = mysqli_fetch_row($rl);
			  echo"<option value=\"$idl\">$lokal</option>
			  </select>";
			}else{
			  echo"<select name=\"idl\" class=\"dLokalTrig".$dlt++."\" id=\"dJkTrig".$djkOC++."\" "; if($act==0){ echo'disabled="disabled"'; } echo' >';
			  echo'<option value="-">-</option>
			  </select>';
			}
		  echo"</td>
		    <td>";
			if($idl!=0){
			  echo"<span class=\"jad countKaps\"><span class=\"dLokalTrig".$dltOC++."\">$muat</span></span>";
			}else{
			  echo"<span class=\"jad countKaps\"><span class=\"dLokalTrig".$dltOC++."\">-</span></span>";
			}
		  echo"</td>
			<td>
			  <input type=\"submit\" name=\"submit\" value=\"+\" title=\"Update\" class=\"update "; if($act==0){ echo'none'; } echo"\" />
			  <input type=\"hidden\" name=\"idjad\" value=\"$idjad\"/>
			  <input type=\"hidden\" name=\"submitted\" value=\"TRUE\"/>
			  </form>
			</td>
		  </tr>";
		  echo'<tr class="exc">';
		  if(($nod!=0)and($nod!=$_SESSION['nod'])){
		    $qn = "select nama from dosen where nod=$nod";
			$rn = mysqli_query($dbc, $qn);
			list($dosen) = mysqli_fetch_row($rn);
			echo"
			<td class=\"tpad\"><span class=\"sub\"><font color=\"#e01515\">tim</font></span></td>
			<td class=\"tpad\">$dosen</td>";
		  }elseif(($nods!=0)and($nods!=$_SESSION['nod'])){
		    $qn = "select nama from dosen where nod=$nods";
			$rn = mysqli_query($dbc, $qn);
			list($dosen) = mysqli_fetch_row($rn);
			echo"
			<td class=\"tpad\"><span class=\"sub\"><font color=\"#e01515\">tim</font></span></td>
			<td class=\"tpad\">$dosen</td>";
		  }
		  echo'</tr>';
		}
		echo'</table></div>';
		
		//----------------------------------------------------------------------------------------------------------------------------------------------------------------------
		
		/*echo'<div style="display:none;"><div id="inline_print">
		  <div class="form" style="padding:20px 20px; margin-bottom:50px;" id="formprint">
		      <a class="fprint" style="text-shadow:none;" href="javascript:window.print()" title="Print">P</a>
		      <p class="center" style="font-size:17px;"><b>SK Mengajar Dosen</font></b><br/>
			<span style="font-size:15px;">Fakultas '.$fak.'<br/>Tahun Akademik '.$tahun.' - Semester '.$ajaran.'</font><br/><span style="font-size:13px;">'.$univ.'</span></p><hr/>
			<p style="text-align:right; padding:0 80px; font-size:14px;"><span style="float:left;">'.$_SESSION['dosen'].'</span>NIP: '.$_SESSION['nip'].'</p>
			<p class="center" style="font-size:12px;">Jadwal Mengajar:</p>
			<table border="1" border-color="#424242" cellspacing="0">
			<tr><td class="vpad">Matakuliah</td><td>SKS</td><td>Split</td><td>Prodi</td><td>Semester</td><td>Kelas</td><td>Hari</td><td>Jam Kuliah</td><td>Lokal</td><td>Tim</td></tr>';		  
			
		$qprint = "select j.id_jad, j.id_prodi, j.nod, j.nods, j.id_matkul, j.id_hari, j.paralel, j.sks, j.id_kls, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j
	       join kelas as k using(id_kls) join matkulmaster as m using(id_matkul) where j.id_TA={$_SESSION['id_ta']} and (j.nod={$_SESSION['nod']} or j.nods={$_SESSION['nod']}) order by order by k.smstr, k.nama, j.id_hari, j.jk_start, m.nama asc"; //
	    $rprint = mysqli_query($dbc, $q);
		$tSKS = 0;
		while(list($idjad, $id_p, $nod, $nods, $idm, $idh, $paralel, $split, $idk, $sJam, $mj, $mm, $eJam, $sj, $sm, $idl) = mysqli_fetch_row($rprint)){
		  $tSKS += $split;
		  $qm = "select nama, sks from matkulmaster where id_matkul=$idm";
		  $rm = mysqli_query($dbc, $qm);
		  $qk = "select nama, smstr from kelas where id_kls=$idk";
		  $rk = mysqli_query($dbc, $qk);
		  $rfu = mysqli_query($dbc, "select f.nama, u.nama from fakultas as f join univ as u using(id_univ) where id_fak={$_SESSION['iddf']}");
		  $rf = mysqli_query($dbc, "select m.id_fak, p.nama from matkulmaster as m join prodi as p using(id_prodi) where m.id_matkul=$idm");
		  $rs = mysqli_query($dbc, "select status from status where id_TA={$_SESSION['id_ta']} and id_prodi=$id_p");
		  $rh = mysqli_query($dbc, "select nama from hariaktif where id_hari=$idh and id_fak={$_SESSION['iddf']}");
		  $rl = mysqli_query($dbc, "select nama from lokal where id_lkl=$idl");
		  list($lokal) = mysqli_fetch_row($rl);
		  list($fak, $univ) = mysqli_fetch_row($rfu);
		  list($day) = mysqli_fetch_row($rh);
		  list($act) = mysqli_fetch_row($rs);
		  list($idf, $prodi) = mysqli_fetch_row($rf);
		  list($matkul, $sks) = mysqli_fetch_row($rm);
		  list($kelas, $smstr) = mysqli_fetch_row($rk);
		  
		  echo"<tr>
		       <td class=\"vpad\">$matkul</td>
		       <td>$sks</td>
			 <td>$split</td>
			 <td>$prodi</td>
			 <td>$smstr</td>
			 <td>$kelas</td>
			 <td>"; if($idh!=0){ echo $day; }else{ echo'-'; } echo'</td>
			 <td>';
		  if($sJam!=0){
		    if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
			if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
			if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
			if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
			echo"$sJam - $eJam / $mjl:$mml - $sjl:$sml";
		  }else{
		    echo'-';
		  }
		  echo'</td><td>';
		  if($idl!=0){
		    echo"$lokal";
		  }else{
		    echo'-'; 
		  }
		  echo'</td><td>';
		  if(($nod!=0)and($nod!=$_SESSION['nod'])){
			echo"tim";
		  }elseif(($nods!=0)and($nods!=$_SESSION['nod'])){
			echo"tim";
		  }
		  echo'</td></tr>';
		}
		echo'</table>
		  <p class="center" style="font-size:13px;"><br/>Total Beban SKS Mengajar Dosen:&nbsp; <b>'.$tSKS.' SKS</b></p>
		  </div>
		  
		</div></div>'; */
	  }else{
	    echo'<br/><span class="jad" style="font-size:12px;">Belum ada data Penugasan Jadwal</span>';
	  } 
  
  }elseif(isset($_POST['ref']) and $_POST['ref']=='DF'){
  
		$r = mysqli_query($dbc, "select id_lkl, nama from lokal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} order by nama asc");
		while(list($idl, $lokal)=mysqli_fetch_row($r)){
		
		  echo'<br/>
		  <table style="padding:10px 10px; border-radius:4px; background:transparent; box-shadow:0px 0px 3px #c0c0c0;">
		    <tr class="exc"><td style="padding:10px 5px;"><span class="jad" style="background:white;">'.$lokal.'</td></span></td>';
			
		  $rjs = mysqli_query($dbc, "select jam_kul from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} order by jam_kul asc");
		  while(list($jk)=mysqli_fetch_row($rjs)){
		    echo'<td>'.$jk.'</td>';
		  }
		  
		  echo'</tr>';
		  
		  $rh = mysqli_query($dbc, "select id_hari, nama from hariaktif where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} order by id_hari asc");
		  while(list($idh, $hari)=mysqli_fetch_row($rh)){
		    echo'
			<tr>
			  <td style="padding:10px 5px;">'.$hari.'</td>';
			$rjh = mysqli_query($dbc, "select jam_kul from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} order by jam_kul asc");
			while(list($jkh)=mysqli_fetch_row($rjh)){
			  $rjad = mysqli_query($dbc, "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} and id_hari=$idh and id_lkl=$idl and ($jkh between jk_start and jk_end)");
			  if(mysqli_num_rows($rjad)>0){
			    echo'<td><span class="jad" style="background:white;">x</span></td>';
			  }else{
			    echo'<td><span class="jad" style="background:white;">&nbsp;</span></td>';
			  }
		    }
			echo'
			</tr>';
		  } 
		  
		  echo'
		  </table><br/>';
		}
  
  }elseif(isset($_POST['ref']) and $_POST['ref']=='DP'){
  
		$r = mysqli_query($dbc, "select id_lkl, nama from lokal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} order by nama asc");
		while(list($idl, $lokal)=mysqli_fetch_row($r)){
		
		  echo'<br/>
		  <table style="padding:10px 10px; border-radius:4px; background:transparent; box-shadow:0px 0px 2px #bbbbbb;">
		    <tr class="exc"><td style="padding:10px 5px;"><span class="jad" style="background:white;">'.$lokal.'</td></span></td>';
			
		  $rjs = mysqli_query($dbc, "select jam_kul from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} order by jam_kul asc");
		  while(list($jk)=mysqli_fetch_row($rjs)){
		    echo'<td>'.$jk.'</td>';
		  }
		  
		  echo'</tr>';
		  
		  $rh = mysqli_query($dbc, "select id_hari, nama from hariaktif where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} order by id_hari asc");
		  while(list($idh, $hari)=mysqli_fetch_row($rh)){
		    echo'
			<tr>
			  <td style="padding:10px 5px;">'.$hari.'</td>';
			$rjh = mysqli_query($dbc, "select jam_kul from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} order by jam_kul asc");
			while(list($jkh)=mysqli_fetch_row($rjh)){
			  $rjad = mysqli_query($dbc, "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} and id_hari=$idh and id_lkl=$idl and ($jkh between jk_start and jk_end)");
			  if(mysqli_num_rows($rjad)>0){
			    echo'<td><span class="jad" style="background:white;">x</span></td>';
			  }else{
			    echo'<td><span class="jad" style="background:white;">&nbsp;</span></td>';
			  }
		    }
			echo'
			</tr>';
		  } 
		  
		  echo'
		  </table><br/>';
		} 
  
  }elseif(isset($_POST['ref']) and isset($_SESSION['adm_prodi']) and $_POST['ref']=='mkmaster'){
  
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
  
  }elseif(isset($_POST['ref']) and isset($_SESSION['adm_fak']) and $_POST['ref']=='mkmaster'){
  
        $qd = "select id_matkul, kd_matkul, nama, sks from matkulmaster where
		id_prodi={$_SESSION['idpx']} and id_fak={$_SESSION['adm_fak']} and id_univ={$_SESSION['idfu']} order by kd_matkul asc";
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
  
  }
  
}
?>