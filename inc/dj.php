<?php
session_start();
if((!isset($_SESSION['adm_super']) or !isset($_GET['ta'])) and (!isset($_SESSION['adm_univ']) or !isset($_GET['ta'])) and (!isset($_SESSION['adm_fak']) or !isset($_GET['ta'])) and (!isset($_SESSION['adm_prodi']) or !isset($_GET['ta'])) and (!isset($_SESSION['nod']) or !isset($_GET['ta']))){
  $url='cp.php';
  header("Location: $url");
  exit();
}else{
  $id_ta = $_GET['ta'];
  $_SESSION['id_ta'] = $_GET['ta'];
  $aj = $_GET['aj'];
  $_SESSION['aj'] = $_GET['aj'];
}

$page_title="Data Jadwal";
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

require_once('../mysqli_connect.php');
$q="select tahun from ta where id_TA=$id_ta limit 1";
$r=mysqli_query($dbc, $q); 
list($tahun)=mysqli_fetch_row($r);
if($aj==1){$ajaran='Ganjil';}else{$ajaran='Genap';}
?>

<a href="cp.php" class="back"><</a>&nbsp;&nbsp;
<span class="heading">Jadwal Tahun Akademik <?php echo"$tahun - $ajaran" ?></span>

<div id="idata" class="base">
  <div class="label inputD">DATA JADWAL</div>
  <div class="form">
  
    <?php
	// JADWAL DOSEN
	if(isset($_SESSION['nod'])){
	  echo'<div id="pd">';
	  
	  echo'<a class="fprint" id="fprint" style="text-shadow:none;" href="pdf.php?aj='.$_SESSION['aj'].'" target="_blank" title="SK Mengajar">SK</a><div style="position:relative; display:table; left:5px;">
		  <div id="refJD" class="jpd" style="font-size:12px; cursor:pointer;  display:inline-block; text-shadow:none; border-radius:15px; padding:3px 5px; padding-top:4px;" title="refresh"><img src="../img/refresh.png" height="16px" width="16px"/></div>
		</div>
		<div id="ocRJD">';
	  
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
		/*
		echo'<div style="display:none;"><div id="inline_print">
		  <div class="form" style="padding:20px 20px; margin-bottom:50px;" id="formprint">
		      <a class="fprint" style="text-shadow:none;" href="javascript:window.print()" title="Print">P</a>
		      <p class="center" style="font-size:17px;"><b>SK Mengajar Dosen</font></b><br/>
			<span style="font-size:15px;">Fakultas '.$fak.'<br/>Tahun Akademik '.$tahun.' - Semester '.$ajaran.'</font><br/><span style="font-size:13px;">'.$univ.'</span></p><hr/>
			<p style="text-align:right; padding:0 80px; font-size:14px;"><span style="float:left;">'.$_SESSION['dosen'].'</span>NIP: '.$_SESSION['nip'].'</p>
			<p class="center" style="font-size:12px;">Jadwal Mengajar:</p>
			<table border="1" border-color="#424242" cellspacing="0">
			<tr><td class="vpad">Matakuliah</td><td>SKS</td><td>Split</td><td>Prodi</td><td>Semester</td><td>Kelas</td><td>Hari</td><td>Jam Kuliah</td><td>Lokal</td><td>Tim</td></tr>';		  
			
		$qprint = "select j.id_jad, j.id_prodi, j.nod, j.nods, j.id_matkul, j.id_hari, j.paralel, j.sks, j.id_kls, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j
	       join kelas as k using(id_kls) join matkulmaster as m using(id_matkul) where j.id_TA={$_SESSION['id_ta']} and (j.nod={$_SESSION['nod']} or j.nods={$_SESSION['nod']}) order by order by k.smstr, k.nama, j.id_hari, j.jk_start, m.nama asc";
	    $rprint = mysqli_query($dbc, $q);
		while(list($idjad, $id_p, $nod, $nods, $idm, $idh, $paralel, $split, $idk, $sJam, $mj, $mm, $eJam, $sj, $sm, $idl) = mysqli_fetch_row($rprint)){
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
		
		$tSKS = 0;
		$rts = mysqli_query($dbc, "select sks from jadwal where (nod={$_SESSION['nod']} or nods={$_SESSION['nod']}) and id_prodi={$_SESSION['iddp']} and id_TA={$_SESSION['id_ta']}");
		while(list($splitz)=mysqli_fetch_row($rts)){$tSKS += $splitz;}
		
		echo'</table>
		  <p class="center" style="font-size:13px;"><br/>Total Beban SKS Mengajar Dosen:&nbsp; <b>'.$tSKS.' SKS</b></p>
		  </div>
		  
		</div></div>';*/
	  }else{
	    echo'<br/><span class="jad" style="font-size:12px;">Belum ada data Penugasan Jadwal</span>';
	  }
	  
	  echo'</div></div>';
	}
	// end JADWAL DOSEN
	?>
	
	<?php 
	// Jadwal Kuliah
	require('require_dj.php');
	if((isset($_SESSION['adm_super']) and $proc) or (isset($_SESSION['adm_univ']) and $proc) or (isset($_SESSION['adm_fak']) and $proc) or (isset($_SESSION['adm_prodi']) and $nd and $im and $nk and $nl and $nh and $tj)){
	
	  echo<<<EOT
	  <div id="formwrapper" class="sliderWrapper">
	    <div id="pointer" class="slider com">Jadwal Kuliah</div>
	    <div class="form slide com">
EOT;
	  
	  if(isset($_SESSION['adm_super'])){
	  
	    $q = "select id_univ, nama from univ order by nama asc";
		$r = mysqli_query($dbc, $q);
		echo'<div class="hr" style="margin:10px 10px;"><hr/></div>';
		while(list($idu, $univ) = mysqli_fetch_row($r)){
		  echo"<div class=\"divprodi\"><p class=\"center\"><span class=\"jad\">$univ</span><br/><br/></p>";
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
			      $sql = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
				   join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi=$idp order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
				  $res = mysqli_query($dbc, $sql);
				  if(mysqli_num_rows($res)>0){
					echo'<table><div id="checkpd">
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
					  $rh = mysqli_query($dbc, "select nama from hariaktif where id_hari=$idh and id_fak=$idf");
					  list($hari) = mysqli_fetch_row($rh);
					  if($paralel!='-'){ $prl = str_replace("."," - ",$paralel); }else{ $prl = $paralel; }
			  
					  echo"
					  <tr>
						<td></td>
						<td class=\"vpad\"><span class=\"jad\">$dosen</span></td>
						<td><span class=\"jad\">$matkul</span></td>
						<td><span class=\"jad\">$sks</span></td>
						<td><span class=\"jad\">$split</span></td>
						<td><span class=\"jad\">$prl</span></td>
						<td><span class=\"jad\">$smstr</span></td>
						<td><span class=\"jad\">$kelas</span></td>";
					  if($idh!=0){
						echo"<td><span class=\"jad\">$hari</span></td>";
					  }else{
						echo'<td><span class="jad">-</span></td>';
					  }
					  if($sJam!=0){
						if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
						if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
						if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
						if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
						echo'<td><span class="jad">'.$sJam.' - '.$eJam.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</span></td>';
					  }else{
						echo'<td><span class="jad">-</span></td>';
					  }
					  if($idl!=0){
					    $ql = "select nama from lokal where id_lkl=$idl";
					    $rl = mysqli_query($dbc, $ql);
					    list($lokal) = mysqli_fetch_row($rl);
					    echo'<td><span class="jad">'.$lokal.'</span></td>';
				      }else{
					    echo'<td><span class="jad">-</span></td>';
				      }
				      echo'</tr>';
					  if($nods!=0){
					    $qds = "select nama from dosen where nod=$nods";
					    $rds = mysqli_query($dbc, $qds);
					    list($subdosen) = mysqli_fetch_row($rds);
					    echo"
					    <tr class=\"exc\">
					      <td class=\"tpad\"><span class=\"sub\"><font color=\"#e01515\">tim</font></span></td>
					      <td class=\"tpad\">$subdosen</td>
					    </tr>";
				      }
					}
					echo'</div></table><br/><br/>';
				  }else{
					echo'Belum ada Jadwal Kuliah<br/><br/>';
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
		  echo"<div class=\"divprodi\"><p class=\"center\">$fak<br/><br/><br/></p>";
		  $qp = "select id_prodi, nama from prodi where id_fak=$idf order by nama asc";
		  $rp = mysqli_query($dbc, $qp);
		  if(mysqli_num_rows($rp)>0){
		    while(list($idp, $prodi) = mysqli_fetch_row($rp)){
			  echo"<p class=\"center\"><span class=\"jad\">$prodi</span></p>";
			  $sql = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
			   join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi=$idp order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
			  $res = mysqli_query($dbc, $sql);
		      if(mysqli_num_rows($res)>0){
			    echo'<table><div id="checkpd">
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
				  $rh = mysqli_query($dbc, "select nama from hariaktif where id_hari=$idh and id_fak=$idf");
				  list($hari) = mysqli_fetch_row($rh);
				  if($paralel!='-'){ $prl = str_replace("."," - ",$paralel); }else{ $prl = $paralel; }
			  
				  echo"
				  <tr>
					<td></td>
					<td class=\"vpad\"><span class=\"jad\">$dosen</span></td>
					<td><span class=\"jad\">$matkul</span></td>
					<td><span class=\"jad\">$sks</span></td>
					<td><span class=\"jad\">$split</span></td>
					<td><span class=\"jad\">$prl</span></td>
					<td><span class=\"jad\">$smstr</span></td>
					<td><span class=\"jad\">$kelas</span></td>";
				  if($idh!=0){
					echo"<td><span class=\"jad\">$hari</span></td>";
				  }else{
					echo'<td><span class="jad">-</span></td>';
				  }
				  if($sJam!=0){
					if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
					if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
					if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
					if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
					echo'<td><span class="jad">'.$sJam.' - '.$eJam.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</span></td>';
				  }else{
					echo'<td><span class="jad">-</span></td>';
				  }
				  if($idl!=0){
					$ql = "select nama from lokal where id_lkl=$idl";
					$rl = mysqli_query($dbc, $ql);
					list($lokal) = mysqli_fetch_row($rl);
					echo'<td><span class="jad">'.$lokal.'</span></td>';
				  }else{
					echo'<td><span class="jad">-</span></td>';
				  }
				  echo'</tr>';
				  if($nods!=0){
					$qds = "select nama from dosen where nod=$nods";
					$rds = mysqli_query($dbc, $qds);
					list($subdosen) = mysqli_fetch_row($rds);
					echo"
					<tr class=\"exc\">
					<td class=\"tpad\"><span class=\"sub\"><font color=\"#e01515\">tim</font></span></td>
					<td class=\"tpad\">$subdosen</td>
					</tr>";
				  }
				}
				echo'</div></table><br/><br/>';
			  }else{
			    echo'Belum ada Jadwal Kuliah<br/><br/>';
			  }
			}
		  }else{
		    echo'<span class=\"jad\">Belum ada Prodi</span>';
		  }
		  echo'</div><div class="hr" style="margin:10px 10px;"><hr/></div>';
		}
		
	  }elseif(isset($_SESSION['adm_fak'])){
	    
		if(!isset($_SESSION['aj'])){
		  $_SESSION['aj'] = $_GET['aj'];
		}else{
		  unset($_SESSION['aj']);
		  $_SESSION['aj'] = $_GET['aj'];
		}
		
	    echo'<div id="hGPjk">';
		  require_once('../mysqli_connect.php');
		  $q="select id_prodi, nama from prodi where id_fak={$_SESSION['adm_fak']} order by nama asc";
		  $r=mysqli_query($dbc, $q);
		  
		  if(mysqli_num_rows($r) > 0){
		    
			echo'<p class="center">Pilih Prodi:</p><br/>';
			
			$i = 1;
		    while(list($idp, $prodi)=mysqli_fetch_row($r)){
			  echo<<<EOT
			  <p class="center">
		          <a href="idp=$idp/jk" class="vj" id="gp">$prodi</a><br/><br/>
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
		  echo'<div id="ocGPjk"></div>';
		
	  }elseif(isset($_SESSION['adm_prodi'])){
		
		echo'<div style="position:relative; left:170px; display:table;">
		  <a class="fprint" id="printPJ" style="text-shadow:none;" href="pdf.php?aj='.$_SESSION['aj'].'" title="Print to PDF" target="_blank">pdf</a>
		  <a class="fprint" id="printPJ" style="text-shadow:none;" href="xls.php?aj='.$_SESSION['aj'].'" title="Export to Excel" target="_blank">xls</a>&nbsp;&nbsp;
		  <div id="refPD" class="jpd" style="font-size:12px; cursor:pointer;  display:inline-block; text-shadow:none; border-radius:15px; padding:3px 5px; padding-top:4px;" title="refresh"><img src="../img/refresh.png" height="16px" width="16px"/></div>
		  <div style="font-size:12px; display:inline-block; margin-left:2px;">
		    <div id="searchPD" class="jpd" style="font-size:12px; cursor:pointer;  display:inline-block; text-shadow:none; border-radius:15px; padding:3px 5px; padding-top:4px;" title="cari jadwal"><img src="../img/search.png" height="16px" width="16px"/></div>
		    <input type="text" name="cs" size="20" id="search" value="cari jadwal..." style="text-align:left; font-style:italic; padding-left:5px; color:#909090;"/>
		  </div>
		</div>
		<div id="pdosen">';
		
	    $sql = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['adm_prodi']} order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		$res = mysqli_query($dbc, $sql);
		if(mysqli_num_rows($res)>0){
		
		  $ract = mysqli_query($dbc, "select status from status where id_prodi={$_SESSION['adm_prodi']} and id_TA={$_SESSION['id_ta']}");
		  list($act) = mysqli_fetch_row($ract);
		  
		  $sqlP = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		  join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['adm_prodi']} order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		  $resP = mysqli_query($dbc, $sqlP);
		  
		  echo'<p class="center" style="font-size:12px;">Tetapkan / Hapus Jadwal Kuliah:</p>
		  <table id="tabEjadwal" border="0">
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
		  list($fak) = mysqli_fetch_row($rf);
		  
		  echo'<div style="display:none"><div id="inline_print" class="form" style="width:900px;">
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
		  echo'</table></div></div>'*/;echo'</div>';
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
			<tr  class="exc">
EOT;
			  echo"
			  <td><input type=\"checkbox\" name=\"tim\" value=\"1\" class=\"timTrig\"/></td>
			  <td><select name=\"idp\" class=\"prodiTrig\">
			    <option value=\"{$_SESSION['adm_prodi']}\" selected=\"selected\">{$_SESSION['prodi']}</option>";
			  $qp = "select id_prodi, nama from prodi where id_univ={$_SESSION['idpu']} and id_prodi!={$_SESSION['adm_prodi']} order by nama asc";
			  $qp = mysqli_query($dbc, $qp);
			  while(list($idp, $prodi) = mysqli_fetch_row($qp)){
			    echo"<option value=\"$idp\">$prodi</option>";
			  }
			  echo'
			  </select></td>
			  <td><select name="nod" class="nodTrig ocDosen">
			    <option value="-" selected="selected">-</option>';
			  $qd = "select nod, nama from dosen where id_prodi={$_SESSION['adm_prodi']} order by nama asc";
			  $rd = mysqli_query($dbc, $qd);
			  while(list($nod, $dosen) = mysqli_fetch_row($rd)){
			    echo"<option value=\"$nod\">$dosen</option>";
			  }
			  echo'
			  </select></td>
			  <td><select name="idkr" class="idkrTrig">
			    <option value="-" selected="selected">-</option>';
			  $rkr = mysqli_query($dbc, "select id_krklm, tahun from kurikulum where id_prodi={$_SESSION['adm_prodi']} order by tahun desc");
			  while(list($idkr, $thn)=mysqli_fetch_row($rkr)){
			    echo"<option value=\"{$_GET['aj']}$idkr\">$thn</option>";
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
			    <option value=\"{$_SESSION['adm_prodi']}\" selected=\"selected\">{$_SESSION['prodi']}</option>";
			  $qp = "select id_prodi, nama from prodi where id_univ={$_SESSION['idpu']} and id_prodi!={$_SESSION['adm_prodi']} order by nama asc";
			  $qp = mysqli_query($dbc, $qp);
			  while(list($idp, $prodi) = mysqli_fetch_row($qp)){
			    echo"<option value=\"$idp\">$prodi</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td><select name="subnod" class="ocSubDosen">
			  <option value="-" selected="selected">-</option>
EOT;
			  $qds = "select nod, nama from dosen where id_prodi={$_SESSION['adm_prodi']} order by nama asc";
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
	  }
	  
	  echo'
	    </div>
	  </div>';
	} // end Jadwal Kuliah
	
	// KELAS
	if(isset($_SESSION['adm_super']) or isset($_SESSION['adm_univ']) or isset($_SESSION['adm_fak']) or isset($_SESSION['adm_prodi'])){
	
	  echo<<<EOT
	  <div id="formwrapper" class="sliderWrapper">
	    <div id="pointer" class="slider com">Kelas</div>
	    <div class="form slide com">
EOT;
	  if(isset($_SESSION['adm_super'])){
	  
	    $q = "select id_univ, nama from univ order by nama asc";
		$r = mysqli_query($dbc, $q);
		echo'<div class="hr" style="margin:10px 10px;"><hr/></div>';
		while(list($idu, $univ) = mysqli_fetch_row($r)){
		  echo"<div class=\"divprodi\"><p class=\"center\"><span class=\"jad\">$univ</span><br/><br/></p>";
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
			      $qk = "select nama, smstr, mhs from kelas where id_prodi=$idp and id_TA={$_SESSION['id_ta']} order by smstr asc";
				  $rk = mysqli_query($dbc, $qk);
				  if(mysqli_num_rows($rk)>0){
					echo'<table><tr class="exc"><td>Semester</td><td>Kelas</td><td>Mahasiswa</td></tr>';
					while(list($kelas, $smstr, $mhs) = mysqli_fetch_row($rk)){
					  echo"
					  <tr>
						<td class=\"vpad\"><span class=\"jad\">$smstr</span></td>
						<td><span class=\"jad\">$kelas</span></td>
						<td><span class=\"jad\">$mhs</span></td>
					  </tr>";
					}
					echo'</table><br/><br/>';
				  }else{
					echo'Belum ada Kelas<br/><br/>';
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
		  echo"<div class=\"divprodi\"><p class=\"center\">$fak<br/><br/><br/></p>";
		  $qp = "select id_prodi, nama from prodi where id_fak=$idf order by nama asc";
		  $rp = mysqli_query($dbc, $qp);
		  if(mysqli_num_rows($rp)>0){
		    while(list($idp, $prodi) = mysqli_fetch_row($rp)){
			  echo"<p class=\"center\"><span class=\"jad\">$prodi</span></p>";
			  $qk = "select nama, smstr, mhs from kelas where id_prodi=$idp and id_TA={$_SESSION['id_ta']} order by smstr asc";
			  $rk = mysqli_query($dbc, $qk);
			  if(mysqli_num_rows($rk)>0){
				echo'<table><tr class="exc"><td>Semester</td><td>Kelas</td><td>Mahasiswa</td></tr>';
				while(list($kelas, $smstr, $mhs) = mysqli_fetch_row($rk)){
				  echo"
				  <tr>
					<td class=\"vpad\"><span class=\"jad\">$smstr</span></td>
					<td><span class=\"jad\">$kelas</span></td>
					<td><span class=\"jad\">$mhs</span></td>
				  </tr>";
				}
				echo'</table><br/><br/>';
			  }else{
			    echo'Belum ada Kelas<br/><br/>';
			  }
			}
		  }else{
		    echo'<span class=\"jad\">Belum ada Prodi</span>';
		  }
		  echo'</div><div class="hr" style="margin:10px 10px;"><hr/></div>';
		}
		
	  }elseif(isset($_SESSION['adm_fak'])){
	    
		echo'<div id="hGPkls">';
		  require_once('../mysqli_connect.php');
		  $q="select id_prodi, nama from prodi where id_fak={$_SESSION['adm_fak']} order by nama asc";
		  $r=mysqli_query($dbc, $q);
		  
		  if(mysqli_num_rows($r) > 0){
		    
			echo'<p class="center">Pilih Prodi:</p><br/>';
			
			$i = 1;
		    while(list($idp, $prodi)=mysqli_fetch_row($r)){
			  echo<<<EOT
			  <p class="center">
		          <a href="idp=$idp/kls" class="vj" id="gp">$prodi</a><br/><br/>
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
		  echo'<div id="ocGPkls"></div>';
		
	  }elseif(isset($_SESSION['adm_prodi'])){
	  
	    echo'<div style="position:relative; display:table;">
		  <div id="refKelas" class="jpd" style="font-size:12px; cursor:pointer;  display:inline-block; text-shadow:none; border-radius:15px; padding:3px 5px; padding-top:4px;" title="refresh"><img src="../img/refresh.png" height="16px" width="16px"/></div>
		</div>
		<div id="ocRKelas">';
	  
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
	  
	  echo'
	    </div>
	  </div>';
	} // end KELAS
	
	// LOKAL
	if(isset($_SESSION['adm_super']) or isset($_SESSION['adm_univ']) or isset($_SESSION['adm_fak']) or isset($_SESSION['adm_prodi'])){
	
	  echo<<<EOT
	  <div id="formwrapper" class="sliderWrapper">
	    <div id="pointer" class="slider com">Lokal</div>
	    <div class="form slide com">
EOT;
	  
	  if(isset($_SESSION['adm_super'])){
	  
	    $q = "select id_univ, nama from univ order by nama asc";
		$r = mysqli_query($dbc, $q);
		echo'<div class="hr" style="margin:10px 10px;"><hr/></div>';
		while(list($idu, $univ) = mysqli_fetch_row($r)){
		  echo"<div class=\"divprodi\"><p class=\"center\">$univ<br/><br/></p>";
		  $qf = "select id_fak, nama from fakultas where id_univ=$idu order by nama asc";
		  $rf = mysqli_query($dbc, $qf);
		  if(mysqli_num_rows($rf)>0){
		    while(list($idf, $fak) = mysqli_fetch_row($rf)){
			  echo"<p class=\"center\"><span class=\"jad\">$fak</span><br/></p>";
			  $ql = "select nama, muat from lokal where id_fak=$idf and id_TA={$_SESSION['id_ta']} order by nama asc";
			  $rl = mysqli_query($dbc, $ql);
			  if(mysqli_num_rows($rl)>0){
			    echo'<table><tr class="exc"><td>Lokal</td><td>Kapasitas</td></tr>';
				while(list($lokal, $muat) = mysqli_fetch_row($rl)){
			      echo"
				  <tr>
					<td class=\"vpad\"><span class=\"jad\">$lokal</span></td>
					<td><span class=\"jad\">$muat</span></td>
				  </tr>";
				}
				echo'</table>';
			  }else{
			    echo'Belum ada Lokal<br/><br/>';
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
		  echo"<div class=\"divprodi\"><p class=\"center\"><span class=\"jad\">$fak</span><br/></p>";
		  $ql = "select nama, muat from lokal where id_fak=$idf and id_TA={$_SESSION['id_ta']} order by nama asc";
		  $rl = mysqli_query($dbc, $ql);
		  if(mysqli_num_rows($rl)>0){
		    echo'<table><tr class="exc"><td>Lokal</td><td>Kapasitas</td></tr>';
		    while(list($lokal, $muat) = mysqli_fetch_row($rl)){
			  echo"
			  <tr>
				<td class=\"vpad\"><span class=\"jad\">$lokal</span></td>
				<td><span class=\"jad\">$muat</span></td>
			  </tr>";
			}
			echo'</table>';
		  }else{
		    echo'Belum ada Lokal';
		  }
		  echo'</div><div class="hr" style="margin:10px 10px;"><hr/></div>';
		}
		
	  }elseif(isset($_SESSION['adm_fak'])){
	  
	    $q = "select id_lkl, nama, muat from lokalmaster where id_fak={$_SESSION['adm_fak']} order by nama asc";
		$r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r)>0){
		  echo'<p class="center" style="font-size:12px;">Aktif / Non-Aktifkan Lokal:</p>';
		  echo'<p class="center" style="font-size:12px;" id="testCB"></p>';
		  echo'<table>';
		  $i = 1; $j = 1;
		  while(list($idl, $lokal, $muat)=mysqli_fetch_row($r)){
		    $rl = mysqli_query($dbc, "select * from lokal where id_lkl=$idl and id_TA={$_SESSION['id_ta']}");
		    echo"
			<tr>
			  <td style=\"padding:10px 5px;\"><input type=\"checkbox\" name=\"lkVal\" value=\"$idl-$lokal-$muat\" id=\"cbLokal".$i++."\" "; 
			  if(mysqli_num_rows($rl)>0){echo"checked=\"checked\"";} echo" /></td>
			  <td><span style=\"font-size:14px;\" class=\""; if(mysqli_num_rows($rl)>0){echo"jad";} echo" countCB spcbLokal".$j++."\">$lokal</span></td>
			</tr>";
		  }
		  echo'</table>';
		}else{
		  echo'<p class="center"><span class="jad" style="font-size:12px;">Belum ada data Lokal</span></p>';
		}
		
	  }elseif(isset($_SESSION['adm_prodi'])){
	  
	    $q = "select nama, muat from lokal where id_fak={$_SESSION['idpf']} and id_TA={$_SESSION['id_ta']} order by nama asc";
		$r = mysqli_query($dbc, $q);
		$qf = "select nama from fakultas where id_fak={$_SESSION['idpf']}";
		$rf = mysqli_query($dbc, $qf);
		list($fak) = mysqli_fetch_row($rf);
		echo"<div class=\"divprodi\"><p class=\"center\"><span class=\"jad\">$fak</span></p>";
		if(mysqli_num_rows($r)>0){		  
		  echo'<table><tr class="exc"><td>Lokal</td><td>Kapasitas</td></tr>';
		  while(list($lokal, $muat) = mysqli_fetch_row($r)){
			echo"
			<tr>
			  <td class=\"vpad\"><span class=\"jad\">$lokal</span></td>
			  <td><span class=\"jad\">$muat</span></td>
			</tr>";
		  }
		  echo'</table><br/><br/>'; 
		}else{
		  echo'Belum ada Lokal<br/><br/>';
		}
		echo'</div>';
	  }
	  
	  echo'
	    </div>
	  </div>';
	} // end LOKAL
	
	// HARI PERKULIAHAN
	if(isset($_SESSION['adm_super']) or isset($_SESSION['adm_univ']) or isset($_SESSION['adm_fak']) or isset($_SESSION['adm_prodi'])){
	
	  echo<<<EOT
	  <div id="formwrapper" class="sliderWrapper">
	    <div id="pointer" class="slider com">Hari Perkuliahan</div>
	    <div class="form slide com">
EOT;
	  
	  if(isset($_SESSION['adm_super'])){
	  
	    $q = "select id_univ, nama from univ order by nama asc";
		$r = mysqli_query($dbc, $q);
		echo'<div class="hr" style="margin:10px 10px;"><hr/></div>';
		while(list($idu, $univ) = mysqli_fetch_row($r)){
		  echo"<div class=\"divprodi\"><p class=\"center\"><span class=\"jad\">$univ</span><br/><br/></p>";
		  $qf = "select id_fak, nama from fakultas where id_univ=$idu order by nama asc";
		  $rf = mysqli_query($dbc, $qf);
		  if(mysqli_num_rows($rf)>0){
		    while(list($idf, $fak) = mysqli_fetch_row($rf)){
			  echo"<p class=\"center\">$fak<br/></p>";
			  $qh = "select id_hari, nama from hariaktif where id_fak=$idf and id_TA={$_SESSION['id_ta']} order by id_hari asc";
			  $rh = mysqli_query($dbc, $qh);
			  if(mysqli_num_rows($rh)>0){
				echo'<table><tr class="exc">';
				while(list($idh, $hari) = mysqli_fetch_row($rh)){
				  echo"<td class=\"vpad\"><span class=\"jad\">$hari</span></td>";
				}
				echo'</tr></table>';
			  }else{
				echo'<span class="jad">Belum ada Hari Perkuliahan</span></br></br>';
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
		  echo"<div class=\"divprodi\"><p class=\"center\">$fak<br/></p>";
		  $qh = "select id_hari, nama from hariaktif where id_fak=$idf and id_TA={$_SESSION['id_ta']} order by id_hari asc";
		  $rh = mysqli_query($dbc, $qh);
		  if(mysqli_num_rows($rh)>0){
		    echo'<table><tr class="exc">';
		    while(list($idh, $hari) = mysqli_fetch_row($rh)){
			  echo"<td class=\"vpad\"><span class=\"jad\">$hari</span></td>";
			}
			echo'</tr></table>';
		  }else{
		    echo'<span class="jad">Belum ada Hari Perkuliahan</span>';
		  }
		  echo'</div><div class="hr" style="margin:10px 10px;"><hr/></div>';
		}
		
	  }elseif(isset($_SESSION['adm_fak'])){
	  
	    echo'<div style="position:relative; display:table;">
		  <div id="refHari" class="jpd" style="font-size:12px; cursor:pointer;  display:inline-block; text-shadow:none; border-radius:15px; padding:3px 5px; padding-top:4px;" title="refresh"><img src="../img/refresh.png" height="16px" width="16px"/></div>
		</div>
		<div id="ocRHari">';
	  
	    $q = "select id_hari, nama from hariaktif where id_fak={$_SESSION['adm_fak']} and id_TA={$_SESSION['id_ta']} order by id_hari asc";
		$r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r) > 0){
		  echo'<p class="center" style="font-size:12px;">Hapus Hari Perkuliahan:</p><table><tr class="exc">';
		  while(list($idh, $nama)=mysqli_fetch_row($r)){
		    echo<<<EOT
			<td><a class="x delhari" href="hapushari.php?idh=$idh" title="Hapus">-</a><br/><span class="jad" style="line-height:20px;">$nama</span></td>
EOT;
		  }
		  echo'</tr></table></div>';
		}else{
		  echo"<p class=\"center\"><span class=\"jad\" style=\"font-size:13px;\">Belum ada data Hari Perkuliahan</span></p></div>";
		}
			
		echo<<<EOT
	    <div class="hr"><hr/></div>
		  
	    <p class="center" style="font-size:12px;">Tambah Hari Perkuliahan:</p>
		  
		<div id="checkhari">
          <form action="cekhari.php" method="post" id="cekhari">
		  <table>
		    <tr class="exc">
EOT;
			  $day = array(1=>'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu');
			  foreach($day as $k => $v){
			  echo"<td><span class=\"jad\">$v</span><br/><input type=\"checkbox\" name=\"hari[]\" value=\"$k-$v\"/></td>";
			  }
			  echo<<<EOT
		    </tr>
	      </table>
          <p class="center"><input type="submit" name="submit" value="+ Tambah"/></p>
          <input type="hidden" name="submitted" value="TRUE"/>
          </form>
	    </div>
EOT;
		
	  }elseif(isset($_SESSION['adm_prodi'])){
	  
	    $q = "select id_hari, nama from hariaktif where id_fak={$_SESSION['idpf']} and id_TA={$_SESSION['id_ta']} order by id_hari asc";
		$r = mysqli_query($dbc, $q);
		$qf = "select nama from fakultas where id_fak={$_SESSION['idpf']}";
		$rf = mysqli_query($dbc, $qf);
		list($fak) = mysqli_fetch_row($rf);
		echo"<div class=\"divprodi\"><p class=\"center\">$fak</p>";
		if(mysqli_num_rows($r)>0){		  
		  echo'<table><tr class="exc">';
		  while(list($idh, $nama)=mysqli_fetch_row($r)){
		    echo<<<EOT
			<td class="vpad"><span class="jad">$nama</span></td>
EOT;
		  }
		  echo'</tr></table>';
		}else{
		  echo'<span class="jad" style="font-size:12px;">Belum ada Hari Perkuliahan</span><br/><br/>';
		}
		echo'</div>';
	  }
	  
	  echo'
	    </div>
	  </div>';
	} // end HARI PERKULIAHAN
	
	// JAM PERKULIAHAN
	if(isset($_SESSION['adm_super']) or isset($_SESSION['adm_univ']) or isset($_SESSION['adm_fak']) or isset($_SESSION['adm_prodi'])){
	
	  echo<<<EOT
	  <div id="formwrapper" class="sliderWrapper">
	    <div id="pointer" class="slider com">Jam Perkuliahan</div>
	    <div class="form slide com">
EOT;

	  if(isset($_SESSION['adm_super'])){
	  
	    $q = "select id_univ, nama from univ order by nama asc";
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
				echo'<table><tr class="exc"><td>Jam <font style="font-size:10px;">Ke-</font></td><td></td><td>Mulai <font style="font-size:10px;">Jam</font></td><td></td><td>Mulai <font style="font-size:10px;">Menit</font></td><td> </td><td>Selesai <font style="font-size:10px;">Jam</font></td><td></td><td>Selesai <font style="font-size:10px;">Menit</font></td></tr>';
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
		}
		
	  }elseif(isset($_SESSION['adm_univ'])){
	    
		$q = "select id_fak, nama from fakultas where id_univ={$_SESSION['adm_univ']} order by nama asc";
		$r = mysqli_query($dbc, $q);
		echo'<div class="hr" style="margin:10px 10px;"><hr/></div>';
		while(list($idf, $fak) = mysqli_fetch_row($r)){
		  echo"<div class=\"divprodi\"><p class=\"center\"><span class=\"jad\">$fak</span><br/></p>";
		  $qj = "select jam_kul, mulai_jam, mulai_menit, selesai_jam, selesai_menit from tabeljam where id_fak=$idf and id_TA={$_SESSION['id_ta']} order by jam_kul asc";
		  $rj = mysqli_query($dbc, $qj);
		  if(mysqli_num_rows($rj)>0){
		    echo'<table><tr class="exc"><td>Jam <font style="font-size:10px;">Ke-</font></td><td></td><td>Mulai <font style="font-size:10px;">Jam</font></td><td></td><td>Mulai <font style="font-size:10px;">Menit</font></td><td> </td><td>Selesai <font style="font-size:10px;">Jam</font></td><td></td><td>Selesai <font style="font-size:10px;">Menit</font></td></tr>';
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
		}
		
	  }elseif(isset($_SESSION['adm_fak'])){
	    
		$rOp = mysqli_query($dbc, "select * from tabeljam where id_fak={$_SESSION['adm_fak']} and id_TA={$_SESSION['id_ta']}");
		if(mysqli_num_rows($rOp)>0){
		
		echo'<div style="position:relative; display:table;">
		  <div id="refJK" class="jpd" style="font-size:12px; cursor:pointer; display:inline-block; text-shadow:none; border-radius:15px; padding:3px 5px; padding-top:4px;" title="refresh"><img src="../img/refresh.png" height="16px" width="16px"/></div>
		</div>
		<div id="ocRJK">';

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
			  <td class="vpad"><a class="x deljam atc'.$ic++.'" href="hapusjam.php?idj='.$idj.'" title="Hapus"'; if($jk!=$maxJ){echo'style="display:none;"';} echo'>-</a></td>
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
          <form action="cekjam.php" method="post" id="cekjam">
		  <table>
		    <tr class="exc"><td>Jam ke-</td><td></td><td>Mulai <font style="font-size:10px;">Jam</font></td><td></td><td>Mulai <font style="font-size:10px;">Menit</font></td><td> </td><td>Selesai <font style="font-size:10px;">Jam</font></td><td></td><td>Selesai <font style="font-size:10px;"> &nbsp;Menit </font></td></tr>
		    <tr class="exc">
		      <td><span class="jad ocCJ">
EOT;
			  $minJ = 1;
			  $rj = mysqli_query($dbc, "select MAX(jam_kul) from tabeljam where id_fak={$_SESSION['adm_fak']} and id_TA={$_SESSION['id_ta']}");
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
		
		}else{
		  
		  echo'<div id="ocOpcj">';
		  
		  echo'<p class="center"><font style="font-size:12px;">Pilih input data:</font><br/><br/>
		  <a class="vj opcj" style="font-size:14px;" opt="generate">Data Master</a>&nbsp;
		  <a class="vj opcj" style="font-size:14px;" opt="create">Buat Baru</a><br/><br/></p>';
		  
		  echo'</div>';
		  
		}
		
	    		
	  }elseif(isset($_SESSION['adm_prodi'])){
	  
	    $q = "select jam_kul, mulai_jam, mulai_menit, selesai_jam, selesai_menit from tabeljam where id_fak={$_SESSION['idpf']} and id_TA={$_SESSION['id_ta']} order by jam_kul asc";
		$r = mysqli_query($dbc, $q);
		$qf = "select nama from fakultas where id_fak={$_SESSION['idpf']}";
		$rf = mysqli_query($dbc, $qf);
		list($fak) = mysqli_fetch_row($rf);
		echo"<div class=\"divprodi\"><p class=\"center\"><span class=\"jad\">$fak</span></p>";
		if(mysqli_num_rows($r)>0){		  
		  echo'<table><tr class="exc"><td>Jam <font style="font-size:10px;">Ke-</font></td><td></td><td>Mulai <font style="font-size:10px;">Jam</font></td><td></td><td>Mulai <font style="font-size:10px;">Menit</font></td><td> </td><td>Selesai <font style="font-size:10px;">Jam</font></td><td></td><td>Selesai <font style="font-size:10px;">Menit</font></td></tr>';
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
	  }

	  echo'
	    </div>
	  </div>';
	} // end JAM PERKULIAHAN
	?>
	
	<?php 
	// DENAH LOKAL
	if(isset($_SESSION['adm_fak'])){
	  $rl = mysqli_query($dbc, "select nama from lokal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']}");
	  $rh = mysqli_query($dbc, "select nama from hariaktif where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']}");
	  $rj = mysqli_query($dbc, "select jam_kul from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']}");
	  if(mysqli_num_rows($rl)>0){list($lk) = mysqli_fetch_row($rl);}else{$lk=false;}
	  if(mysqli_num_rows($rh)>0){list($ha) = mysqli_fetch_row($rh);}else{$ha=false;}
	  if(mysqli_num_rows($rj)>0){list($jk) = mysqli_fetch_row($rj);}else{$jk=false;}
	}elseif(isset($_SESSION['adm_prodi'])){
	  $rl = mysqli_query($dbc, "select nama from lokal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']}");
	  $rh = mysqli_query($dbc, "select nama from hariaktif where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']}");
	  $rj = mysqli_query($dbc, "select jam_kul from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']}");
	  if(mysqli_num_rows($rl)>0){list($lk) = mysqli_fetch_row($rl);}else{$lk=false;}
	  if(mysqli_num_rows($rh)>0){list($ha) = mysqli_fetch_row($rh);}else{$ha=false;}
	  if(mysqli_num_rows($rj)>0){list($jk) = mysqli_fetch_row($rj);}else{$jk=false;}
	}
	if((isset($_SESSION['adm_fak']) and isset($_SESSION['id_ta']) and $lk and $ha and $jk) or (isset($_SESSION['adm_prodi']) and isset($_SESSION['id_ta']) and $lk and $ha and $jk)){
	  echo<<<EOT
	  <div id="formwrapper" class="sliderWrapper">
	    <div id="pointer" class="slider com">Denah Lokal</div>
	    <div class="form slide com">
EOT;
	  if(isset($_SESSION['adm_fak'])){
	  
	    echo'<div style="position:relative; display:table;">
		  <div id="refDF" class="jpd" style="font-size:12px; cursor:pointer;  display:inline-block; text-shadow:none; border-radius:15px; padding:3px 5px; padding-top:4px;" title="refresh"><img src="../img/refresh.png" height="16px" width="16px"/></div>
		</div>
		<div id="ocRDF">';
		
		/*$r = mysqli_query($dbc, "select id_lkl, nama from lokal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} order by nama asc");
		while(list($idl, $lokal)=mysqli_fetch_row($r)){
		
		  echo'<br/>
		  <table style="padding:10px 10px; border-radius:4px; background:#f8f8f8;">
		    <tr><td style="padding:10px 5px;"><span class="jad" style="background:white;">'.$lokal.'</td></span></td>';
			
		  $rjs = mysqli_query($dbc, "select jam_kul from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']}");
		  while(list($jk)=mysqli_fetch_row($rjs)){
		    echo'<td>'.$jk.'</td>';
		  }
		  
		  echo'</tr>';
		  
		  $rh = mysqli_query($dbc, "select id_hari, nama from hariaktif where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} order by id_hari asc");
		  while(list($idh, $hari)=mysqli_fetch_row($rh)){
		    echo'
			<tr>
			  <td style="padding:10px 5px;">'.$hari.'</td>';
			$rjh = mysqli_query($dbc, "select jam_kul from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']}");
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
		}*/
		
		echo'</div>';
		
	  }elseif(isset($_SESSION['adm_prodi'])){
	  
	    echo'<div style="position:relative; display:table;">
		  <div id="refDP" class="jpd" style="font-size:12px; cursor:pointer;  display:inline-block; text-shadow:none; border-radius:15px; padding:3px 5px; padding-top:4px;" title="refresh"><img src="../img/refresh.png" height="16px" width="16px"/></div>
		</div>
		<div id="ocRDP">';
	  
	    /*$r = mysqli_query($dbc, "select id_lkl, nama from lokal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} order by nama asc");
		while(list($idl, $lokal)=mysqli_fetch_row($r)){
		
		  echo'<br/>
		  <table style="padding:10px 10px; border-radius:4px; background:#f8f8f8;">
		    <tr><td style="padding:10px 5px;"><span class="jad" style="background:white;">'.$lokal.'</td></span></td>';
			
		  $rjs = mysqli_query($dbc, "select jam_kul from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']}");
		  while(list($jk)=mysqli_fetch_row($rjs)){
		    echo'<td>'.$jk.'</td>';
		  }
		  
		  echo'</tr>';
		  
		  $rh = mysqli_query($dbc, "select id_hari, nama from hariaktif where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} order by id_hari asc");
		  while(list($idh, $hari)=mysqli_fetch_row($rh)){
		    echo'
			<tr>
			  <td style="padding:10px 5px;">'.$hari.'</td>';
			$rjh = mysqli_query($dbc, "select jam_kul from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']}");
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
		}*/
		
		echo'</div>';
		
	  }
	  
	  echo'
	    </div>
	  </div>';
	}
	// end DENAH LOKAL
	?>
	
  </div>
</div>

<?php include('../footer.html'); ?>