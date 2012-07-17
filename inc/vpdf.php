<?php
  if(!isset($_GET['v'])){
    header('Location:index.php');  
  }else{  
    
	require_once('../html2pdf/html2pdf.class.php');
    require_once('../mysqli_connect.php');
	
	$html2pdf = new HTML2PDF('P', 'A4', 'en');
	list($idta, $idk, $idp, $smstr) = explode('/', $_GET['v']);
	
	$rk = mysqli_query($dbc, "select nama from kelas where id_kls=$idk");
	list($kls) = mysqli_fetch_row($rk);
	$rf = mysqli_query($dbc, "select id_fak from prodi where id_prodi=$idp limit 1");
	list($idf) = mysqli_fetch_row($rf);
	$rf2 = mysqli_query($dbc, "select nama from fakultas where id_fak=$idf limit 1");
	list($fak) = mysqli_fetch_row($rf2);
	$rp = mysqli_query($dbc, "select nama from prodi where id_prodi=$idp limit 1");
	list($prodi) = mysqli_fetch_row($rp);
	$rta = mysqli_query($dbc, "select tahun, ajaran from ta where id_TA=$idta");
	list($tahun, $ajx) = mysqli_fetch_row($rta);
	if($ajx==1){$aj = "Ganjil";}else{$aj="Genap";}
	$q = "select id_jad, nod, nods, id_matkul, sks, paralel, id_hari, jk_start, mulai_jam, mulai_menit, jk_end, selesai_jam, selesai_menit, id_lkl from jadwal where id_kls=$idk order by id_hari, jk_start asc";
	$r = mysqli_query($dbc, $q);
		
	$write = "<html>
	<head><link rel=\"stylesheet\" type=\"text/css\" href=\"../css/pdf.css\" media=\"all\"/></head>
	<body>
	<div style=\"text-align:center; margin-top:40px; margin-right:40px; margin-left:40px;\">
	<span style=\"font-size:16px;\">Jadwal Perkuliahan</span><br/>
	Program Studi $prodi<br/>
	Fakultas $fak<br/><hr/></div>
	<div style=\"text-align:center; margin-right:40px; margin-left:40px;\"><p align=\"left\"><b>$prodi $smstr-$kls</b></p><p align=\"center\">Tahun Akademik $tahun - $aj<br/></p></div>
	
	<div style=\"text-align:center; margin-top:20px; margin-right:40px; margin-left:40px;\">
	  <table border=\"1\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">
	    <tr><td>Tim</td><td>Dosen</td><td>Mata Kuliah</td><td>SKS</td><td>Split</td><td>Paralel</td><td>Hari</td><td>Jam Kuliah</td><td>Lokal</td></tr>";
	
	while(list($idjad, $nod, $nods, $idm, $split, $paralel, $idh, $sJam, $mj, $mm, $eJam, $sj, $sm, $idl) = mysqli_fetch_row($r)){
		      $qd = "select nama from dosen where nod=$nod";
			  $rd = mysqli_query($dbc, $qd);
			  list($dosen) = mysqli_fetch_row($rd);
			  $qm = "select nama, sks from matkulmaster where id_matkul=$idm";
			  $rm = mysqli_query($dbc, $qm);
			  list($matkul, $sks) = mysqli_fetch_row($rm);
			  $rh = mysqli_query($dbc, "select nama from hariaktif where id_hari=$idh and id_fak=$idf");
			  list($hari) = mysqli_fetch_row($rh);
			  if($paralel!='-'){ $prl = str_replace("."," - ",$paralel); }else{ $prl = $paralel; }
			
			$write .= "
			  <tr>
			    <td></td>
				<td>$dosen</td>
				<td>$matkul</td>
				<td>$sks</td>
				<td>$split</td>
				<td>$prl</td>";

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
				  <td colspan=\"7\">-</td>
			    </tr>";
			  }
	}
	$write .= "</table>
	</div>
	</body>
	</html>";
	
	$html2pdf->writeHTML($write);
	$html2pdf->Output("jk $prodi $smstr-$kls $tahun $aj.pdf");

  }
?>