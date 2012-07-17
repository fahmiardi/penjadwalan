<?php
session_start();
if((!isset($_SESSION['adm_prodi']) or !isset($_GET['aj'])) and (!isset($_SESSION['adm_fak']) or !isset($_GET['aj'])) and (!isset($_SESSION['nod']) or !isset($_GET['aj']))){
  header("Location:../index.php");
}else{

  require_once('../html2pdf/html2pdf.class.php');
  require_once('../mysqli_connect.php');

  if(isset($_SESSION['adm_prodi'])){
    		
	$html2pdf = new HTML2PDF('P', 'A4', 'en');
	$rf = mysqli_query($dbc, "select f.nama from fakultas as f join prodi as p using(id_fak) where p.id_prodi={$_SESSION['adm_prodi']}");
	list($fak) = mysqli_fetch_row($rf);
	$rta = mysqli_query($dbc, "select tahun from ta where id_TA={$_SESSION['id_ta']}");
	list($tahun) = mysqli_fetch_row($rta);
	if($_GET['aj']==1){$aj = "Ganjil";}else{$aj="Genap";}
	
	$write = "<html>
	<head><link rel=\"stylesheet\" type=\"text/css\" href=\"../css/pdf.css\" media=\"all\"/></head>
	<body>
	<div style=\"text-align:center; margin-top:40px; margin-right:40px; margin-left:40px;\">
	<span style=\"font-size:16px;\">Jadwal Perkuliahan</span><br/>
	Program Studi {$_SESSION['prodi']}<br/>
	Fakultas $fak<br/><hr/></div>
	<div style=\"text-align:center; margin-right:40px; margin-left:40px;\"><p align=\"center\">Tahun Akademik $tahun - $aj</p></div>";
	
	$display = 25;
	$start = 0;
	$rDis = mysqli_query($dbc, "select count(id_jad) from jadwal where id_TA={$_SESSION['id_ta']} and id_prodi={$_SESSION['adm_prodi']}");
	list($recs) = mysqli_fetch_row($rDis);
	if($recs>$display){$xt=ceil($recs/$display);}else{$xt=1;}
	
	for($i=1;$i<=$xt;$i++){
	  if($i!=1){$display=30;}
	$write .= "<div style=\"text-align:center; margin-top:20px; margin-right:40px; margin-left:40px;\">
	  <table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">
	    <tr><td>Tim</td><td>Dosen</td><td>Matakuliah</td><td>SKS</td><td>Split</td><td>Paralel</td><td>Semester</td><td>Kelas</td><td>Hari</td><td>Jam Kuliah</td><td>Lokal</td></tr>";
	
	$sqlP = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		  join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['adm_prodi']} order by k.smstr, k.nama, j.id_hari, j.jk_start asc limit $start, $display";
		  $resP = mysqli_query($dbc, $sqlP);
	
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
			
			$write .= "
			  <tr>
			    <td></td>
			    <td>$dosen</td>
			    <td>$matkul</td>
			    <td>$sks</td>
			    <td>$split</td>
			    <td>$prl</td>
			    <td>$smstr</td>
			    <td>$kelas</td> ";

			  if($idh!=0){$write .= "<td>$hari</td>";}else{$write .= "<td>-</td>";}
			  if($sJam!=0){
				if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
			    if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
			    if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
			    if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
				$write .= '<td>'.$sJam.' - '.$eJam.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</td>';
			  }else{
			    $write .= '<td>-</td>';
			  }
			  if($idl!=0){
			    $ql = "select nama from lokal where id_lkl=$idl";
				$rl = mysqli_query($dbc, $ql);
				list($lokal) = mysqli_fetch_row($rl);
				$write .= "<td>$lokal</td>";
			  }else{
				$write .= '<td>-</td>';
			  }
			  $write .= '
			  </tr>';
			  if($nods!=0){
			    $qds = "select nama from dosen where nod=$nods";
			    $rds = mysqli_query($dbc, $qds);
			    list($subdosen) = mysqli_fetch_row($rds);
			    $write .= "
			    <tr>
			      <td>Tim</td>
				  <td>$subdosen</td>
				  <td colspan=\"9\">-</td>
			    </tr>";
			  }
	}
	$write .= "</table>
	</div>";
	  $start += $display;
	}
	
	$write .= "</body>
	</html>";
		
	$html2pdf->writeHTML($write);
	$lowpro = strtolower($_SESSION['prodi']);
	$html2pdf->Output("jk $lowpro $tahun $aj.pdf");

  }elseif(isset($_SESSION['adm_fak'])){
  
    $r = mysqli_query($dbc, "select nama from prodi where id_prodi={$_SESSION['idpx']}");
	list($prodif)=mysqli_fetch_row($r); 
	  
    $html2pdf = new HTML2PDF('P', 'A4', 'en');
	/*$rf = mysqli_query($dbc, "select f.nama from fakultas as f join prodi as p using(id_fak) where p.id_prodi={$_SESSION['idpx']}");
	list($fak) = mysqli_fetch_row($rf);*/
	$rta = mysqli_query($dbc, "select tahun from ta where id_TA={$_SESSION['id_ta']}");
	list($tahun) = mysqli_fetch_row($rta);
	if($_GET['aj']==1){$aj = "Ganjil";}else{$aj="Genap";}
	
	$write = "<html>
	<head><link rel=\"stylesheet\" type=\"text/css\" href=\"../css/pdf.css\" media=\"all\"/></head>
	<body>
	<div style=\"text-align:center; margin-top:40px; margin-right:40px; margin-left:40px;\">
	<span style=\"font-size:16px;\">Jadwal Perkuliahan</span><br/>
	Program Studi $prodif<br/>
	Fakultas {$_SESSION['fak']}<br/><hr/></div>
	<div style=\"text-align:center; margin-right:40px; margin-left:40px;\"><p align=\"center\">Tahun Akademik $tahun - $aj</p></div>";
	
	$display = 25;
	$start = 0;
	$rDis = mysqli_query($dbc, "select count(id_jad) from jadwal where id_TA={$_SESSION['id_ta']} and id_prodi={$_SESSION['idpx']}");
	list($recs) = mysqli_fetch_row($rDis);
	if($recs>$display){$xt=ceil($recs/$display);}else{$xt=1;}
	
	for($i=1;$i<=$xt;$i++){
	  if($i!=1){$display=30;}
	
	$write .= "<div style=\"text-align:center; margin-top:20px; margin-right:40px; margin-left:40px;\">
	  <table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">
	    <tr><td>Tim</td><td>Dosen</td><td>Matakuliah</td><td>SKS</td><td>Split</td><td>Paralel</td><td>Semester</td><td>Kelas</td><td>Hari</td><td>Jam Kuliah</td><td>Lokal</td></tr>";
	
	$sqlP = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		  join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['idpx']} order by k.smstr, k.nama, j.id_hari, j.jk_start asc limit $start, $display";
		  $resP = mysqli_query($dbc, $sqlP);
	
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
			
			$write .= "
			  <tr>
			    <td></td>
			    <td>$dosen</td>
			    <td>$matkul</td>
			    <td>$sks</td>
			    <td>$split</td>
			    <td>$prl</td>
			    <td>$smstr</td>
			    <td>$kelas</td> ";

			  if($idh!=0){$write .= "<td>$hari</td>";}else{$write .= "<td>-</td>";}
			  if($sJam!=0){
				if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
			    if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
			    if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
			    if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
				$write .= '<td>'.$sJam.' - '.$eJam.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</td>';
			  }else{
			    $write .= '<td>-</td>';
			  }
			  if($idl!=0){
			    $ql = "select nama from lokal where id_lkl=$idl";
				$rl = mysqli_query($dbc, $ql);
				list($lokal) = mysqli_fetch_row($rl);
				$write .= "<td>$lokal</td>";
			  }else{
				$write .= '<td>-</td>';
			  }
			  $write .= '
			  </tr>';
			  if($nods!=0){
			    $qds = "select nama from dosen where nod=$nods";
			    $rds = mysqli_query($dbc, $qds);
			    list($subdosen) = mysqli_fetch_row($rds);
			    $write .= "
			    <tr>
			      <td>Tim</td>
				  <td>$subdosen</td>
				  <td colspan=\"9\">-</td>
			    </tr>";
			  }
	}
	$write .= "</table>
	</div>";
	  $start += $display;
	}
	
	$write .="</body>
	</html>";
	
	$html2pdf->writeHTML($write);
	$html2pdf->Output("jk {$_SESSION['fak']} $prodif $tahun $aj.pdf");
  
  }elseif(isset($_SESSION['nod'])){
  
    $html2pdf = new HTML2PDF('P', 'A4', 'en');
	$rp = mysqli_query($dbc, "select nama from prodi where id_prodi={$_SESSION['iddp']}");
	list($prodix) = mysqli_fetch_row($rp);
	$rf = mysqli_query($dbc, "select nama from fakultas where id_fak={$_SESSION['iddf']}");
	list($fak) = mysqli_fetch_row($rf);
	$rta = mysqli_query($dbc, "select tahun from ta where id_TA={$_SESSION['id_ta']}");
	list($tahun) = mysqli_fetch_row($rta);
	if($_GET['aj']==1){$aj = "Ganjil";}else{$aj="Genap";}
	
	$write = "<html>
	<head><link rel=\"stylesheet\" type=\"text/css\" href=\"../css/pdf.css\" media=\"all\"/></head>
	<body>
	<div style=\"text-align:center; margin-top:40px; margin-right:40px; margin-left:40px;\">
	<span style=\"font-size:16px;\">Jadwal Perkuliahan</span><br/>
	Fakultas $fak<br/>
	Tahun Akademik $tahun - $aj<br/><hr/></div>
	<div style=\"margin-right:40px; margin-left:40px;\">
	  <p style=\"text-align:left; font-size:14px;\"><b>{$_SESSION['dosen']}<br/>NIP: {$_SESSION['nip']}</b></p>
	  
	  <p style=\"font-size:12px; text-align:center;\">Jadwal Mengajar:</p>
	</div>
	
	<div style=\"text-align:center; margin-top:20px; margin-right:40px; margin-left:40px;\">
	  <table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">
	    <tr><td>Matakuliah</td><td>SKS</td><td>Split</td><td>Prodi</td><td>Semester</td><td>Kelas</td><td>Hari</td><td>Jam Kuliah</td><td>Lokal</td><td>Tim</td></tr>";
	
	$q = "select j.id_jad, j.id_prodi, j.nod, j.nods, j.id_matkul, j.id_hari, j.paralel, j.sks, j.id_kls, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j
	   join kelas as k using(id_kls) join matkulmaster as m using(id_matkul) where j.id_TA={$_SESSION['id_ta']} and (j.nod={$_SESSION['nod']} or j.nods={$_SESSION['nod']}) order by k.smstr, k.nama, j.id_hari, j.jk_start, m.nama asc";
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
			
			$write .= "
			  <tr>
		       <td>$matkul</td>
		       <td>$sks</td>
			 <td>$split</td>
			 <td>$prodi</td>
			 <td>$smstr</td>
			 <td>$kelas</td>";

			  if($idh!=0){$write .= "<td>$day</td>";}else{$write .= "<td>-</td>";}
			  if($sJam!=0){
				if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
			    if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
			    if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
			    if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
				$write .= '<td>'.$sJam.' - '.$eJam.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</td>';
			  }else{
			    $write .= '<td>-</td>';
			  }
			  if($idl!=0){
			    $ql = "select nama from lokal where id_lkl=$idl";
				$rl = mysqli_query($dbc, $ql);
				list($lokal) = mysqli_fetch_row($rl);
				$write .= "<td>$lokal</td>";
			  }else{
				$write .= '<td>-</td>';
			  }
			  if(($nod!=0)and($nod!=$_SESSION['nod'])){
				$write .= '<td>Tim</td>';
			  }elseif(($nods!=0)and($nods!=$_SESSION['nod'])){
				$write .= '<td>Tim</td>';
			  }else{
			    $write .= '<td>-</td>';
			  }
			  $write .= '
			  </tr>';
	}
	$write .= "</table>";
	$tSKS = 0;
	$rts = mysqli_query($dbc, "select sks from jadwal where (nod={$_SESSION['nod']} or nods={$_SESSION['nod']}) and id_prodi={$_SESSION['iddp']} and id_TA={$_SESSION['id_ta']}");
	while(list($splitz)=mysqli_fetch_row($rts)){$tSKS += $splitz;}
	$write .= '<p class="center" style="font-size:13px;"><br/>Total Beban SKS Mengajar:&nbsp; <b>'.$tSKS.' SKS</b><br/><i>Homebase: '.$prodix.'</i></p>';
	$write .= "
	</div>
	</body>
	</html>";
	
	$html2pdf->writeHTML($write);
	$html2pdf->Output("jk {$_SESSION['dosen']} $tahun $aj.pdf");
  
  }
  
}
?>