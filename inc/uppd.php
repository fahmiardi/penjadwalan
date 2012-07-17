<?php
session_start();
if((!isset($_SESSION['adm_prodi']) or !isset($_SESSION['id_ta'])) and (!isset($_SESSION['adm_fak']) or !isset($_SESSION['id_ta']))){
  header("Location:../index.php");
  exit();
}

if(isset($_POST['submitted'])){
  
  if(isset($_SESSION['adm_prodi'])){
  
  require('../mysqli_connect.php');
  if($_POST['idh']!='-' and $_POST['jk']!='-' and $_POST['idl']!='-'){
    
	$cSubstr = substr_count($_POST['jk'], '-');
	
	if($cSubstr == 9){
	
	  list($nod, $sJam, $eJam, $idh, $mj, $mm, $sj, $sm, $idk, $idjad) = explode('-', $_POST['jk']);
	  $idl = $_POST['idl'];
	  $idjad = $_POST['idjad'];
	  $range = range($sJam, $eJam);
	  $struck = 0; $pdStruck = 0; $kStruck = 0;
	  while(list($k, $qRange) = each($range)){
	    $q = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} and id_hari=$idh and id_lkl=$idl and ($qRange between jk_start and jk_end)";
	    $r = mysqli_query($dbc, $q);
	    if(mysqli_num_rows($r)>0){
	      $struck += 1;
	    }else{
		  $qn = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_jad!=$idjad and (nod=$nod or nods=$nod) and id_hari=$idh and ($qRange between jk_start and jk_end)";
		  $rn = mysqli_query($dbc, $qn);
		  if(mysqli_num_rows($rn)){
			$pdStruck += 1;
		  }else{
		    $qk = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_jad!=$idjad and id_kls=$idk and id_hari=$idh and ($qRange between jk_start and jk_end)";
		    $rk = mysqli_query($dbc, $qk);
			if(mysqli_num_rows($rk)>0){
			  $kStruck += 1;
			}
		  }
		}
	  }
	
	  if(($struck==0) and ($pdStruck==0) and ($kStruck==0)){
	    $sql= "update jadwal set jk_start=$sJam, mulai_jam=$mj, mulai_menit=$mm, jk_end=$eJam, selesai_jam=$sj, selesai_menit=$sm, id_hari=$idh, id_lkl=$idl where id_jad=$idjad";
	    $res = mysqli_query($dbc, $sql);
	    if(mysqli_affected_rows($dbc)>0){
	      echo'success';
	    }else{
	      echo'error';
	    }
	  }else{
	    echo'jad-struck';
	  }
	
	}else{
	
	  list($nod, $nods, $sJam, $eJam, $idh, $mj, $mm, $sj, $sm, $idk, $idjad) = explode('-', $_POST['jk']);
	  $idl = $_POST['idl'];
	  $idjad = $_POST['idjad'];
	  $range = range($sJam, $eJam);
	  $struck = 0; $pdStruck = 0; $pdStrucks = 0; $kStruck = 0;
	  while(list($k, $qRange) = each($range)){
	    $q = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} and id_hari=$idh and id_lkl=$idl and ($qRange between jk_start and jk_end)";
	    $r = mysqli_query($dbc, $q);
	    if(mysqli_num_rows($r)>0){
	      $struck += 1;
	    }else{
		  $qn = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_jad!=$idjad and (nod=$nod or nods=$nod) and id_hari=$idh and ($qRange between jk_start and jk_end)";
		  $rn = mysqli_query($dbc, $qn);
		  if(mysqli_num_rows($rn)){
			$pdStruck += 1;
			break;
		  }else{
		    $qns = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_jad!=$idjad and (nod=$nods or nods=$nods) and id_hari=$idh and ($qRange between jk_start and jk_end)";
		    $rns = mysqli_query($dbc, $qns);
		    if(mysqli_num_rows($rns)){
			  $pdStrucks += 1;
			  break;
		    }else{
			  $qk = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_jad!=$idjad and id_kls=$idk and id_hari=$idh and ($qRange between jk_start and jk_end)";
		      $rk = mysqli_query($dbc, $qk);
			  if(mysqli_num_rows($rk)>0){
				$kStruck += 1;
				break;
			  }
			}
		  }
		}
	  }
	
	  if(($struck==0) and ($pdStruck==0) and ($pdStrucks==0) and ($kStruck==0)){
	    $sql= "update jadwal set jk_start=$sJam, mulai_jam=$mj, mulai_menit=$mm, jk_end=$eJam, selesai_jam=$sj, selesai_menit=$sm, id_hari=$idh, id_lkl=$idl where id_jad=$idjad";
	    $res = mysqli_query($dbc, $sql);
	    if(mysqli_affected_rows($dbc)>0){
	      echo'success';
	    }else{
	      echo'error';
	    }
	  }else{
	    echo'jad-struck';
	  }
	
	}
	
  }else{
    echo'incomplete';
  }
  
  }elseif(isset($_SESSION['adm_fak'])){
  
  require('../mysqli_connect.php');
  if($_POST['idh']!='-' and $_POST['jk']!='-' and $_POST['idl']!='-'){
    
	$cSubstr = substr_count($_POST['jk'], '-');
	
	if($cSubstr == 9){
	
	  list($nod, $sJam, $eJam, $idh, $mj, $mm, $sj, $sm, $idk, $idjad) = explode('-', $_POST['jk']);
	  $idl = $_POST['idl'];
	  $idjad = $_POST['idjad'];
	  $range = range($sJam, $eJam);
	  $struck = 0; $pdStruck = 0; $kStruck = 0;
	  while(list($k, $qRange) = each($range)){
	    $q = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} and id_hari=$idh and id_lkl=$idl and ($qRange between jk_start and jk_end)";
	    $r = mysqli_query($dbc, $q);
	    if(mysqli_num_rows($r)>0){
	      $struck += 1;
	    }else{
		  $qn = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_jad!=$idjad and (nod=$nod or nods=$nod) and id_hari=$idh and ($qRange between jk_start and jk_end)";
		  $rn = mysqli_query($dbc, $qn);
		  if(mysqli_num_rows($rn)){
			$pdStruck += 1;
		  }else{
		    $qk = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_jad!=$idjad and id_kls=$idk and id_hari=$idh and ($qRange between jk_start and jk_end)";
		    $rk = mysqli_query($dbc, $qk);
			if(mysqli_num_rows($rk)>0){
			  $kStruck += 1;
			}
		  }
		}
	  }
	
	  if(($struck==0) and ($pdStruck==0) and ($kStruck==0)){
	    $sql= "update jadwal set jk_start=$sJam, mulai_jam=$mj, mulai_menit=$mm, jk_end=$eJam, selesai_jam=$sj, selesai_menit=$sm, id_hari=$idh, id_lkl=$idl where id_jad=$idjad";
	    $res = mysqli_query($dbc, $sql);
	    if(mysqli_affected_rows($dbc)>0){
	      echo'success';
	    }else{
	      echo'error';
	    }
	  }else{
	    echo'jad-struck';
	  }
	
	}else{
	
	  list($nod, $nods, $sJam, $eJam, $idh, $mj, $mm, $sj, $sm, $idk, $idjad) = explode('-', $_POST['jk']);
	  $idl = $_POST['idl'];
	  $idjad = $_POST['idjad'];
	  $range = range($sJam, $eJam);
	  $struck = 0; $pdStruck = 0; $pdStrucks = 0; $kStruck = 0;
	  while(list($k, $qRange) = each($range)){
	    $q = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} and id_hari=$idh and id_lkl=$idl and ($qRange between jk_start and jk_end)";
	    $r = mysqli_query($dbc, $q);
	    if(mysqli_num_rows($r)>0){
	      $struck += 1;
	    }else{
		  $qn = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_jad!=$idjad and (nod=$nod or nods=$nod) and id_hari=$idh and ($qRange between jk_start and jk_end)";
		  $rn = mysqli_query($dbc, $qn);
		  if(mysqli_num_rows($rn)){
			$pdStruck += 1;
			break;
		  }else{
		    $qns = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_jad!=$idjad and (nod=$nods or nods=$nods) and id_hari=$idh and ($qRange between jk_start and jk_end)";
		    $rns = mysqli_query($dbc, $qns);
		    if(mysqli_num_rows($rns)){
			  $pdStrucks += 1;
			  break;
		    }else{
			  $qk = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_jad!=$idjad and id_kls=$idk and id_hari=$idh and ($qRange between jk_start and jk_end)";
		      $rk = mysqli_query($dbc, $qk);
			  if(mysqli_num_rows($rk)>0){
				$kStruck += 1;
				break;
			  }
			}
		  }
		}
	  }
	
	  if(($struck==0) and ($pdStruck==0) and ($pdStrucks==0) and ($kStruck==0)){
	    $sql= "update jadwal set jk_start=$sJam, mulai_jam=$mj, mulai_menit=$mm, jk_end=$eJam, selesai_jam=$sj, selesai_menit=$sm, id_hari=$idh, id_lkl=$idl where id_jad=$idjad";
	    $res = mysqli_query($dbc, $sql);
	    if(mysqli_affected_rows($dbc)>0){
	      echo'success';
	    }else{
	      echo'error';
	    }
	  }else{
	    echo'jad-struck';
	  }
	
	}
	
  }else{
    echo'incomplete';
  }
  
  }
  
}
?>