<?php
session_start();
if((!isset($_SESSION['adm_prodi']) or !isset($_SESSION['id_ta'])) and (!isset($_SESSION['adm_fak']) or !isset($_SESSION['id_ta']))){
  header("Location:../index.php");
}

if($_POST['jk']){
  
  if(isset($_SESSION['adm_prodi'])){
  
  require_once('../mysqli_connect.php');
  
  if($_POST['jk']=='-'){
    echo'<option value="-">-</option>';
  }else{
    
	$cSubstr = substr_count($_POST['jk'], '-');
	if($cSubstr==9){
	
      list($nod, $sJam, $eJam, $idh, $mj, $mm, $sj, $sm, $idk, $idjad) = explode('-', $_POST['jk']);
	  $rk = mysqli_query($dbc, "select mhs from kelas where id_kls=$idk");
	  list($mhs) = mysqli_fetch_row($rk);
	  $ql = "select id_lkl, nama, muat from lokal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} order by nama asc";
	  $rl = mysqli_query($dbc, $ql);
	  
	  echo'<option value="-">-</option>';
	  $rlx = mysqli_query($dbc, "select j.id_hari, j.id_lkl, j.jk_start, j.jk_end, l.nama from jadwal as j inner join lokalmaster as l using(id_lkl) where j.id_jad=$idjad");
	  list($idhx, $idlx, $jsx, $jex, $lokalx) = mysqli_fetch_row($rlx);
	  if($idlx!=0 and $idhx==$idh and $jsx==$sJam and $jex==$eJam){
	    echo"<option value=\"$idlx\" style=\"background:#606060; color:white;\">$lokalx</option>";
	  }
	  while(list($idl, $lokal, $muat) = mysqli_fetch_row($rl)){
	    $range = range($sJam, $eJam);
	    $struck = 0;
	    while(list($k, $qRange) = each($range)){
	      $q = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} and id_hari=$idh and id_lkl=$idl and ($qRange between jk_start and jk_end)";
		  $r = mysqli_query($dbc, $q);
		  if(mysqli_num_rows($r)>0  or $muat<$mhs){
		    $struck += 1;
		  }
	    }
	    if($struck==0){
	      echo"<option value=\"$idl\">$lokal</option>";
	    }
	  }
	
	}else{
	
	  list($nod, $nods, $sJam, $eJam, $idh, $mj, $mm, $sj, $sm, $idk, $idjad) = explode('-', $_POST['jk']);
	  $rk = mysqli_query($dbc, "select mhs from kelas where id_kls=$idk");
	  list($mhs) = mysqli_fetch_row($rk);
	  $ql = "select id_lkl, nama, muat from lokal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} order by nama asc";
	  $rl = mysqli_query($dbc, $ql);
	  
	  echo'<option value="-">-</option>';
	  $rlx = mysqli_query($dbc, "select j.id_hari, j.id_lkl, j.jk_start, j.jk_end, l.nama from jadwal as j inner join lokalmaster as l using(id_lkl) where j.id_jad=$idjad");
	  list($idhx, $idlx, $jsx, $jex, $lokalx) = mysqli_fetch_row($rlx);
	  if($idlx!=0 and $idhx==$idh and $jsx==$sJam and $jex==$eJam){
	    echo"<option value=\"$idlx\" style=\"background:#606060; color:white;\">$lokalx</option>";
	  }
	  while(list($idl, $lokal, $muat) = mysqli_fetch_row($rl)){
	    $range = range($sJam, $eJam);
	    $struck = 0;
	    while(list($k, $qRange) = each($range)){
	      $q = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']} and id_hari=$idh and id_lkl=$idl and ($qRange between jk_start and jk_end)";
		  $r = mysqli_query($dbc, $q);
		  if(mysqli_num_rows($r)>0 or $muat<$mhs){
		    $struck += 1;
		  }
	    }
	    if($struck==0){
	      echo"<option value=\"$idl\">$lokal</option>";
	    }
	  }
	
	}
  
  }
 
  }elseif(isset($_SESSION['adm_fak'])){
  
  require_once('../mysqli_connect.php');
  
  if($_POST['jk']=='-'){
    echo'<option value="-">-</option>';
  }else{
    
	$cSubstr = substr_count($_POST['jk'], '-');
	if($cSubstr==9){
	
      list($nod, $sJam, $eJam, $idh, $mj, $mm, $sj, $sm, $idk, $idjad) = explode('-', $_POST['jk']);
	  $rk = mysqli_query($dbc, "select mhs from kelas where id_kls=$idk");
	  list($mhs) = mysqli_fetch_row($rk);
	  $ql = "select id_lkl, nama, muat from lokal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} order by nama asc";
	  $rl = mysqli_query($dbc, $ql);
	  
	  echo'<option value="-">-</option>';
	  $rlx = mysqli_query($dbc, "select j.id_hari, j.id_lkl, j.jk_start, j.jk_end, l.nama from jadwal as j inner join lokalmaster as l using(id_lkl) where j.id_jad=$idjad");
	  list($idhx, $idlx, $jsx, $jex, $lokalx) = mysqli_fetch_row($rlx);
	  if($idlx!=0 and $idhx==$idh and $jsx==$sJam and $jex==$eJam){
	    echo"<option value=\"$idlx\" style=\"background:#606060; color:white;\">$lokalx</option>";
	  }
	  while(list($idl, $lokal, $muat) = mysqli_fetch_row($rl)){
	    $range = range($sJam, $eJam);
	    $struck = 0;
	    while(list($k, $qRange) = each($range)){
	      $q = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} and id_hari=$idh and id_lkl=$idl and ($qRange between jk_start and jk_end)";
		  $r = mysqli_query($dbc, $q);
		  if(mysqli_num_rows($r)>0  or $muat<$mhs){
		    $struck += 1;
		  }
	    }
	    if($struck==0){
	      echo"<option value=\"$idl\">$lokal</option>";
	    }
	  }
	
	}else{
	
	  list($nod, $nods, $sJam, $eJam, $idh, $mj, $mm, $sj, $sm, $idk, $idjad) = explode('-', $_POST['jk']);
	  $rk = mysqli_query($dbc, "select mhs from kelas where id_kls=$idk");
	  list($mhs) = mysqli_fetch_row($rk);
	  $ql = "select id_lkl, nama, muat from lokal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} order by nama asc";
	  $rl = mysqli_query($dbc, $ql);
	  
	  echo'<option value="-">-</option>';
	  $rlx = mysqli_query($dbc, "select j.id_hari, j.id_lkl, j.jk_start, j.jk_end, l.nama from jadwal as j inner join lokalmaster as l using(id_lkl) where j.id_jad=$idjad");
	  list($idhx, $idlx, $jsx, $jex, $lokalx) = mysqli_fetch_row($rlx);
	  if($idlx!=0 and $idhx==$idh and $jsx==$sJam and $jex==$eJam){
	    echo"<option value=\"$idlx\" style=\"background:#606060; color:white;\">$lokalx</option>";
	  }
	  while(list($idl, $lokal, $muat) = mysqli_fetch_row($rl)){
	    $range = range($sJam, $eJam);
	    $struck = 0;
	    while(list($k, $qRange) = each($range)){
	      $q = "select * from jadwal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['adm_fak']} and id_hari=$idh and id_lkl=$idl and ($qRange between jk_start and jk_end)";
		  $r = mysqli_query($dbc, $q);
		  if(mysqli_num_rows($r)>0 or $muat<$mhs){
		    $struck += 1;
		  }
	    }
	    if($struck==0){
	      echo"<option value=\"$idl\">$lokal</option>";
	    }
	  }
	
	}
  
  }
  
  }
  
}
?>