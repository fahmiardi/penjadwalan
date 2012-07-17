<?php
session_start();
if((!isset($_SESSION['adm_prodi']) or !isset($_SESSION['id_ta'])) and (!isset($_SESSION['adm_fak']) or !isset($_SESSION['id_ta']))){
  header('Location:../index.php');
}else{

  if(isset($_SESSION['adm_prodi'])){
  
  /*if(isset($_POST['cs']) and preg_match('/^[A-Z]+$/', $_POST['cs'])){
    
	require_once('../mysqli_connect.php');
	$cs = mysqli_real_escape_string($dbc, strip_tags($_POST['cs']));
	$q = "select id_kls from kelas where id_prodi={$_SESSION['adm_prodi']} and id_TA={$_SESSION['id_ta']} and nama='$cs'";
	$r = mysqli_query($dbc, $q);
	if(mysqli_num_rows($r)>0){
	
	  //echo'<p class="center" style="font-size:12px;">Tetapkan / Hapus Jadwal Penugasan Dosen:</p>';
	  
	  $idform=1; $ht=1; $htOC=1; $jk=1; $lk=1;
	  while(list($idkSearch)=mysqli_fetch_row($r)){
	  
	    $sql = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['adm_prodi']} and j.id_kls=$idkSearch order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		$res = mysqli_query($dbc, $sql);
		
		if(mysqli_num_rows($res)>0){
		
		  $ract = mysqli_query($dbc, "select status from status where id_prodi={$_SESSION['adm_prodi']} and id_TA={$_SESSION['id_ta']}");
		  list($act) = mysqli_fetch_row($ract);
		  
		  $sqlP = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		  join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['adm_prodi']} and j.id_kls=$idkSearch order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		  $resP = mysqli_query($dbc, $sqlP);
		  
		  echo'<br/>
		  <table id="tabEjadwal">
		  <tr><td></td><td>Dosen</td><td>Matakuliah</td><td>SKS</td><td>Split</td><td>Paralel</td><td>Semester</td><td>Kelas</td><td>Hari</td><td>Jam Kuliah</td><td>Lokal</td><td></td></tr>';
		  
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
				  echo"<option value=\"$split-$idh-$nod-$nods-$idk\">$hari</option>";
				}else{
				  echo"<option value=\"$split-$idh-$nod-$idk\">$hari</option>";
				}
				$qhs = "select id_hari, nama from hariaktif where id_fak={$_SESSION['idpf']} and id_TA={$_SESSION['id_ta']} and id_hari!='$idh' order by id_hari asc";
			    $rhs = mysqli_query($dbc, $qhs);
			    while(list($idhs, $haris) = mysqli_fetch_row($rhs)){
				  if($nods!=0){
			        echo"<option value=\"$split-$idhs-$nod-$nods-$idk\">$haris</option>";
				  }else{
				    echo"<option value=\"$split-$idhs-$nod-$idk\">$haris</option>";
				  }
			    }
			  }else{
			    echo"<option value=\"-\">-</option>";
			    $qhs = "select id_hari, nama from hariaktif where id_fak={$_SESSION['idpf']} and id_TA={$_SESSION['id_ta']} order by id_hari asc";
			    $rhs = mysqli_query($dbc, $qhs);
			    while(list($idhs, $haris) = mysqli_fetch_row($rhs)){
			      if($nods!=0){
			        echo"<option value=\"$split-$idhs-$nod-$nods-$idk\">$haris</option>";
				  }else{
				    echo"<option value=\"$split-$idhs-$nod-$idk\">$haris</option>";
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
			  <tr>
			    <td class="tpad"><span class="sub"><font color="#e01515">tim</font></span></td>
			    <td class="tpad">$subdosen</td>
			  </tr>
EOT;
			}
			
		  } 
		  echo'
		  </table>';
		  
		  $rf = mysqli_query($dbc, "select f.nama from fakultas as f join prodi as p using(id_fak) where id_prodi={$_SESSION['adm_prodi']}");
		  $rt = mysqli_query($dbc, "select tahun, ajaran from ta where id_ta={$_SESSION['id_ta']}");
		  list($fak) = mysqli_fetch_row($rf);
		  list($tahun, $aj) = mysqli_fetch_row($rt);
		  if($aj==1){$ajaran='Ganjil';}else{$ajaran='Genap';}
		  
		  echo'<div style="display:none"><div id="inline_print" class="form" style="width:900px;">
		  <a class="fprint" style="text-shadow:none;" href="javascript:window.print()" title="Print">P</a><br/><br/>
		  <div style="display:none"><div id="inline_print" class="form" style="width:900px;">
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
		  echo'</table></div></div></div>';
		  
		}//else{
		  //$r = mysqli_query($dbc, "select nama, smstr from kelas where id_kls=$idkSearch");
		  //list($kelas, $smstr) = mysqli_fetch_row($r);
		  //echo'<span class="jad" style="font-size:12px;">Kelas <b>'.$kelas.'</b>, semester <b>'.$smstr.'</b> belum ada jadwal.</span>';
	    //}
		
	  }
	  
	}else{
	  echo'<span class="jad" style="font-size:12px;">Tidak ada data Kelas: <b>'.$cs.'</b></span>';
	}
	
  }else*/if(isset($_POST['cs']) and preg_match('/^[0-9]+$/', $_POST['cs'])){
  
    require_once('../mysqli_connect.php');
	$cs = mysqli_real_escape_string($dbc, strip_tags($_POST['cs']));
	$q = "select id_kls from kelas where id_prodi={$_SESSION['adm_prodi']} and id_TA={$_SESSION['id_ta']} and smstr=$cs";
	$r = mysqli_query($dbc, $q);
	if(mysqli_num_rows($r)>0){
	
	  //echo'<p class="center" style="font-size:12px;">Tetapkan / Hapus Jadwal Penugasan Dosen:</p>';
	  
	  $idform=1; $ht=1; $htOC=1; $jk=1; $lk=1;
	  while(list($idkSearch)=mysqli_fetch_row($r)){
	  
	    $sql = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['adm_prodi']} and j.id_kls=$idkSearch order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		$res = mysqli_query($dbc, $sql);
		
		if(mysqli_num_rows($res)>0){
		
		  $ract = mysqli_query($dbc, "select status from status where id_prodi={$_SESSION['adm_prodi']} and id_TA={$_SESSION['id_ta']}");
		  list($act) = mysqli_fetch_row($ract);
		  
		  $sqlP = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		  join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['adm_prodi']} and j.id_kls=$idkSearch order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		  $resP = mysqli_query($dbc, $sqlP); 
		  
		  echo'<br/>
		  <table id="tabEjadwal">
		  <tr class="exc"><td></td><td>Dosen</td><td>Matakuliah</td><td>SKS</td><td>Split</td><td>Paralel</td><td>Semester</td><td>Kelas</td><td>Hari</td><td>Jam Kuliah</td><td>Lokal</td><td></td></tr>';
		  
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
			        echo"<option value=\"$split-$idhs-$nod-$nods-$idk\">$haris</option>";
				  }else{
				    echo"<option value=\"$split-$idhs-$nod-$idk\">$haris</option>";
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
		  
		  $rf = mysqli_query($dbc, "select f.nama from fakultas as f join prodi as p using(id_fak) where id_prodi={$_SESSION['adm_prodi']}");
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
		    <tr class="exc"><td>Dosen</td><td>Matakuliah</td><td>SKS</td><td>Split</td><td>Paralel</td><td>Semester</td><td>Kelas</td><td>Hari</td><td>Jam Kuliah</td><td>Lokal</td></tr>';
		  
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
		  echo'</table></div></div></div>';
		
		}/*else{
		  $r = mysqli_query($dbc, "select nama, smstr from kelas where id_kls=$idkSearch");
		  list($kelas, $smstr) = mysqli_fetch_row($r);
		  echo'<span class="jad" style="font-size:12px;">Kelas <b>'.$kelas.'</b>, semester <b>'.$smstr.'</b> belum ada jadwal.</span>';
	    }*/
		
	  }
	  
	}else{
	  echo'<span class="jad" style="font-size:12px;">Tidak ada data Semester: <b>'.$cs.'</b></span>';
	}
  
  }elseif(isset($_POST['cs']) and preg_match('/^[0-9]+[A-Za-z]+$/', $_POST['cs'])){
  
    require_once('../mysqli_connect.php');
	$cs1 = mysqli_real_escape_string($dbc, strip_tags(substr($_POST['cs'], 0, 1)));
	$cs2 = mysqli_real_escape_string($dbc, strip_tags(substr($_POST['cs'], 1)));
	$q = "select id_kls from kelas where id_prodi={$_SESSION['adm_prodi']} and id_TA={$_SESSION['id_ta']} and smstr=$cs1 and nama='$cs2'";
	$r = mysqli_query($dbc, $q);
	if(mysqli_num_rows($r)>0){
	
	  //echo'<p class="center" style="font-size:12px;">Tetapkan / Hapus Jadwal Penugasan Dosen:</p>';
	  
	  $idform=1; $ht=1; $htOC=1; $jk=1; $lk=1;
	  while(list($idkSearch)=mysqli_fetch_row($r)){
	  
	    $sql = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['adm_prodi']} and j.id_kls=$idkSearch order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		$res = mysqli_query($dbc, $sql);
		
		if(mysqli_num_rows($res)>0){
		
		  echo'<br/>
		  <table id="tabEjadwal">
		  <tr class="exc"><td></td><td>Dosen</td><td>Matakuliah</td><td>SKS</td><td>Split</td><td>Paralel</td><td>Semester</td><td>Kelas</td><td>Hari</td><td>Jam Kuliah</td><td>Lokal</td><td></td></tr>';
		
		  $ract = mysqli_query($dbc, "select status from status where id_prodi={$_SESSION['adm_prodi']} and id_TA={$_SESSION['id_ta']}");
		  list($act) = mysqli_fetch_row($ract);
		  
		  $sqlP = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		  join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['adm_prodi']} and j.id_kls=$idkSearch order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		  $resP = mysqli_query($dbc, $sqlP);
		  
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
		  echo'<span class="jad" style="font-size:12px;">Kelas <b>'.$cs2.'</b> / semester <b>'.$cs1.'</b> belum ada jadwal.</span>';
	    }
		
	  }
	  
	  
	}else{
	  echo'<span class="jad" style="font-size:12px;">Tidak ada data Kelas <b>'.$cs2.'</b> / Semester <b>'.$cs1.'</b></span>';
	}
  
  }else{
  
    require_once('../mysqli_connect.php');
	$cs = mysqli_real_escape_string($dbc, strip_tags($_POST['cs']));
	$q = "select nod from dosen where id_prodi={$_SESSION['adm_prodi']} and match(nama) against('*$cs*' in boolean mode)";
	$r = mysqli_query($dbc, $q);
	if(mysqli_num_rows($r)>0){
	
	  //echo'<p class="center" style="font-size:12px;">Tetapkan / Hapus Jadwal Penugasan Dosen:</p>';
	  
	  $idform=1; $ht=1; $htOC=1; $jk=1; $lk=1;
	  while(list($nodSearch)=mysqli_fetch_row($r)){
	  
	    $sql = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['adm_prodi']} and (j.nod=$nodSearch or j.nods=$nodSearch) order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		$res = mysqli_query($dbc, $sql);
		
		if(mysqli_num_rows($res)>0){
		
		  $ract = mysqli_query($dbc, "select status from status where id_prodi={$_SESSION['adm_prodi']} and id_TA={$_SESSION['id_ta']}");
		  list($act) = mysqli_fetch_row($ract);
		  
		  $sqlP = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		  join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['adm_prodi']} and (j.nod=$nodSearch or j.nods=$nodSearch) order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		  $resP = mysqli_query($dbc, $sqlP);
		  
		  echo'<br/>
		  <table id="tabEjadwal">
		  <tr class="exc"><td></td><td>Dosen</td><td>Matakuliah</td><td>SKS</td><td>Split</td><td>Paralel</td><td>Semester</td><td>Kelas</td><td>Hari</td><td>Jam Kuliah</td><td>Lokal</td><td></td></tr>';
		  
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
		  
		}/*else{
		  $r = mysqli_query($dbc, "select nama from dosen where nod=$nodSearch");
		  list($dosen) = mysqli_fetch_row($r);
		  echo'<span class="jad" style="font-size:12px;">Belum ada Penugasan Dosen: <b>'.$dosen.'</b></span>';
	    }*/
		
	  }
	  
	}else{
	  echo'<span class="jad" style="font-size:12px;">Tidak ada data Dosen: <b>'.$cs.'</b></span>';
	}
	
  }
  
  }elseif(isset($_SESSION['adm_fak'])){
  
  /*if(isset($_POST['cs']) and preg_match('/^[A-Z]+$/', $_POST['cs'])){
    
	require_once('../mysqli_connect.php');
	$cs = mysqli_real_escape_string($dbc, strip_tags($_POST['cs']));
	$q = "select id_kls from kelas where id_prodi={$_SESSION['idpx']} and id_TA={$_SESSION['id_ta']} and nama='$cs'";
	$r = mysqli_query($dbc, $q);
	if(mysqli_num_rows($r)>0){
	
	  //echo'<p class="center" style="font-size:12px;">Tetapkan / Hapus Jadwal Penugasan Dosen:</p>';
	  
	  $idform=1; $ht=1; $htOC=1; $jk=1; $lk=1;
	  while(list($idkSearch)=mysqli_fetch_row($r)){
	  
	    $sql = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['idpx']} and j.id_kls=$idkSearch order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		$res = mysqli_query($dbc, $sql);
		
		if(mysqli_num_rows($res)>0){
		
		  $ract = mysqli_query($dbc, "select status from status where id_prodi={$_SESSION['idpx']} and id_TA={$_SESSION['id_ta']}");
		  list($act) = mysqli_fetch_row($ract);
		  
		  $sqlP = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		  join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['idpx']} and j.id_kls=$idkSearch order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		  $resP = mysqli_query($dbc, $sqlP);
		  
		  echo'<br/>
		  <table id="tabEjadwal">
		  <tr><td></td><td>Dosen</td><td>Matakuliah</td><td>SKS</td><td>Split</td><td>Paralel</td><td>Semester</td><td>Kelas</td><td>Hari</td><td>Jam Kuliah</td><td>Lokal</td><td></td></tr>';
		  
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
				  echo"<option value=\"$split-$idh-$nod-$nods-$idk\">$hari</option>";
				}else{
				  echo"<option value=\"$split-$idh-$nod-$idk\">$hari</option>";
				}
				$qhs = "select id_hari, nama from hariaktif where id_fak={$_SESSION['adm_fak']} and id_TA={$_SESSION['id_ta']} and id_hari!='$idh' order by id_hari asc";
			    $rhs = mysqli_query($dbc, $qhs);
			    while(list($idhs, $haris) = mysqli_fetch_row($rhs)){
				  if($nods!=0){
			        echo"<option value=\"$split-$idhs-$nod-$nods-$idk\">$haris</option>";
				  }else{
				    echo"<option value=\"$split-$idhs-$nod-$idk\">$haris</option>";
				  }
			    }
			  }else{
			    echo"<option value=\"-\">-</option>";
			    $qhs = "select id_hari, nama from hariaktif where id_fak={$_SESSION['adm_fak']} and id_TA={$_SESSION['id_ta']} order by id_hari asc";
			    $rhs = mysqli_query($dbc, $qhs);
			    while(list($idhs, $haris) = mysqli_fetch_row($rhs)){
			      if($nods!=0){
			        echo"<option value=\"$split-$idhs-$nod-$nods-$idk\">$haris</option>";
				  }else{
				    echo"<option value=\"$split-$idhs-$nod-$idk\">$haris</option>";
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
			  <tr>
			    <td class="tpad"><span class="sub"><font color="#e01515">tim</font></span></td>
			    <td class="tpad">$subdosen</td>
			  </tr>
EOT;
			}
			
		  } 
		  echo'
		  </table>';
		  
		  $rp = mysqli_query($dbc, "select nama from prodi where id_prodi={$_SESSION['idpx']}");
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
		  echo'</table></div></div></div>';
		  
		}//else{
		  //$r = mysqli_query($dbc, "select nama, smstr from kelas where id_kls=$idkSearch");
		  //list($kelas, $smstr) = mysqli_fetch_row($r);
		  //echo'<span class="jad" style="font-size:12px;">Kelas <b>'.$kelas.'</b>, semester <b>'.$smstr.'</b> belum ada jadwal.</span>';
	    //}
		
	  }
	  
	}else{
	  echo'<span class="jad" style="font-size:12px;">Tidak ada data Kelas: <b>'.$cs.'</b></span>';
	}
	
  }else*/if(isset($_POST['cs']) and preg_match('/^[0-9]+$/', $_POST['cs'])){
  
    require_once('../mysqli_connect.php');
	$cs = mysqli_real_escape_string($dbc, strip_tags($_POST['cs']));
	$q = "select id_kls from kelas where id_prodi={$_SESSION['idpx']} and id_TA={$_SESSION['id_ta']} and smstr=$cs";
	$r = mysqli_query($dbc, $q);
	if(mysqli_num_rows($r)>0){
	
	  //echo'<p class="center" style="font-size:12px;">Tetapkan / Hapus Jadwal Penugasan Dosen:</p>';
	  
	  $idform=1; $ht=1; $htOC=1; $jk=1; $lk=1;
	  while(list($idkSearch)=mysqli_fetch_row($r)){
	  
	    $sql = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['idpx']} and j.id_kls=$idkSearch order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		$res = mysqli_query($dbc, $sql);
		
		if(mysqli_num_rows($res)>0){
		
		  $ract = mysqli_query($dbc, "select status from status where id_prodi={$_SESSION['idpx']} and id_TA={$_SESSION['id_ta']}");
		  list($act) = mysqli_fetch_row($ract);
		  
		  $sqlP = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		  join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['idpx']} and j.id_kls=$idkSearch order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		  $resP = mysqli_query($dbc, $sqlP); 
		  
		  echo'<br/>
		  <table id="tabEjadwal">
		  <tr class="exc"><td></td><td>Dosen</td><td>Matakuliah</td><td>SKS</td><td>Split</td><td>Paralel</td><td>Semester</td><td>Kelas</td><td>Hari</td><td>Jam Kuliah</td><td>Lokal</td><td></td></tr>';
		  
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
		
		}/*else{
		  $r = mysqli_query($dbc, "select nama, smstr from kelas where id_kls=$idkSearch");
		  list($kelas, $smstr) = mysqli_fetch_row($r);
		  echo'<span class="jad" style="font-size:12px;">Kelas <b>'.$kelas.'</b>, semester <b>'.$smstr.'</b> belum ada jadwal.</span>';
	    }*/
		
	  }
	  
	}else{
	  echo'<span class="jad" style="font-size:12px;">Tidak ada data Semester: <b>'.$cs.'</b></span>';
	}
  
  }elseif(isset($_POST['cs']) and preg_match('/^[0-9]+[A-Za-z]+$/', $_POST['cs'])){
  
    require_once('../mysqli_connect.php');
	$cs1 = mysqli_real_escape_string($dbc, strip_tags(substr($_POST['cs'], 0, 1)));
	$cs2 = mysqli_real_escape_string($dbc, strip_tags(substr($_POST['cs'], 1)));
	$q = "select id_kls from kelas where id_prodi={$_SESSION['idpx']} and id_TA={$_SESSION['id_ta']} and smstr=$cs1 and nama='$cs2'";
	$r = mysqli_query($dbc, $q);
	if(mysqli_num_rows($r)>0){
	
	  //echo'<p class="center" style="font-size:12px;">Tetapkan / Hapus Jadwal Penugasan Dosen:</p>';
	  
	  $idform=1; $ht=1; $htOC=1; $jk=1; $lk=1;
	  while(list($idkSearch)=mysqli_fetch_row($r)){
	  
	    $sql = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['idpx']} and j.id_kls=$idkSearch order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		$res = mysqli_query($dbc, $sql);
		
		if(mysqli_num_rows($res)>0){
		
		  echo'<br/>
		  <table id="tabEjadwal">
		  <tr class="exc"><td></td><td>Dosen</td><td>Matakuliah</td><td>SKS</td><td>Split</td><td>Paralel</td><td>Semester</td><td>Kelas</td><td>Hari</td><td>Jam Kuliah</td><td>Lokal</td><td></td></tr>';
		
		  $ract = mysqli_query($dbc, "select status from status where id_prodi={$_SESSION['idpx']} and id_TA={$_SESSION['id_ta']}");
		  list($act) = mysqli_fetch_row($ract);
		  
		  $sqlP = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		  join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['idpx']} and j.id_kls=$idkSearch order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		  $resP = mysqli_query($dbc, $sqlP);
		  
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
				  echo"<option value=\"$split-$idh-$nod-$idk\">$hari</option>";
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
		  echo'<span class="jad" style="font-size:12px;">Kelas <b>'.$cs2.'</b> / semester <b>'.$cs1.'</b> belum ada jadwal.</span>';
	    }
		
	  }
	  
	  
	}else{
	  echo'<span class="jad" style="font-size:12px;">Tidak ada data Kelas <b>'.$cs2.'</b> / Semester <b>'.$cs1.'</b></span>';
	}
  
  }else{
  
    require_once('../mysqli_connect.php');
	$cs = mysqli_real_escape_string($dbc, strip_tags($_POST['cs']));
	$q = "select nod from dosen where id_prodi={$_SESSION['idpx']} and match(nama) against('*$cs*' in boolean mode)";
	$r = mysqli_query($dbc, $q);
	if(mysqli_num_rows($r)>0){
	
	  //echo'<p class="center" style="font-size:12px;">Tetapkan / Hapus Jadwal Penugasan Dosen:</p>';
	  
	  $idform=1; $ht=1; $htOC=1; $jk=1; $lk=1;
	  while(list($nodSearch)=mysqli_fetch_row($r)){
	  
	    $sql = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['idpx']} and (j.nod=$nodSearch or j.nods=$nodSearch) order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		$res = mysqli_query($dbc, $sql);
		
		if(mysqli_num_rows($res)>0){
		
		  $ract = mysqli_query($dbc, "select status from status where id_prodi={$_SESSION['idpx']} and id_TA={$_SESSION['id_ta']}");
		  list($act) = mysqli_fetch_row($ract);
		  
		  $sqlP = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		  join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['idpx']} and (j.nod=$nodSearch or j.nods=$nodSearch) order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		  $resP = mysqli_query($dbc, $sqlP);
		  
		  echo'<br/>
		  <table id="tabEjadwal">
		  <tr class="exc"><td></td><td>Dosen</td><td>Matakuliah</td><td>SKS</td><td>Split</td><td>Paralel</td><td>Semester</td><td>Kelas</td><td>Hari</td><td>Jam Kuliah</td><td>Lokal</td><td></td></tr>';
		  
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
		  
		}/*else{
		  $r = mysqli_query($dbc, "select nama from dosen where nod=$nodSearch");
		  list($dosen) = mysqli_fetch_row($r);
		  echo'<span class="jad" style="font-size:12px;">Belum ada Penugasan Dosen: <b>'.$dosen.'</b></span>';
	    }*/
		
	  }
	  
	}else{
	  echo'<span class="jad" style="font-size:12px;">Tidak ada data Dosen: <b>'.$cs.'</b></span>';
	}
	
  }
  
  }
  
}
?>