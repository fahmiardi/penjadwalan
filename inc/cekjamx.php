<?php
session_start();
if(!isset($_SESSION['adm_fak'])){

  $url='../index.php';
  header("Location: $url");
  exit();
  
}

if(isset($_POST['submitted'])){


  require_once('../mysqli_connect.php');
  
  $jk = $_POST['jamkul'];
  $mj = $_POST['mulai_jam'];
  $mm = $_POST['mulai_menit'];
  $sj = $_POST['selesai_jam'];
  $sm = $_POST['selesai_menit'];
  $start = (100 * $mj) + $mm + 1;
  $end = (100 * $sj) + $sm;
  
  $sql = "select * from tabeljammaster where id_fak={$_SESSION['adm_fak']} and jam_kul=$jk";
  $res = mysqli_query($dbc, $sql);
  
  if($start >= $end){
    echo'minus';
  }elseif(mysqli_num_rows($res)>0){
    echo'jamkul-struck';
  }else{
    
    $range = range($start, $end);  
    $dbStart = "(mulai_jam*100)+mulai_menit";
    $dbEnd = "(selesai_jam*100)+selesai_menit";
    $struck = 0;
	while(list($k, $qRange)=each($range)){
      $q = "select * from tabeljammaster where id_fak={$_SESSION['adm_fak']} and ($qRange between $dbStart and $dbEnd)";
      $r = mysqli_query($dbc, $q);
      if(mysqli_num_rows($r)>0){
        $struck += 1;
	    break;
      }
    }
    if($struck>0){
      echo'range-struck';
    }else{
      $qi = "insert into tabeljammaster(jam_kul, mulai_jam, mulai_menit, selesai_jam, selesai_menit, id_fak, id_univ)
	  values($jk, $mj, $mm, $sj, $sm, {$_SESSION['adm_fak']}, {$_SESSION['idfu']})";
	  mysqli_query($dbc, $qi);
	  if(mysqli_affected_rows($dbc)>0){
	    echo'success';
	  }else{
	    echo'error';
	  }
    }
  
  }


}

?>