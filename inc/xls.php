<?php
session_start();
if((!isset($_SESSION['adm_prodi']) or !isset($_GET['aj'])) and (!isset($_SESSION['adm_fak']) or !isset($_GET['aj']))){
  header("Location:../index.php");
}else{

  require_once('../mysqli_connect.php');

  if(isset($_SESSION['adm_prodi'])){
    
	$rf = mysqli_query($dbc, "select f.nama from fakultas as f join prodi as p using(id_fak) where p.id_prodi={$_SESSION['adm_prodi']}");
	list($fak) = mysqli_fetch_row($rf);
	$rta = mysqli_query($dbc, "select tahun from ta where id_TA={$_SESSION['id_ta']}");
	list($tahun) = mysqli_fetch_row($rta);
	if($_GET['aj']==1){$aj = "Ganjil";}else{$aj="Genap";}
	
	$filename = "jk/$fak/{$_SESSION['prodi']}/$tahun-$aj.xls";
    header('Content-type: application/ms-excel');
    header('Content-Disposition: attachment; filename='.$filename);
	
    echo"No. \t Dosen \t Matakuliah \t SKS \t Split \t Paralel \t Semester \t Kelas \t Hari \t Jam Kuliah \t lokal \n";
	
	$sqlP = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		  join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['adm_prodi']} order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
	$resP = mysqli_query($dbc, $sqlP);
	
	$no = 1;
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
			
			  if($idh!=0){$whari = "$hari";}else{$whari = "-";}
			  if($sJam!=0){
				if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
			    if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
			    if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
			    if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
				$wjam = $sJam.' - '.$eJam.' / '.$mjl.':'.$mml.' - '.$sjl.':'.$sml;
			  }else{
			    $wjam = '-';
			  }
			  if($idl!=0){
			    $ql = "select nama from lokal where id_lkl=$idl";
				$rl = mysqli_query($dbc, $ql);
				list($lokal) = mysqli_fetch_row($rl);
				$wlokal = "$lokal";
			  }else{
				$wlokal = '-';
			  }
			  
			$write = $no++ ." \t $dosen \t $matkul \t $sks \t $split \t $prl \t $smstr \t $kelas \t $whari \t $wjam \t $wlokal \t";
			echo "$write \n";
	}
	
  }elseif(isset($_SESSION['adm_fak'])){
  
    $r = mysqli_query($dbc, "select nama from prodi where id_prodi={$_SESSION['idpx']}");
	list($prodif)=mysqli_fetch_row($r); 
	  
	$rta = mysqli_query($dbc, "select tahun from ta where id_TA={$_SESSION['id_ta']}");
	list($tahun) = mysqli_fetch_row($rta);
	if($_GET['aj']==1){$aj = "Ganjil";}else{$aj="Genap";}
	
	$filename = "jk/{$_SESSION['fak']}/$prodif/$tahun-$aj.xls";
    header('Content-type: application/ms-excel');
    header('Content-Disposition: attachment; filename='.$filename);
	
    echo"No. \t Dosen \t Matakuliah \t SKS \t Split \t Paralel \t Semester \t Kelas \t Hari \t Jam Kuliah \t lokal \n";
	
	$sqlP = "select j.id_jad, j.nod, j.nods, j.id_matkul, j.sks, j.paralel, j.id_kls, j.id_hari, j.jk_start, j.mulai_jam, j.mulai_menit, j.jk_end, j.selesai_jam, j.selesai_menit, j.id_lkl from jadwal as j 
		  join kelas as k using(id_kls) where j.id_TA={$_SESSION['id_ta']} and j.id_prodi={$_SESSION['idpx']} order by k.smstr, k.nama, j.id_hari, j.jk_start asc";
		  $resP = mysqli_query($dbc, $sqlP);
	
	$no = 1;
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
			
			  if($idh!=0){$whari = "$hari";}else{$whari= "-";}
			  if($sJam!=0){
				if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
			    if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
			    if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
			    if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
				$wjam = $sJam.' - '.$eJam.' / '.$mjl.':'.$mml.' - '.$sjl.':'.$sml;
			  }else{
			    $wjam = '-';
			  }
			  if($idl!=0){
			    $ql = "select nama from lokal where id_lkl=$idl";
				$rl = mysqli_query($dbc, $ql);
				list($lokal) = mysqli_fetch_row($rl);
				$wlokal = "$lokal";
			  }else{
				$wlokal = '-';
			  }
			
			$write = $no++ ." \t $dosen \t $matkul \t $sks \t $split \t $prl \t $smstr \t $kelas \t $whari \t $wjam \t $wlokal \t";
			echo "$write \n";
			
	}
	  
  }
  
}
?>

<?php
  
?>