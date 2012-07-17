<?php
session_start();
if((!isset($_SESSION['adm_prodi']) or !isset($_SESSION['id_ta'])) and (!isset($_SESSION['adm_fak']) or !isset($_SESSION['id_ta']))){
  header("Location:../index.php");
}

if($_POST['idh']){	
  
  if(isset($_SESSION['adm_prodi'])){
  
  require_once('../mysqli_connect.php'); 
  if($_POST['idh']=='-'){
    echo'<option value="-">-</option>';
  }else{
    
	$cSubstr = substr_count($_POST['idh'], '-');
	if($cSubstr == 4){
	  list($sks, $idh, $nod, $idk, $idjad) = explode('-', $_POST['idh']);
	  $qn = "select id_fak from dosen where nod=$nod";
	  $rn = mysqli_query($dbc, $qn);
	  list($idfn) = mysqli_fetch_row($rn);
	}else{
	  list($sks, $idh, $nod, $nods, $idk, $idjad) = explode('-', $_POST['idh']);
	  $qn = "select id_fak from dosen where nod=$nod";
	  $rn = mysqli_query($dbc, $qn);
	  $qns = "select id_fak from dosen where nod=$nods";
	  $rns = mysqli_query($dbc, $qns);
	  list($idfn) = mysqli_fetch_row($rn);
	  list($idfns) = mysqli_fetch_row($rns);
	}
	
    if($sks==0 or $sks==1){ $sks=2; }
    $cJam = 0;
  
    $q = "select jam_kul from tabeljam where id_fak={$_SESSION['idpf']} and id_TA={$_SESSION['id_ta']} order by jam_kul asc";
    $r = mysqli_query($dbc, $q);
    while(list($jk) = mysqli_fetch_row($r)){ $cJam += 1; }
  
    $jPart = intval((($cJam-$sks)+1));
    $minSKS = $sks - 1;
	  
	echo'<option value="-">-</option>';
	
	if(((isset($idfn) and !isset($idfns)) and $idfn==$_SESSION['idpf']) or ((isset($idfn) and isset($idfns)) and ($idfn==$_SESSION['idpf'] and $idfns==$_SESSION['idpf']))){
	  
	  if(isset($nod) and !isset($nods)){
	    
		$rjx = mysqli_query($dbc, "select jk_start, mulai_jam, mulai_menit, jk_end, selesai_jam, selesai_menit, id_hari from jadwal where id_jad=$idjad");
		list($jsx, $mjx, $mmx, $jex, $sjx, $smx, $idhx) = mysqli_fetch_row($rjx);
		if($jsx!=0 and $idhx==$idh){
		  if(strlen($mjx)==1){ $mjl = 0 . $mjx; }else{ $mjl = $mjx; }
		  if(strlen($mmx)==1){ $mml = $mmx . 0; }else{ $mml = $mmx; }
		  if(strlen($sjx)==1){ $sjl = 0 . $sjx; }else{ $sjl = $sjx; }
		  if(strlen($smx)==1){ $sml = $smx . 0; }else{ $sml = $smx; }
	      echo'<option value="'.$nod.'-'.$jsx.'-'.$jex.'-'.$idh.'-'.$mjx.'-'.$mmx.'-'.$sjx.'-'.$smx.'-'.$idk.'-'.$idjad.'" style="background:#606060; color:white;">'.$jsx.' - '.$jex.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</option>';
		}
		
	    for($i=1;$i<=$jPart;$i++){
	  
          $eJam = ($i+$sks)-1;
          $sJam = $i;
	      $range = range($sJam, $eJam);
	      $cIDL = 0; $struck = 0; $pdStruck = 0; $kStruck = 0;
	  
	      $ql = "select id_lkl from lokal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']}";
	      $rl = mysqli_query($dbc, $ql);
	      while(list($idl) = mysqli_fetch_row($rl)){
	       $cIDL += 1;
		    while(list($k, $qRange) = each($range)){
		      $q = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} and id_hari=$idh and id_lkl=$idl and ($qRange between jk_start and jk_end)";
		      $r = mysqli_query($dbc, $q);
		      if(mysqli_num_rows($r)>0){
		        $struck += 1;
			    break;
		      }else{
			    $qn = "select * from jadwal where id_TA={$_SESSION['id_ta']} and (nod=$nod or nods=$nod) and id_hari=$idh and ($qRange between jk_start and jk_end)";
		        $rn = mysqli_query($dbc, $qn);
			    if(mysqli_num_rows($rn)){
			      $pdStruck += 1;
				  break;
			    }else{
				  $qk = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_kls=$idk and id_hari=$idh and ($qRange between jk_start and jk_end)";
		          $rk = mysqli_query($dbc, $qk);
				  if(mysqli_num_rows($rk)>0){
				    $kStruck += 1;
					break;
				  }
				}
			  }
		    }
	      }
	  
	      if(($struck!=$cIDL) and ($pdStruck==0) and ($kStruck==0)){
	        $qjs = "select mulai_jam, mulai_menit from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} and jam_kul=$sJam";
		    $rjs = mysqli_query($dbc, $qjs);
		    $qje = "select selesai_jam, selesai_menit from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} and jam_kul=$eJam";
		    $rje = mysqli_query($dbc, $qje);
		    list($mj, $mm) = mysqli_fetch_row($rjs);
		    list($sj, $sm) = mysqli_fetch_row($rje);
		    if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
		    if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
		    if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
		    if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
	        echo'<option value="'.$nod.'-'.$sJam.'-'.$eJam.'-'.$idh.'-'.$mj.'-'.$mm.'-'.$sj.'-'.$sm.'-'.$idk.'-'.$idjad.'">'.$sJam.' - '.$eJam.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</option>';
	      }
	  
        }
		
	  }else{
	  
	    $rjx = mysqli_query($dbc, "select jk_start, mulai_jam, mulai_menit, jk_end, selesai_jam, selesai_menit, id_hari from jadwal where id_jad=$idjad");
		list($jsx, $mjx, $mmx, $jex, $sjx, $smx, $idhx) = mysqli_fetch_row($rjx);
		if($jsx!=0 and $idhx==$idh){
		  if(strlen($mjx)==1){ $mjl = 0 . $mjx; }else{ $mjl = $mjx; }
		  if(strlen($mmx)==1){ $mml = $mmx . 0; }else{ $mml = $mmx; }
		  if(strlen($sjx)==1){ $sjl = 0 . $sjx; }else{ $sjl = $sjx; }
		  if(strlen($smx)==1){ $sml = $smx . 0; }else{ $sml = $smx; }
	      echo'<option value="'.$nod.'-'.$nods.'-'.$jsx.'-'.$jex.'-'.$idh.'-'.$mjx.'-'.$mmx.'-'.$sjx.'-'.$smx.'-'.$idk.'-'.$idjad.'" style="background:#606060; color:white;">'.$jsx.' - '.$jex.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</option>';
		}
	  
	    for($i=1;$i<=$jPart;$i++){
	  
          $eJam = ($i+$sks)-1;
          $sJam = $i;
	      $range = range($sJam, $eJam);
	      $cIDL = 0; $struck = 0; $pdStruck = 0; $pdStrucks = 0; $kStruck = 0;
	  
	      $ql = "select id_lkl from lokal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']}";
	      $rl = mysqli_query($dbc, $ql);
	      while(list($idl) = mysqli_fetch_row($rl)){
	       $cIDL += 1;
		    while(list($k, $qRange) = each($range)){
		      $q = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} and id_hari=$idh and id_lkl=$idl and ($qRange between jk_start and jk_end)";
		      $r = mysqli_query($dbc, $q);
		      if(mysqli_num_rows($r)>0){
		        $struck += 1;
			    break;
		      }else{
			    $qn = "select * from jadwal where id_TA={$_SESSION['id_ta']} and (nod=$nod or nods=$nod) and id_hari=$idh and ($qRange between jk_start and jk_end)";
		        $rn = mysqli_query($dbc, $qn);
			    if(mysqli_num_rows($rn)){
			      $pdStruck += 1;
				  break;
			    }else{
				  $qns = "select * from jadwal where id_TA={$_SESSION['id_ta']} and (nod=$nods or nods=$nods) and id_hari=$idh and ($qRange between jk_start and jk_end)";
		          $rns = mysqli_query($dbc, $qns);
				  if(mysqli_num_rows($rns)>0){
				    $pdStrucks += 1;
					break;
				  }else{
				    $qk = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_kls=$idk and id_hari=$idh and ($qRange between jk_start and jk_end)";
		            $rk = mysqli_query($dbc, $qk);
				    if(mysqli_num_rows($rk)>0){
				      $kStruck += 1;
					  break;
				    }
				  }
				}
			  }
		    }
	      }
	  
	      if(($struck!=$cIDL) and ($pdStruck==0) and ($pdStrucks==0) and ($kStruck==0)){
	        $qjs = "select mulai_jam, mulai_menit from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} and jam_kul=$sJam";
		    $rjs = mysqli_query($dbc, $qjs);
		    $qje = "select selesai_jam, selesai_menit from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} and jam_kul=$eJam";
		    $rje = mysqli_query($dbc, $qje);
		    list($mj, $mm) = mysqli_fetch_row($rjs);
		    list($sj, $sm) = mysqli_fetch_row($rje);
		    if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
		    if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
		    if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
		    if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
	        echo'<option value="'.$nod.'-'.$nods.'-'.$sJam.'-'.$eJam.'-'.$idh.'-'.$mj.'-'.$mm.'-'.$sj.'-'.$sm.'-'.$idk.'-'.$idjad.'">'.$sJam.' - '.$eJam.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</option>';
	      }
	  
        }
	  
	  }
	
	}else{
	
	  if(isset($nod) and !isset($nods)){
	  
	    $rjx = mysqli_query($dbc, "select jk_start, mulai_jam, mulai_menit, jk_end, selesai_jam, selesai_menit, id_hari from jadwal where id_jad=$idjad");
		list($jsx, $mjx, $mmx, $jex, $sjx, $smx, $idhx) = mysqli_fetch_row($rjx);
		if($jsx!=0 and $idhx==$idh){
		  if(strlen($mjx)==1){ $mjl = 0 . $mjx; }else{ $mjl = $mjx; }
		  if(strlen($mmx)==1){ $mml = $mmx . 0; }else{ $mml = $mmx; }
		  if(strlen($sjx)==1){ $sjl = 0 . $sjx; }else{ $sjl = $sjx; }
		  if(strlen($smx)==1){ $sml = $smx . 0; }else{ $sml = $smx; }
	      echo'<option value="'.$nod.'-'.$jsx.'-'.$jex.'-'.$idh.'-'.$mjx.'-'.$mmx.'-'.$sjx.'-'.$smx.'-'.$idk.'-'.$idjad.'" style="background:#606060; color:white;">'.$jsx.' - '.$jex.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</option>';
		}
	  
	    for($i=1;$i<=$jPart;$i++){
	
          $eJam = ($i+$sks)-1;
          $sJam = $i;
	      $qjs = "select mulai_jam, mulai_menit from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} and jam_kul=$sJam";
	      $rjs = mysqli_query($dbc, $qjs);
	      $qje = "select selesai_jam, selesai_menit from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} and jam_kul=$eJam";
	      $rje = mysqli_query($dbc, $qje);
	      list($mj, $mm) = mysqli_fetch_row($rjs);
	      list($sj, $sm) = mysqli_fetch_row($rje);
	      $start = (100 * $mj) + $mm + 1;
	      $end = (100 * $sj) + $sm;
	      $range = range($start, $end);
	      $dbStart = "(mulai_jam*100)+mulai_menit";
          $dbEnd = "(selesai_jam*100)+selesai_menit";
	      $cIDL = 0; $struck = 0; $pdStruck = 0; $kStruck = 0;
		  		  
	      $ql = "select id_lkl from lokal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']}";
	      $rl = mysqli_query($dbc, $ql);
	      while(list($idl) = mysqli_fetch_row($rl)){
	        $cIDL += 1;
		    while(list($k, $qRange) = each($range)){
		      $q = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} and id_hari=$idh and id_lkl=$idl and ($qRange between $dbStart and $dbEnd)";
		      $r = mysqli_query($dbc, $q);
		      if(mysqli_num_rows($r)>0){
		        $struck += 1;
			    break;
		      }else{
			    $qn = "select * from jadwal where id_TA={$_SESSION['id_ta']} and (nod=$nod or nods=$nod) and id_hari=$idh and ($qRange between $dbStart and $dbEnd)";
		        $rn = mysqli_query($dbc, $qn);
			    if(mysqli_num_rows($rn)){
			      $pdStruck += 1;
				  break;
			    }else{
				  $qk = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_kls=$idk and id_hari=$idh and ($qRange between jk_start and jk_end)";
		          $rk = mysqli_query($dbc, $qk);
				  if(mysqli_num_rows($rk)>0){
				    $kStruck += 1;
					break;
				  }
				}
			  }
		    }
	      }
	  
	      if(($struck!=$cIDL) and ($pdStruck==0) and ($kStruck==0)){
		  
		    if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
		    if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
		    if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
		    if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
	        echo'<option value="'.$nod.'-'.$sJam.'-'.$eJam.'-'.$idh.'-'.$mj.'-'.$mm.'-'.$sj.'-'.$sm.'-'.$idk.'-'.$idjad.'">'.$sJam.' - '.$eJam.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</option>';
			
	      }
	  
        }
	  
	  }else{
	  
	    $rjx = mysqli_query($dbc, "select jk_start, mulai_jam, mulai_menit, jk_end, selesai_jam, selesai_menit, id_hari from jadwal where id_jad=$idjad");
		list($jsx, $mjx, $mmx, $jex, $sjx, $smx, $idhx) = mysqli_fetch_row($rjx);
		if($jsx!=0 and $idhx==$idh){
		  if(strlen($mjx)==1){ $mjl = 0 . $mjx; }else{ $mjl = $mjx; }
		  if(strlen($mmx)==1){ $mml = $mmx . 0; }else{ $mml = $mmx; }
		  if(strlen($sjx)==1){ $sjl = 0 . $sjx; }else{ $sjl = $sjx; }
		  if(strlen($smx)==1){ $sml = $smx . 0; }else{ $sml = $smx; }
	      echo'<option value="'.$nod.'-'.$nods.'-'.$jsx.'-'.$jex.'-'.$idh.'-'.$mjx.'-'.$mmx.'-'.$sjx.'-'.$smx.'-'.$idk.'-'.$idjad.'" style="background:#606060; color:white;">'.$jsx.' - '.$jex.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</option>';
		}
	  
	    for($i=1;$i<=$jPart;$i++){
	
          $eJam = ($i+$sks)-1;
          $sJam = $i;
	      $qjs = "select mulai_jam, mulai_menit from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} and jam_kul=$sJam";
	      $rjs = mysqli_query($dbc, $qjs);
	      $qje = "select selesai_jam, selesai_menit from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} and jam_kul=$eJam";
	      $rje = mysqli_query($dbc, $qje);
	      list($mj, $mm) = mysqli_fetch_row($rjs);
	      list($sj, $sm) = mysqli_fetch_row($rje);
	      $start = (100 * $mj) + $mm + 1;
	      $end = (100 * $sj) + $sm;
	      $range = range($start, $end);
	      $dbStart = "(mulai_jam*100)+mulai_menit";
          $dbEnd = "(selesai_jam*100)+selesai_menit";
	      $cIDL = 0; $struck = 0; $pdStruck = 0; $pdStrucks = 0; $kStruck = 0;
		  		  
	      $ql = "select id_lkl from lokal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']}";
	      $rl = mysqli_query($dbc, $ql);
	      while(list($idl) = mysqli_fetch_row($rl)){
	        $cIDL += 1;
		    while(list($k, $qRange) = each($range)){
		      $q = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} and id_hari=$idh and id_lkl=$idl and ($qRange between $dbStart and $dbEnd)";
		      $r = mysqli_query($dbc, $q);
		      if(mysqli_num_rows($r)>0){
		        $struck += 1;
			    break;
		      }else{
			    $qn = "select * from jadwal where id_TA={$_SESSION['id_ta']} and (nod=$nod or nods=$nod) and id_hari=$idh and ($qRange between $dbStart and $dbEnd)";
		        $rn = mysqli_query($dbc, $qn);
			    if(mysqli_num_rows($rn)){
			      $pdStruck += 1;
				  break;
			    }else{
				  $qns = "select * from jadwal where id_TA={$_SESSION['id_ta']} and (nod=$nods or nods=$nods) and id_hari=$idh and ($qRange between $dbStart and $dbEnd)";
		          $rns = mysqli_query($dbc, $qns);
				  if(mysqli_num_rows($rns)>0){
				    $pdStrucks += 1;
					break;
				  }else{
				    $qk = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_kls=$idk and id_hari=$idh and ($qRange between jk_start and jk_end)";
		            $rk = mysqli_query($dbc, $qk);
				    if(mysqli_num_rows($rk)>0){
				      $kStruck += 1;
					  break;
				    }
				  }
				}
			  }
		    }
	      }
	  
	      if(($struck!=$cIDL) and ($pdStruck==0) and ($pdStrucks==0) and ($kStruck==0)){
		  
		    if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
		    if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
		    if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
		    if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
	        echo'<option value="'.$nod.'-'.$nods.'-'.$sJam.'-'.$eJam.'-'.$idh.'-'.$mj.'-'.$mm.'-'.$sj.'-'.$sm.'-'.$idk.'-'.$idjad.'">'.$sJam.' - '.$eJam.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</option>';
			
	      }
	  
        }
	  
	  }
      
	} 
  
  }
 
  }elseif(isset($_SESSION['adm_fak'])){
  
  require_once('../mysqli_connect.php'); 
  if($_POST['idh']=='-'){
    echo'<option value="-">-</option>';
  }else{
    
	$cSubstr = substr_count($_POST['idh'], '-');
	if($cSubstr == 4){
	  list($sks, $idh, $nod, $idk, $idjad) = explode('-', $_POST['idh']);
	  $qn = "select id_fak from dosen where nod=$nod";
	  $rn = mysqli_query($dbc, $qn);
	  list($idfn) = mysqli_fetch_row($rn);
	}else{
	  list($sks, $idh, $nod, $nods, $idk, $idjad) = explode('-', $_POST['idh']);
	  $qn = "select id_fak from dosen where nod=$nod";
	  $rn = mysqli_query($dbc, $qn);
	  $qns = "select id_fak from dosen where nod=$nods";
	  $rns = mysqli_query($dbc, $qns);
	  list($idfn) = mysqli_fetch_row($rn);
	  list($idfns) = mysqli_fetch_row($rns);
	}
	
    if($sks==0 or $sks==1){ $sks=2; }
    $cJam = 0;
  
    $q = "select jam_kul from tabeljam where id_fak={$_SESSION['adm_fak']} and id_TA={$_SESSION['id_ta']} order by jam_kul asc";
    $r = mysqli_query($dbc, $q);
    while(list($jk) = mysqli_fetch_row($r)){ $cJam += 1; }
  
    $jPart = intval((($cJam-$sks)+1));
    $minSKS = $sks - 1;
	  
	echo'<option value="-">-</option>';
	
	if(((isset($idfn) and !isset($idfns)) and $idfn==$_SESSION['adm_fak']) or ((isset($idfn) and isset($idfns)) and ($idfn==$_SESSION['adm_fak'] and $idfns==$_SESSION['adm_fak']))){
	  
	  if(isset($nod) and !isset($nods)){
	    
		$rjx = mysqli_query($dbc, "select jk_start, mulai_jam, mulai_menit, jk_end, selesai_jam, selesai_menit, id_hari from jadwal where id_jad=$idjad");
		list($jsx, $mjx, $mmx, $jex, $sjx, $smx, $idhx) = mysqli_fetch_row($rjx);
		if($jsx!=0 and $idhx==$idh){
		  if(strlen($mjx)==1){ $mjl = 0 . $mjx; }else{ $mjl = $mjx; }
		  if(strlen($mmx)==1){ $mml = $mmx . 0; }else{ $mml = $mmx; }
		  if(strlen($sjx)==1){ $sjl = 0 . $sjx; }else{ $sjl = $sjx; }
		  if(strlen($smx)==1){ $sml = $smx . 0; }else{ $sml = $smx; }
	      echo'<option value="'.$nod.'-'.$jsx.'-'.$jex.'-'.$idh.'-'.$mjx.'-'.$mmx.'-'.$sjx.'-'.$smx.'-'.$idk.'-'.$idjad.'" style="background:#606060; color:white;">'.$jsx.' - '.$jex.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</option>';
		}
	  
	    for($i=1;$i<=$jPart;$i++){
	  
          $eJam = ($i+$sks)-1;
          $sJam = $i;
	      $range = range($sJam, $eJam);
	      $cIDL = 0; $struck = 0; $pdStruck = 0; $kStruck = 0;
	  
	      $ql = "select id_lkl from lokal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']}";
	      $rl = mysqli_query($dbc, $ql);
	      while(list($idl) = mysqli_fetch_row($rl)){
	       $cIDL += 1;
		    while(list($k, $qRange) = each($range)){
		      $q = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} and id_hari=$idh and id_lkl=$idl and ($qRange between jk_start and jk_end)";
		      $r = mysqli_query($dbc, $q);
		      if(mysqli_num_rows($r)>0){
		        $struck += 1;
			    break;
		      }else{
			    $qn = "select * from jadwal where id_TA={$_SESSION['id_ta']} and (nod=$nod or nods=$nod) and id_hari=$idh and ($qRange between jk_start and jk_end)";
		        $rn = mysqli_query($dbc, $qn);
			    if(mysqli_num_rows($rn)){
			      $pdStruck += 1;
				  break;
			    }else{
				  $qk = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_kls=$idk and id_hari=$idh and ($qRange between jk_start and jk_end)";
		          $rk = mysqli_query($dbc, $qk);
				  if(mysqli_num_rows($rk)>0){
				    $kStruck += 1;
					break;
				  }
				}
			  }
		    }
	      }
	  
	      if(($struck!=$cIDL) and ($pdStruck==0) and ($kStruck==0)){
	        $qjs = "select mulai_jam, mulai_menit from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} and jam_kul=$sJam";
		    $rjs = mysqli_query($dbc, $qjs);
		    $qje = "select selesai_jam, selesai_menit from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} and jam_kul=$eJam";
		    $rje = mysqli_query($dbc, $qje);
		    list($mj, $mm) = mysqli_fetch_row($rjs);
		    list($sj, $sm) = mysqli_fetch_row($rje);
		    if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
		    if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
		    if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
		    if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
	        echo'<option value="'.$nod.'-'.$sJam.'-'.$eJam.'-'.$idh.'-'.$mj.'-'.$mm.'-'.$sj.'-'.$sm.'-'.$idk.'-'.$idjad.'">'.$sJam.' - '.$eJam.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</option>';
	      }
	  
        }
		
	  }else{
	  
	    $rjx = mysqli_query($dbc, "select jk_start, mulai_jam, mulai_menit, jk_end, selesai_jam, selesai_menit, id_hari from jadwal where id_jad=$idjad");
		list($jsx, $mjx, $mmx, $jex, $sjx, $smx, $idhx) = mysqli_fetch_row($rjx);
		if($jsx!=0 and $idhx==$idh){
		  if(strlen($mjx)==1){ $mjl = 0 . $mjx; }else{ $mjl = $mjx; }
		  if(strlen($mmx)==1){ $mml = $mmx . 0; }else{ $mml = $mmx; }
		  if(strlen($sjx)==1){ $sjl = 0 . $sjx; }else{ $sjl = $sjx; }
		  if(strlen($smx)==1){ $sml = $smx . 0; }else{ $sml = $smx; }
	      echo'<option value="'.$nod.'-'.$nods.'-'.$jsx.'-'.$jex.'-'.$idh.'-'.$mjx.'-'.$mmx.'-'.$sjx.'-'.$smx.'-'.$idk.'-'.$idjad.'" style="background:#606060; color:white;">'.$jsx.' - '.$jex.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</option>';
		}
	    
	    for($i=1;$i<=$jPart;$i++){
	  
          $eJam = ($i+$sks)-1;
          $sJam = $i;
	      $range = range($sJam, $eJam);
	      $cIDL = 0; $struck = 0; $pdStruck = 0; $pdStrucks = 0; $kStruck = 0;
	  
	      $ql = "select id_lkl from lokal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']}";
	      $rl = mysqli_query($dbc, $ql);
	      while(list($idl) = mysqli_fetch_row($rl)){
	       $cIDL += 1;
		    while(list($k, $qRange) = each($range)){
		      $q = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} and id_hari=$idh and id_lkl=$idl and ($qRange between jk_start and jk_end)";
		      $r = mysqli_query($dbc, $q);
		      if(mysqli_num_rows($r)>0){
		        $struck += 1;
			    break;
		      }else{
			    $qn = "select * from jadwal where id_TA={$_SESSION['id_ta']} and (nod=$nod or nods=$nod) and id_hari=$idh and ($qRange between jk_start and jk_end)";
		        $rn = mysqli_query($dbc, $qn);
			    if(mysqli_num_rows($rn)){
			      $pdStruck += 1;
				  break;
			    }else{
				  $qns = "select * from jadwal where id_TA={$_SESSION['id_ta']} and (nod=$nods or nods=$nods) and id_hari=$idh and ($qRange between jk_start and jk_end)";
		          $rns = mysqli_query($dbc, $qns);
				  if(mysqli_num_rows($rns)>0){
				    $pdStrucks += 1;
					break;
				  }else{
				    $qk = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_kls=$idk and id_hari=$idh and ($qRange between jk_start and jk_end)";
		            $rk = mysqli_query($dbc, $qk);
				    if(mysqli_num_rows($rk)>0){
				      $kStruck += 1;
					  break;
				    }
				  }
				}
			  }
		    }
	      }
	  
	      if(($struck!=$cIDL) and ($pdStruck==0) and ($pdStrucks==0) and ($kStruck==0)){
	        $qjs = "select mulai_jam, mulai_menit from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} and jam_kul=$sJam";
		    $rjs = mysqli_query($dbc, $qjs);
		    $qje = "select selesai_jam, selesai_menit from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} and jam_kul=$eJam";
		    $rje = mysqli_query($dbc, $qje);
		    list($mj, $mm) = mysqli_fetch_row($rjs);
		    list($sj, $sm) = mysqli_fetch_row($rje);
		    if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
		    if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
		    if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
		    if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
	        echo'<option value="'.$nod.'-'.$nods.'-'.$sJam.'-'.$eJam.'-'.$idh.'-'.$mj.'-'.$mm.'-'.$sj.'-'.$sm.'-'.$idk.'-'.$idjad.'">'.$sJam.' - '.$eJam.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</option>';
	      }
	  
        }
	  
	  }
	
	}else{
	
	  if(isset($nod) and !isset($nods)){
	    
		$rjx = mysqli_query($dbc, "select jk_start, mulai_jam, mulai_menit, jk_end, selesai_jam, selesai_menit, id_hari from jadwal where id_jad=$idjad");
		list($jsx, $mjx, $mmx, $jex, $sjx, $smx, $idhx) = mysqli_fetch_row($rjx);
		if($jsx!=0 and $idhx==$idh){
		  if(strlen($mjx)==1){ $mjl = 0 . $mjx; }else{ $mjl = $mjx; }
		  if(strlen($mmx)==1){ $mml = $mmx . 0; }else{ $mml = $mmx; }
		  if(strlen($sjx)==1){ $sjl = 0 . $sjx; }else{ $sjl = $sjx; }
		  if(strlen($smx)==1){ $sml = $smx . 0; }else{ $sml = $smx; }
	      echo'<option value="'.$nod.'-'.$jsx.'-'.$jex.'-'.$idh.'-'.$mjx.'-'.$mmx.'-'.$sjx.'-'.$smx.'-'.$idk.'-'.$idjad.'" style="background:#606060; color:white;">'.$jsx.' - '.$jex.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</option>';
		}
		
	    for($i=1;$i<=$jPart;$i++){
	
          $eJam = ($i+$sks)-1;
          $sJam = $i;
	      $qjs = "select mulai_jam, mulai_menit from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} and jam_kul=$sJam";
	      $rjs = mysqli_query($dbc, $qjs);
	      $qje = "select selesai_jam, selesai_menit from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} and jam_kul=$eJam";
	      $rje = mysqli_query($dbc, $qje);
	      list($mj, $mm) = mysqli_fetch_row($rjs);
	      list($sj, $sm) = mysqli_fetch_row($rje);
	      $start = (100 * $mj) + $mm + 1;
	      $end = (100 * $sj) + $sm;
	      $range = range($start, $end);
	      $dbStart = "(mulai_jam*100)+mulai_menit";
          $dbEnd = "(selesai_jam*100)+selesai_menit";
	      $cIDL = 0; $struck = 0; $pdStruck = 0; $kStruck = 0;
		  		  
	      $ql = "select id_lkl from lokal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']}";
	      $rl = mysqli_query($dbc, $ql);
	      while(list($idl) = mysqli_fetch_row($rl)){
	        $cIDL += 1;
		    while(list($k, $qRange) = each($range)){
		      $q = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} and id_hari=$idh and id_lkl=$idl and ($qRange between $dbStart and $dbEnd)";
		      $r = mysqli_query($dbc, $q);
		      if(mysqli_num_rows($r)>0){
		        $struck += 1;
			    break;
		      }else{
			    $qn = "select * from jadwal where id_TA={$_SESSION['id_ta']} and (nod=$nod or nods=$nod) and id_hari=$idh and ($qRange between $dbStart and $dbEnd)";
		        $rn = mysqli_query($dbc, $qn);
			    if(mysqli_num_rows($rn)){
			      $pdStruck += 1;
				  break;
			    }else{
				  $qk = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_kls=$idk and id_hari=$idh and ($qRange between jk_start and jk_end)";
		          $rk = mysqli_query($dbc, $qk);
				  if(mysqli_num_rows($rk)>0){
				    $kStruck += 1;
					break;
				  }
				}
			  }
		    }
	      }
	  
	      if(($struck!=$cIDL) and ($pdStruck==0) and ($kStruck==0)){
		  
		    if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
		    if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
		    if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
		    if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
	        echo'<option value="'.$nod.'-'.$sJam.'-'.$eJam.'-'.$idh.'-'.$mj.'-'.$mm.'-'.$sj.'-'.$sm.'-'.$idk.'-'.$idjad.'">'.$sJam.' - '.$eJam.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</option>';
			
	      }
	  
        }
	  
	  }else{
	  
	    $rjx = mysqli_query($dbc, "select jk_start, mulai_jam, mulai_menit, jk_end, selesai_jam, selesai_menit, id_hari from jadwal where id_jad=$idjad");
		list($jsx, $mjx, $mmx, $jex, $sjx, $smx, $idhx) = mysqli_fetch_row($rjx);
		if($jsx!=0 and $idhx==$idh){
		  if(strlen($mjx)==1){ $mjl = 0 . $mjx; }else{ $mjl = $mjx; }
		  if(strlen($mmx)==1){ $mml = $mmx . 0; }else{ $mml = $mmx; }
		  if(strlen($sjx)==1){ $sjl = 0 . $sjx; }else{ $sjl = $sjx; }
		  if(strlen($smx)==1){ $sml = $smx . 0; }else{ $sml = $smx; }
	      echo'<option value="'.$nod.'-'.$nods.'-'.$jsx.'-'.$jex.'-'.$idh.'-'.$mjx.'-'.$mmx.'-'.$sjx.'-'.$smx.'-'.$idk.'-'.$idjad.'" style="background:#606060; color:white;">'.$jsx.' - '.$jex.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</option>';
		}
	  
	    for($i=1;$i<=$jPart;$i++){
	
          $eJam = ($i+$sks)-1;
          $sJam = $i;
	      $qjs = "select mulai_jam, mulai_menit from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} and jam_kul=$sJam";
	      $rjs = mysqli_query($dbc, $qjs);
	      $qje = "select selesai_jam, selesai_menit from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} and jam_kul=$eJam";
	      $rje = mysqli_query($dbc, $qje);
	      list($mj, $mm) = mysqli_fetch_row($rjs);
	      list($sj, $sm) = mysqli_fetch_row($rje);
	      $start = (100 * $mj) + $mm + 1;
	      $end = (100 * $sj) + $sm;
	      $range = range($start, $end);
	      $dbStart = "(mulai_jam*100)+mulai_menit";
          $dbEnd = "(selesai_jam*100)+selesai_menit";
	      $cIDL = 0; $struck = 0; $pdStruck = 0; $pdStrucks = 0; $kStruck = 0;
		  		  
	      $ql = "select id_lkl from lokal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']}";
	      $rl = mysqli_query($dbc, $ql);
	      while(list($idl) = mysqli_fetch_row($rl)){
	        $cIDL += 1;
		    while(list($k, $qRange) = each($range)){
		      $q = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} and id_hari=$idh and id_lkl=$idl and ($qRange between $dbStart and $dbEnd)";
		      $r = mysqli_query($dbc, $q);
		      if(mysqli_num_rows($r)>0){
		        $struck += 1;
			    break;
		      }else{
			    $qn = "select * from jadwal where id_TA={$_SESSION['id_ta']} and (nod=$nod or nods=$nod) and id_hari=$idh and ($qRange between $dbStart and $dbEnd)";
		        $rn = mysqli_query($dbc, $qn);
			    if(mysqli_num_rows($rn)){
			      $pdStruck += 1;
				  break;
			    }else{
				  $qns = "select * from jadwal where id_TA={$_SESSION['id_ta']} and (nod=$nods or nods=$nods) and id_hari=$idh and ($qRange between $dbStart and $dbEnd)";
		          $rns = mysqli_query($dbc, $qns);
				  if(mysqli_num_rows($rns)>0){
				    $pdStrucks += 1;
					break;
				  }else{
				    $qk = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_kls=$idk and id_hari=$idh and ($qRange between jk_start and jk_end)";
		            $rk = mysqli_query($dbc, $qk);
				    if(mysqli_num_rows($rk)>0){
				      $kStruck += 1;
					  break;
				    }
				  }
				}
			  }
		    }
	      }
	  
	      if(($struck!=$cIDL) and ($pdStruck==0) and ($pdStrucks==0) and ($kStruck==0)){
		  
		    if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
		    if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
		    if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
		    if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
	        echo'<option value="'.$nod.'-'.$nods.'-'.$sJam.'-'.$eJam.'-'.$idh.'-'.$mj.'-'.$mm.'-'.$sj.'-'.$sm.'-'.$idk.'-'.$idjad.'">'.$sJam.' - '.$eJam.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</option>';
			
	      }
	  
        }
	  
	  }
      
	} 
  
  }
  
  }
  
}
?>