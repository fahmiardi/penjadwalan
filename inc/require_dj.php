<?php
	if(isset($_SESSION['adm_prodi'])){
	  $qd = "select nama from dosen where id_prodi={$_SESSION['adm_prodi']}";
      $rd = mysqli_query($dbc, $qd);
      if(mysqli_num_rows($rd)>0){
        list($nd) = mysqli_fetch_row($rd);
      }else{
        $nd = false;
      }
	  $qm = "select mk.smstr from matkul as mk inner join matkulmaster as mkm using(id_matkul) where mkm.id_prodi={$_SESSION['adm_prodi']}";
      $rm = mysqli_query($dbc, $qm);
      if(mysqli_num_rows($rm)>0){
        list($im) = mysqli_fetch_row($rm);
      }else{
	    $im = false;
      }
	  $qk = "select nama from kelas where id_TA={$_SESSION['id_ta']} and id_prodi={$_SESSION['adm_prodi']}";
      $rk = mysqli_query($dbc, $qk);
      if(mysqli_num_rows($rk)>0){
        list($nk) = mysqli_fetch_row($rk);
      }else{
	    $nk = false;
      }
	  $ql = "select nama from lokal where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']}";
      $rl = mysqli_query($dbc, $ql);
      if(mysqli_num_rows($rl)>0){
        list($nl) = mysqli_fetch_row($rl);
      }else{
	    $nl = false;
      }
	  $qh = "select nama from hariaktif where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']}";
      $rh = mysqli_query($dbc, $qh);
      if(mysqli_num_rows($rh)>0){
        list($nh) = mysqli_fetch_row($rh);
      }else{
	    $nh = false;
      }
	  $qj = "select jam_kul from tabeljam where id_TA={$_SESSION['id_ta']} and id_fak={$_SESSION['idpf']}";
      $rj = mysqli_query($dbc, $qj);
      if(mysqli_num_rows($rj)>0){
        list($tj) = mysqli_fetch_row($rj);
      }else{
	    $tj = false;
      }
	}elseif(isset($_SESSION['adm_fak'])){
	  $qd = "select nama from dosen where id_fak={$_SESSION['adm_fak']}";
	  $rd = mysqli_query($dbc, $qd);
	  if(mysqli_num_rows($rd)>0){
	    list($nd) = mysqli_fetch_row($rd);
	  }else{
	    $nd = false;
	  }
	  $qh = "select nama from hariaktif where id_fak={$_SESSION['adm_fak']} and id_TA={$_SESSION['id_ta']}";
	  $rh = mysqli_query($dbc, $qh);
	  if(mysqli_num_rows($rh)>0){
	    list($nh) = mysqli_fetch_row($rh);
	  }else{
	    $nh = false;
	  }
	  $qk = "select nama from kelas where id_fak={$_SESSION['adm_fak']} and id_TA={$_SESSION['id_ta']}";
	  $rk = mysqli_query($dbc, $qk);
	  if(mysqli_num_rows($rk)>0){
	    list($nk) = mysqli_fetch_row($rk);
	  }else{
	    $nk = false;
	  }
	  $qt = "select id_jam from tabeljam where id_fak={$_SESSION['adm_fak']} and id_TA={$_SESSION['id_ta']}";
	  $rt = mysqli_query($dbc, $qt);
	  if(mysqli_num_rows($rt)>0){
	    list($ij) = mysqli_fetch_row($rt);
	  }else{
	    $ij = false;
	  }
	  $ql = "select nama from lokal where id_fak={$_SESSION['adm_fak']} and id_TA={$_SESSION['id_ta']}";
	  $rl = mysqli_query($dbc, $ql);
	  if(mysqli_num_rows($rl)>0){
	    list($nl) = mysqli_fetch_row($rl);
	  }else{
	    $nl = false;
	  }
	  $qm = "select mk.id_krklm from matkul as mk inner join matkulmaster as mkm using(id_matkul) where mkm.id_fak={$_SESSION['adm_fak']}";
	  $rm = mysqli_query($dbc, $qm);
	  if(mysqli_num_rows($rm)>0){
	    list($ik) = mysqli_fetch_row($rm);
	  }else{
	    $ik = false;
	  }
	  if($nd and $nh and $nk and $ij and $nl and $ik){
	    $proc = true;
	  }else{
	    $proc = false;
	  }
	}elseif(isset($_SESSION['adm_univ'])){
	  $q = "select nod from jadwal where id_TA={$_SESSION['id_ta']} and id_univ={$_SESSION['adm_univ']}";
	  $r = mysqli_query($dbc, $q);
	  if(mysqli_num_rows($r)>0){
	    list($proc) = mysqli_fetch_row($r);
	  }else{
	    $proc = false;
	  }
	}elseif(isset($_SESSION['adm_super'])){
	  $q = "select nod from jadwal where id_TA={$_SESSION['id_ta']}";
	  $r = mysqli_query($dbc, $q);
	  if(mysqli_num_rows($r)>0){
	    list($proc) = mysqli_fetch_row($r);
	  }else{
	    $proc = false;
	  }
	}
?>