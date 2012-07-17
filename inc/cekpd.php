<?php
  session_start();
  if((!isset($_SESSION['adm_prodi']) or !isset($_SESSION['id_ta'])) and (!isset($_SESSION['adm_fak']) or !isset($_SESSION['id_ta']))){
    header("Location:../index.php");
	exit();
  }
  
  if(isset($_POST['submitted'])){
    
	if(isset($_SESSION['adm_prodi'])){
	
    require_once('../mysqli_connect.php');
	if(isset($_POST['tim'])){ $tim = $_POST['tim']; }
	$nod = $_POST['nod'];
	$subnod = $_POST['subnod'];
	$idm = substr($_POST['idm'], 1);
	$sks = substr($_POST['idm'], 0, 1);
	$idk = $_POST['idk'];
	
	if(isset($_POST['tim']) and $nod!='-' and $idm!='-' and $idk!='-'){
	
	  if($subnod=='-'){
	    echo'incomplete';
	  }elseif($subnod==$nod){
	    echo'subnod-struck';
	  }else{
	    $nods = array($nod, $subnod);
		$struck = 0;
		for($i=0;$i<2;$i++){
		  $qc = "select * from jadwal where (id_prodi={$_SESSION['adm_prodi']} and id_TA={$_SESSION['id_ta']} and id_matkul=$idm and id_kls=$idk) or (id_prodi={$_SESSION['adm_prodi']} and id_TA={$_SESSION['id_ta']} and nod={$nods[$i]} and id_matkul=$idm and id_kls=$idk) or (id_prodi={$_SESSION['adm_prodi']} and id_TA={$_SESSION['id_ta']} and nods={$nods[$i]} and id_matkul=$idm and id_kls=$idk) limit 1";
		  $rc = mysqli_query($dbc, $qc);
		  if(mysqli_num_rows($rc)>0){ $struck += 1; }
		}
		
		if(isset($_POST['paralel']) and $_POST['paralel']!='-' and $struck==0){
		
		  $prl = $_POST['paralel'];
		  $c = substr_count($prl, '.');
		  for($i=0;$i<=$c;$i++){
		    $sks = explode('.', $prl, $c+1);
			$q = "insert into jadwal(id_univ, id_fak, id_prodi, id_TA, tim, nod, nods, id_matkul, paralel, sks, id_kls) 
		    values({$_SESSION['idpu']}, {$_SESSION['idpf']}, {$_SESSION['adm_prodi']}, {$_SESSION['id_ta']}, $tim, {$nods[0]}, {$nods[1]}, $idm, '$prl', {$sks[$i]}, $idk)";
		    mysqli_query($dbc, $q);
		  }
	      echo'success';
		  
		}elseif($struck==0){
		  
		  $q = "insert into jadwal(id_univ, id_fak, id_prodi, id_TA, tim, nod, nods, id_matkul, paralel, sks, id_kls) 
		  values({$_SESSION['idpu']}, {$_SESSION['idpf']}, {$_SESSION['adm_prodi']}, {$_SESSION['id_ta']}, $tim, {$nods[0]}, {$nods[1]}, $idm, '-', $sks, $idk)";
		  mysqli_query($dbc, $q);
		  echo'success';
	      
		}else{
		  echo'pd-struck';
		}
		
	  }
	  
	}elseif($nod!='-' and $idm!='-' and $idk!='-'){
	  
	  if(isset($_POST['paralel']) and $_POST['paralel']!='-'){
		
		$qc = "select * from jadwal where (id_prodi={$_SESSION['adm_prodi']} and id_TA={$_SESSION['id_ta']} and id_matkul=$idm and id_kls=$idk) or (id_prodi={$_SESSION['adm_prodi']} and id_TA={$_SESSION['id_ta']} and nod=$nod and id_matkul=$idm and id_kls=$idk) or (id_prodi={$_SESSION['adm_prodi']} and id_TA={$_SESSION['id_ta']} and nods=$nod and id_matkul=$idm and id_kls=$idk) limit 1";
	    $rc = mysqli_query($dbc, $qc);
	    if(mysqli_num_rows($rc)>0){
	      echo'pd-struck';
	    }else{
		  $prl = $_POST['paralel'];
		  $c = substr_count($prl, '.');
		  for($i=0;$i<=$c;$i++){
		    $sks = explode('.', $prl, $c+1);
			$q = "insert into jadwal(id_univ, id_fak, id_prodi, id_TA, nod, id_matkul, paralel, sks, id_kls) 
		    values({$_SESSION['idpu']}, {$_SESSION['idpf']}, {$_SESSION['adm_prodi']}, {$_SESSION['id_ta']}, $nod, $idm, '$prl', {$sks[$i]}, $idk)";
		    mysqli_query($dbc, $q);
		  }
	      echo'success';
	    }
		
	  }else{
	  
	    $qc = "select * from jadwal where (id_prodi={$_SESSION['adm_prodi']} and id_TA={$_SESSION['id_ta']} and id_matkul=$idm and id_kls=$idk) or (id_prodi={$_SESSION['adm_prodi']} and id_TA={$_SESSION['id_ta']} and nod=$nod and id_matkul=$idm and id_kls=$idk) or (id_prodi={$_SESSION['adm_prodi']} and id_TA={$_SESSION['id_ta']} and nods=$nod and id_matkul=$idm and id_kls=$idk) limit 1";
	    $rc = mysqli_query($dbc, $qc);
	    if(mysqli_num_rows($rc)>0){
	      echo'pd-struck';
	    }else{
	      $q = "insert into jadwal(id_univ, id_fak, id_prodi, id_TA, nod, id_matkul, paralel, sks, id_kls) 
		  values({$_SESSION['idpu']}, {$_SESSION['idpf']}, {$_SESSION['adm_prodi']}, {$_SESSION['id_ta']}, $nod, $idm, '-', $sks, $idk)";
		  mysqli_query($dbc, $q);
		  echo'success';
	    }
	  
	  }
	  
	}else{
	  echo'incomplete';
	}
	
	
	}elseif(isset($_SESSION['adm_fak'])){
	
	require_once('../mysqli_connect.php');
	if(isset($_POST['tim'])){ $tim = $_POST['tim']; }
	$nod = $_POST['nod'];
	$subnod = $_POST['subnod'];
	$idm = substr($_POST['idm'], 1);
	$sks = substr($_POST['idm'], 0, 1);
	$idk = $_POST['idk'];
	
	if(isset($_POST['tim']) and $nod!='-' and $idm!='-' and $idk!='-'){
	
	  if($subnod=='-'){
	    echo'incomplete';
	  }elseif($subnod==$nod){
	    echo'subnod-struck';
	  }else{
	    $nods = array($nod, $subnod);
		$struck = 0;
		for($i=0;$i<2;$i++){
		  $qc = "select * from jadwal where (id_prodi={$_SESSION['idpx']} and id_TA={$_SESSION['id_ta']} and id_matkul=$idm and id_kls=$idk) or (id_prodi={$_SESSION['idpx']} and id_TA={$_SESSION['id_ta']} and nod={$nods[$i]} and id_matkul=$idm and id_kls=$idk) or (id_prodi={$_SESSION['idpx']} and id_TA={$_SESSION['id_ta']} and nods={$nods[$i]} and id_matkul=$idm and id_kls=$idk) limit 1";
		  $rc = mysqli_query($dbc, $qc);
		  if(mysqli_num_rows($rc)>0){ $struck += 1; }
		}
		
		if(isset($_POST['paralel']) and $_POST['paralel']!='-' and $struck==0){
		
		  $prl = $_POST['paralel'];
		  $c = substr_count($prl, '.');
		  for($i=0;$i<=$c;$i++){
		    $sks = explode('.', $prl, $c+1);
			$q = "insert into jadwal(id_univ, id_fak, id_prodi, id_TA, tim, nod, nods, id_matkul, paralel, sks, id_kls) 
		    values({$_SESSION['idfu']}, {$_SESSION['adm_fak']}, {$_SESSION['idpx']}, {$_SESSION['id_ta']}, $tim, {$nods[0]}, {$nods[1]}, $idm, '$prl', {$sks[$i]}, $idk)";
		    mysqli_query($dbc, $q);
		  }
	      echo'success';
		  
		}elseif($struck==0){
		  
		  $q = "insert into jadwal(id_univ, id_fak, id_prodi, id_TA, tim, nod, nods, id_matkul, paralel, sks, id_kls) 
		  values({$_SESSION['idfu']}, {$_SESSION['adm_fak']}, {$_SESSION['idpx']}, {$_SESSION['id_ta']}, $tim, {$nods[0]}, {$nods[1]}, $idm, '-', $sks, $idk)";
		  mysqli_query($dbc, $q);
		  echo'success';
	      
		}else{
		  echo'pd-struck';
		}
		
	  }
	  
	}elseif($nod!='-' and $idm!='-' and $idk!='-'){
	  
	  if(isset($_POST['paralel']) and $_POST['paralel']!='-'){
		
		$qc = "select * from jadwal where (id_prodi={$_SESSION['idpx']} and id_TA={$_SESSION['id_ta']} and id_matkul=$idm and id_kls=$idk) or (id_prodi={$_SESSION['idpx']} and id_TA={$_SESSION['id_ta']} and nod=$nod and id_matkul=$idm and id_kls=$idk) or (id_prodi={$_SESSION['idpx']} and id_TA={$_SESSION['id_ta']} and nods=$nod and id_matkul=$idm and id_kls=$idk) limit 1";
	    $rc = mysqli_query($dbc, $qc);
	    if(mysqli_num_rows($rc)>0){
	      echo'pd-struck';
	    }else{
		  $prl = $_POST['paralel'];
		  $c = substr_count($prl, '.');
		  for($i=0;$i<=$c;$i++){
		    $sks = explode('.', $prl, $c+1);
			$q = "insert into jadwal(id_univ, id_fak, id_prodi, id_TA, nod, id_matkul, paralel, sks, id_kls) 
		    values({$_SESSION['idfu']}, {$_SESSION['adm_fak']}, {$_SESSION['idpx']}, {$_SESSION['id_ta']}, $nod, $idm, '$prl', {$sks[$i]}, $idk)";
		    mysqli_query($dbc, $q);
		  }
	      echo'success';
	    }
		
	  }else{
	  
	    $qc = "select * from jadwal where (id_prodi={$_SESSION['idpx']} and id_TA={$_SESSION['id_ta']} and id_matkul=$idm and id_kls=$idk) or (id_prodi={$_SESSION['idpx']} and id_TA={$_SESSION['id_ta']} and nod=$nod and id_matkul=$idm and id_kls=$idk) or (id_prodi={$_SESSION['idpx']} and id_TA={$_SESSION['id_ta']} and nods=$nod and id_matkul=$idm and id_kls=$idk) limit 1";
	    $rc = mysqli_query($dbc, $qc);
	    if(mysqli_num_rows($rc)>0){
	      echo'pd-struck';
	    }else{
	      $q = "insert into jadwal(id_univ, id_fak, id_prodi, id_TA, nod, id_matkul, paralel, sks, id_kls) 
		  values({$_SESSION['idfu']}, {$_SESSION['adm_fak']}, {$_SESSION['idpx']}, {$_SESSION['id_ta']}, $nod, $idm, '-', $sks, $idk)";
		  mysqli_query($dbc, $q);
		  echo'success';
	    }
	  
	  }
	  
	}else{
	  echo'incomplete';
	}
	
	}
	
  }
?>