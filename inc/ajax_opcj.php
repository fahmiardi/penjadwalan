<?php
session_start();
if(!isset($_SESSION['adm_fak']) or !isset($_SESSION['id_ta'])){
  header('Location:../index.php');
}else{

  require_once('../mysqli_connect.php');
  
  if(isset($_POST['opt'])){
  
    $opt = $_POST['opt'];
	
	if($opt=='generate'){
	  
	  $cjk = mysqli_query($dbc, "select count(jam_kul) from tabeljammaster where id_fak={$_SESSION['adm_fak']}");
	  list($recs) = mysqli_fetch_row($cjk);
	  $r = mysqli_query($dbc, "select id_univ, jam_kul, mulai_jam, mulai_menit, selesai_jam, selesai_menit from tabeljammaster where id_fak={$_SESSION['adm_fak']} order by jam_kul asc");
	  if(mysqli_num_rows($r)>0){
	    
		$succeed = 0;
	    while(list($idu, $jk, $mj, $mm, $sj, $sm)=mysqli_fetch_row($r)){
	      mysqli_query($dbc, "insert into tabeljam(id_fak, id_univ, jam_kul, mulai_jam, mulai_menit, selesai_jam, selesai_menit, id_TA) 
		  values({$_SESSION['adm_fak']}, $idu, $jk, $mj, $mm, $sj, $sm, {$_SESSION['id_ta']})");
		  if(mysqli_affected_rows($dbc)>0){
		    $succeed += 1;
		  }
	    }
		
		if($succeed==$recs){
		  
		  echo'<div style="position:relative; display:table;">
		  <div id="refJK" class="jpd" style="font-size:12px; cursor:pointer; display:inline-block; text-shadow:none; border-radius:15px; padding:3px 5px; padding-top:4px;" title="refresh"><img src="../img/refresh.png" height="16px" width="16px"/></div>
		</div>
		<div id="ocRJK">';

	    $q = "select id_jam, jam_kul, mulai_jam, mulai_menit, selesai_jam, selesai_menit from tabeljam where id_fak={$_SESSION['adm_fak']} and id_TA={$_SESSION['id_ta']} order by jam_kul asc";
		$r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r) > 0){
		  echo"<p class=\"center\" style=\"font-size:12px;\">Ubah Data Jam Perkuliahan:</span><br/></p>
		  <table id=\"tabEjam\">
		  <tr class=\"exc\"><td></td><td>Jam ke-</td><td></td><td>Mulai <font style=\"font-size:10px;\">Jam</font></td><td></td><td>Mulai <font style=\"font-size:10px;\">Menit</font></td><td> </td><td>Selesai <font style=\"font-size:10px;\">Jam</font></td><td></td><td>Selesai <font style=\"font-size:10px;\">Menit</font></td><td></td></tr>";
		  $rj = mysqli_query($dbc, "select MAX(jam_kul) from tabeljam where id_fak={$_SESSION['adm_fak']} and id_TA={$_SESSION['id_ta']}");
		  if(mysqli_num_rows($rj)>0){
			list($maxJ) = mysqli_fetch_row($rj);
		  }else{
		    $maxJ = 1;
		  } 
		  $i = 1; $ic = 1;
		  while(list($idj, $jk, $mj, $mm, $sj, $sm)=mysqli_fetch_row($r)){
			  
		    if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
		    if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
			if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
			if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
			
			echo"<tr><form action=\"upjam.php\" method=\"post\" id=\"upjam".$i++."\">";
			echo'
			  <td class="vpad"><a class="x deljam atc'.$ic++.'" href="hapusjam.php?idj='.$idj.'" title="Hapus"'; if($jk!=$maxJ){echo'style="display:none;"';} echo'>-</a></td>
		      <td><span class="jad">';
			  echo $jk;
			  /*$jamkul = range(1, 20);
			  foreach($jamkul as $k => $v){
			    echo"<option value=\"$v\">$v</option>";
			  }*/
			  echo<<<EOT
			  </span></td>
			  <td> </td>
		      <td><select name="mulai_jam">
			  <option value="$mj" selected="selected">$mjl</option>
EOT;
			  $jam = array(1=>'01','02','03','04','05','06','07','08','09','10','11',
			  '12','13','14','15','16','17','18','19','20','21','22','23','24');
			  foreach($jam as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td>:</td>
		      <td><select name="mulai_menit">
			  <option value="$mm" selected="selected">$mml</option>
EOT;
			  $menit = array(0=>'00',10=>'10',20=>'20',30=>'30',40=>'40',50=>'50');
			  foreach($menit as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td> </td>
		      <td><select name="selesai_jam">
			  <option value="$sj" selected="selected">$sjl</option>
EOT;
			  $jam = array(1=>'01','02','03','04','05','06','07','08','09','10','11',
			  '12','13','14','15','16','17','18','19','20','21','22','23','24');
			  foreach($jam as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td>:</td>
		      <td><select name="selesai_menit">
			  <option value="$sm" selected="selected">$sml</option>
EOT;
			  $menit = array(0=>'00',10=>'10',20=>'20',30=>'30',40=>'40',50=>'50');
			  foreach($menit as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td>
			    <input type="submit" name="submit" value="+" title="Update" class="update"/>
			    <input type="hidden" name="jamkul" value="$jk"/>
			    <input type="hidden" name="idj" value="$idj"/>
			    <input type="hidden" name="submitted" value="TRUE"/>
			  </td>
			  </form>
		    </tr>
EOT;
		  }
		  echo'</table></div>';
		}else{
		  echo"<p class=\"center\" style=\"font-size:13px;\">Belum ada data Jam Perkuliahan</p></div>";
		}
		echo<<<EOT
	    <div class="hr"><hr/></div>
		  
	    <p class="center" style="font-size:12px;">Tambah Data Jam Perkuliahan:<br/></p>
		  
		<div id="checkjam">
          <form action="cekjam.php" method="post" id="cekjam">
		  <table>
		    <tr class="exc"><td>Jam ke-</td><td></td><td>Mulai <font style="font-size:10px;">Jam</font></td><td></td><td>Mulai <font style="font-size:10px;">Menit</font></td><td> </td><td>Selesai <font style="font-size:10px;">Jam</font></td><td></td><td>Selesai <font style="font-size:10px;"> &nbsp;Menit </font></td></tr>
		    <tr class="exc">
		      <td><span class="jad ocCJ">
EOT;
			  $minJ = 1;
			  $rj = mysqli_query($dbc, "select MAX(jam_kul) from tabeljam where id_fak={$_SESSION['adm_fak']} and id_TA={$_SESSION['id_ta']}");
			  if(mysqli_num_rows($rj)>0){
			    list($maxJ) = mysqli_fetch_row($rj);
				$pJ = $minJ + $maxJ;
				echo $pJ;
			  }else{
			    $pJ = $minJ;
				echo $pJ;
			  }
			  /*$jamkul = range(1, 20);
			  foreach($jamkul as $k => $v){
			    echo"<option value=\"$v\">$v</option>";
			  }*/
			  echo<<<EOT
			  </span></td>
			  <td> </td>
		      <td><select name="mulai_jam">
EOT;
			  $jam = array(1=>'01','02','03','04','05','06','07','08','09','10','11',
			  '12','13','14','15','16','17','18','19','20','21','22','23','24');
			  foreach($jam as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td>:</td>
		      <td><select name="mulai_menit">
EOT;
			  $menit = array(0=>'00',10=>'10',20=>'20',30=>'30',40=>'40',50=>'50');
			  foreach($menit as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td> </td>
		      <td><select name="selesai_jam">
EOT;
			  $jam = array(1=>'01','02','03','04','05','06','07','08','09','10','11',
			  '12','13','14','15','16','17','18','19','20','21','22','23','24');
			  foreach($jam as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td>:</td>
		      <td><select name="selesai_menit">
EOT;
			  $menit = array(0=>'00',10=>'10',20=>'20',30=>'30',40=>'40',50=>'50');
			  foreach($menit as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
		    </tr>
	      </table>
          <p class="center"><input type="submit" name="submit" value="+ Tambah"/></p>
	    <input type="hidden" name="jamkul" value="$pJ" class="hidCJ"/>  
          <input type="hidden" name="submitted" value="TRUE"/>
          </form>
	    </div>
EOT;
		  
		}else{
		  echo"<p class=\"center\" style=\"font-size:13px;\">Terjadi kesalahan pada sistem / kegagalan koneksi.</p></div>";
		}

	  }
	
	}elseif($opt=='create'){
	
	  echo'<div style="position:relative; display:table;">
		  <div id="refJK" class="jpd" style="font-size:12px; cursor:pointer; display:inline-block; text-shadow:none; border-radius:15px; padding:3px 5px; padding-top:4px;" title="refresh"><img src="../img/refresh.png" height="16px" width="16px"/></div>
		</div>
		<div id="ocRJK">';

	    $q = "select id_jam, jam_kul, mulai_jam, mulai_menit, selesai_jam, selesai_menit from tabeljam where id_fak={$_SESSION['adm_fak']} and id_TA={$_SESSION['id_ta']} order by jam_kul asc";
		$r = mysqli_query($dbc, $q);
		if(mysqli_num_rows($r) > 0){
		  echo"<p class=\"center\" style=\"font-size:12px;\">Ubah Data Jam Perkuliahan:</span><br/></p>
		  <table id=\"tabEjam\">
		  <tr class=\"exc\"><td></td><td>Jam ke-</td><td></td><td>Mulai <font style=\"font-size:10px;\">Jam</font></td><td></td><td>Mulai <font style=\"font-size:10px;\">Menit</font></td><td> </td><td>Selesai <font style=\"font-size:10px;\">Jam</font></td><td></td><td>Selesai <font style=\"font-size:10px;\">Menit</font></td><td></td></tr>";
		  $rj = mysqli_query($dbc, "select MAX(jam_kul) from tabeljam where id_fak={$_SESSION['adm_fak']} and id_TA={$_SESSION['id_ta']}");
		  if(mysqli_num_rows($rj)>0){
			list($maxJ) = mysqli_fetch_row($rj);
		  }else{
		    $maxJ = 1;
		  } 
		  $i = 1; $ic = 1;
		  while(list($idj, $jk, $mj, $mm, $sj, $sm)=mysqli_fetch_row($r)){
			  
		    if(strlen($mj)==1){ $mjl = 0 . $mj; }else{ $mjl = $mj; }
		    if(strlen($mm)==1){ $mml = $mm . 0; }else{ $mml = $mm; }
			if(strlen($sj)==1){ $sjl = 0 . $sj; }else{ $sjl = $sj; }
			if(strlen($sm)==1){ $sml = $sm . 0; }else{ $sml = $sm; }
			
			echo"<tr><form action=\"upjam.php\" method=\"post\" id=\"upjam".$i++."\">";
			echo'
			  <td class="vpad"><a class="x deljam atc'.$ic++.'" href="hapusjam.php?idj='.$idj.'" title="Hapus"'; if($jk!=$maxJ){echo'style="display:none;"';} echo'>-</a></td>
		      <td><span class="jad">';
			  echo $jk;
			  /*$jamkul = range(1, 20);
			  foreach($jamkul as $k => $v){
			    echo"<option value=\"$v\">$v</option>";
			  }*/
			  echo<<<EOT
			  </span></td>
			  <td> </td>
		      <td><select name="mulai_jam">
			  <option value="$mj" selected="selected">$mjl</option>
EOT;
			  $jam = array(1=>'01','02','03','04','05','06','07','08','09','10','11',
			  '12','13','14','15','16','17','18','19','20','21','22','23','24');
			  foreach($jam as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td>:</td>
		      <td><select name="mulai_menit">
			  <option value="$mm" selected="selected">$mml</option>
EOT;
			  $menit = array(0=>'00',10=>'10',20=>'20',30=>'30',40=>'40',50=>'50');
			  foreach($menit as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td> </td>
		      <td><select name="selesai_jam">
			  <option value="$sj" selected="selected">$sjl</option>
EOT;
			  $jam = array(1=>'01','02','03','04','05','06','07','08','09','10','11',
			  '12','13','14','15','16','17','18','19','20','21','22','23','24');
			  foreach($jam as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td>:</td>
		      <td><select name="selesai_menit">
			  <option value="$sm" selected="selected">$sml</option>
EOT;
			  $menit = array(0=>'00',10=>'10',20=>'20',30=>'30',40=>'40',50=>'50');
			  foreach($menit as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td>
			    <input type="submit" name="submit" value="+" title="Update" class="update"/>
			    <input type="hidden" name="jamkul" value="$jk"/>
			    <input type="hidden" name="idj" value="$idj"/>
			    <input type="hidden" name="submitted" value="TRUE"/>
			  </td>
			  </form>
		    </tr>
EOT;
		  }
		  echo'</table></div>';
		}else{
		  echo"<p class=\"center\" style=\"font-size:13px;\">Belum ada data Jam Perkuliahan</p></div>";
		}
		echo<<<EOT
	    <div class="hr"><hr/></div>
		  
	    <p class="center" style="font-size:12px;">Tambah Data Jam Perkuliahan:<br/></p>
		  
		<div id="checkjam">
          <form action="cekjam.php" method="post" id="cekjam">
		  <table>
		    <tr class="exc"><td>Jam ke-</td><td></td><td>Mulai <font style="font-size:10px;">Jam</font></td><td></td><td>Mulai <font style="font-size:10px;">Menit</font></td><td> </td><td>Selesai <font style="font-size:10px;">Jam</font></td><td></td><td>Selesai <font style="font-size:10px;"> &nbsp;Menit </font></td></tr>
		    <tr class="exc">
		      <td><span class="jad ocCJ">
EOT;
			  $minJ = 1;
			  $rj = mysqli_query($dbc, "select MAX(jam_kul) from tabeljam where id_fak={$_SESSION['adm_fak']} and id_TA={$_SESSION['id_ta']}");
			  if(mysqli_num_rows($rj)>0){
			    list($maxJ) = mysqli_fetch_row($rj);
				$pJ = $minJ + $maxJ;
				echo $pJ;
			  }else{
			    $pJ = $minJ;
				echo $pJ;
			  }
			  /*$jamkul = range(1, 20);
			  foreach($jamkul as $k => $v){
			    echo"<option value=\"$v\">$v</option>";
			  }*/
			  echo<<<EOT
			  </span></td>
			  <td> </td>
		      <td><select name="mulai_jam">
EOT;
			  $jam = array(1=>'01','02','03','04','05','06','07','08','09','10','11',
			  '12','13','14','15','16','17','18','19','20','21','22','23','24');
			  foreach($jam as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td>:</td>
		      <td><select name="mulai_menit">
EOT;
			  $menit = array(0=>'00',10=>'10',20=>'20',30=>'30',40=>'40',50=>'50');
			  foreach($menit as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td> </td>
		      <td><select name="selesai_jam">
EOT;
			  $jam = array(1=>'01','02','03','04','05','06','07','08','09','10','11',
			  '12','13','14','15','16','17','18','19','20','21','22','23','24');
			  foreach($jam as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
			  <td>:</td>
		      <td><select name="selesai_menit">
EOT;
			  $menit = array(0=>'00',10=>'10',20=>'20',30=>'30',40=>'40',50=>'50');
			  foreach($menit as $k => $v){
			    echo"<option value=\"$k\">$v</option>";
			  }
			  echo<<<EOT
			  </select></td>
		    </tr>
	      </table>
          <p class="center"><input type="submit" name="submit" value="+ Tambah"/></p>
	    <input type="hidden" name="jamkul" value="$pJ" class="hidCJ"/>  
          <input type="hidden" name="submitted" value="TRUE"/>
          </form>
	    </div>
EOT;

	}
	
  }
  
}
?>