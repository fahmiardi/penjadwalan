<?php
  if(isset($_POST['idta'])){
    
	list($idta, $aj) = explode('-', $_POST['idta']);
	
	if($idta=='null'){
	  echo'<option value="null"> - </option>';
	}else{
	  require_once('../mysqli_connect.php');
	  $q = "select id_prodi, nama from prodi order by nama asc";
	  $r = mysqli_query($dbc, $q);
	  if(mysqli_num_rows($r)>0){
	    echo'<option value="null"> - </option>';
	    while(list($idp, $prodi) = mysqli_fetch_row($r)){
		  echo"<option value=\"$idta-$aj-$idp\">$prodi</option>";
		}
	  }else{
	    echo'<option value="null"> - </option>';
	  }
	}
    
  }elseif(isset($_POST['idp'])){
    
	list($idta, $aj, $idp) = explode('-', $_POST['idp']);
	
	if($idp=='null'){
	  echo'<option value="null"> - </option>';
	}else{
	  if($aj==1){
	    echo'<option value="null"> - </option>';
	    $smstr = array(1,3,5,7);
		foreach($smstr as $k => $v){
		  echo"<option value=\"$idta-$idp-$v\">$v</option>";
		}
	  }else{
	    echo'<option value="null"> - </option>';
	    $smstr = array(2,4,6,8);
		foreach($smstr as $k => $v){
		  echo"<option value=\"$idta-$idp-$v\">$v</option>";
		}
	  }
	}
    
  }elseif(isset($_POST['smstr'])){
    
	list($idta, $idp, $smstr) = explode('-', $_POST['smstr']);
	
	if($smstr=='null'){
	  echo'<option value="null-null-null-null"> - </option>';
	}else{
	  require_once('../mysqli_connect.php');
	  $q = "select id_kls, nama from kelas where id_prodi=$idp and id_TA=$idta and smstr=$smstr order by nama asc";
	  $r = mysqli_query($dbc, $q);
	  if(mysqli_num_rows($r)>0){
	    echo'<option value="null-null-null-null"> - </option>';
	    while(list($idk, $kelas) = mysqli_fetch_row($r)){
		  echo"<option value=\"$idta-$idk-$idp-$smstr-$kelas\">$kelas</option>";
		}
	  }else{
	    echo'<option value="null-null-null-null"> - </option>';
	  }
	}
    
  }elseif(isset($_POST['idk'])){
    
	list($idta, $idk, $idp, $smstr, $kelas) = explode('-', $_POST['idk']);
	
	if($idk!='null'){
	  require_once('../mysqli_connect.php');
	  $rf = mysqli_query($dbc, "select id_fak from prodi where id_prodi=$idp limit 1");
	  list($idf) = mysqli_fetch_row($rf);
	  $rp = mysqli_query($dbc, "select nama from prodi where id_prodi=$idp limit 1");
	  list($prodi) = mysqli_fetch_row($rp);
	  $q = "select id_jad, nod, nods, id_matkul, sks, paralel, id_hari, jk_start, mulai_jam, mulai_menit, jk_end, selesai_jam, selesai_menit, id_lkl from jadwal where id_kls=$idk order by id_hari, jk_start asc";
	  $r = mysqli_query($dbc, $q);
	  if(mysqli_num_rows($r)>0){
	    echo'<div id="ocBView" class="form extView"><a class="fprint" style="text-shadow:none;" href="inc/vpdf.php?v='.$idta.'/'.$idk.'/'.$idp.'/'.$smstr.'" target="_blank" title="Print">pdf</a>';
		echo"<br/><span class=\"jad\">$prodi $smstr-$kelas</span><br/><br/><br/>";
		echo'<table>
		    <tr class="exc"><td></td><td>Dosen</td><td>Mata Kuliah</td><td>SKS</td><td>Split</td><td>Paralel</td><td>Hari</td><td>Jam Kuliah</td><td>Lokal</td></tr>';
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
			  echo"
			  <tr>
			    <td></td>
				<td class=\"vpad\"><span class=\"jad\">$dosen</span></td>
				<td><span class=\"jad\">$matkul</span></td>
				<td><span class=\"jad\">$sks</span></td>
				<td><span class=\"jad\">$split</span></td>
				<td><span class=\"jad\">$prl</span></td>";
			  if($idh!=0){
				echo"<td><span class=\"jad\">$hari</span></td>";
			  }else{
			    echo'<td><span class="jad">-</span></td>';
			  }
			  if($sJam!=0){
				if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
			    if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
			    if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
			    if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
				echo'<td><span class="jad">'.$sJam.' - '.$eJam.' &nbsp;/&nbsp; '.$mjl.':'.$mml.' - '.$sjl.':'.$sml.'</span></td>';
			  }else{
			    echo'<td><span class="jad">-</span></td>';
			  }
			  if($idl!=0){
			    $ql = "select nama from lokal where id_lkl=$idl";
				$rl = mysqli_query($dbc, $ql);
				list($lokal) = mysqli_fetch_row($rl);
				echo'<td><span class="jad">'.$lokal.'</span></td>';
			  }else{
				echo'<td><span class="jad">-</span></td>';
			  }
			  echo'</tr>';
			  if($nods!=0){
			    $qds = "select nama from dosen where nod=$nods";
			    $rds = mysqli_query($dbc, $qds);
			    list($subdosen) = mysqli_fetch_row($rds);
			    echo"
			    <tr class=\"exc\">
			      <td class=\"tpad\"><span class=\"sub\"><font color=\"#e01515\">tim</font></span></td>
				  <td class=\"tpad\">$subdosen</td>
			    </tr>";
			  }
			}
		echo'
		  <tr class="exc"><td colspan="9" style="text-align:center;"></td></tr>
		  <tr class="exc"><td colspan="9" style="text-align:center;"><p><a href="index.php" class="logView"></a></p></td></tr>
		  </table>
		</div>';
	  }else{
	    require_once('../mysqli_connect.php');
	    $rp = mysqli_query($dbc, "select nama from prodi where id_prodi=$idp limit 1");
	    list($prodi) = mysqli_fetch_row($rp);
	    echo'<div id="ocBView" class="form" style="border-top-left-radius:8px; border-top-right-radius:8px;">
		  
		  <p class="center" style="font-size:16px;">';
		echo"<span class=\"jad\">$prodi $smstr-$kelas</span><br/><br/>";
		echo'<font style="font-size:12px;">Belum ada Jadwal</font></p>
		  <hr/>
		  <p class="center"><a href="index.php" class="logView"></a></p>
		</div>';
	  }
	}else{
	  echo'<div id="ocBView" class="form" style="border-top-left-radius:8px; border-top-right-radius:8px;">
		<p class="center" style="font-size:16px;">
	  <span class="jad">null</span></p><br/>
		<hr/>
		<p class="center"><a href="index.php" class="logView"></a></p>
	  </div>';
	}
    
  }
?>