<?php
session_start();
if(!isset($_SESSION['adm_fak']) or !isset($_SESSION['id_ta'])){
  $url='../index.php';
  header("Location: $url");
  exit();
}

if(isset($_POST['submitted'])){

  require_once('../mysqli_connect.php');
    
  if(!isset($_POST['hari'])){
    
	echo'kosong';
  
  }else{
    
    $count = count($_POST['hari']);
    $struck = 0;
	
      for($i=0;$i<$count;$i++){
	    list($idh, $hari) = explode('-', $_POST['hari'][$i]);
	    $q = "select * from hariaktif where nama='$hari' and id_fak={$_SESSION['adm_fak']} and id_univ={$_SESSION['idfu']} and id_TA={$_SESSION['id_ta']}";
	    $r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r)>0){
		  $struck += 1;
	    }
	  }
	  	  
	 if($struck>0){
		echo'struck';
	  }elseif($struck==0){
	    for($i=0;$i<$count;$i++){
		  list($idh, $hari) = explode('-', $_POST['hari'][$i]);
		  $qi = "insert into hariaktif(id_hari, nama, id_fak, id_univ, id_TA) values($idh, '$hari', {$_SESSION['adm_fak']}, {$_SESSION['idfu']}, {$_SESSION['id_ta']})";
		  mysqli_query($dbc, $qi);
		}
		echo'success';
	  }else{
	    echo'error';
	  }
	  
    
  
  }
  
}

?>