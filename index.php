<?php
  session_start();
  $page_title='Penjadwalan';
  include('header.html');
  if(isset($_SESSION['adm_super']) or isset($_SESSION['adm_univ']) or isset($_SESSION['adm_fak']) or isset($_SESSION['adm_prodi']) or isset($_SESSION['nod'])){
    header("Location:inc/cp.php");
  }
?>
<div id="login" class="base">
  <div id="ocBView">
    <div class="label">LOGIN</div>
    <div class="form">
    <?php
	if(isset($_SESSION['failog'])){
	  echo<<<EOT
	  <p class="fail">{$_SESSION['failog']}</p>
EOT;
	  unset($_SESSION['failog']);
	}
	?>
      <div id="checklogwrap">
        <form action="inc/checklog.php" method="post" id="checklog">
          <p class="center">Username : <input type="text" name="username" id="username" class="frontlog" size="20"/></p>
          <p class="center">Password : <input type="password" name="password" class="frontlog" size="20"/></p>
          <p class="center"><input type="submit" name="submit" value="" class="flog"/></p>
          <input type="hidden" name="submitted" value="TRUE"/>
        </form>
	  </div>
	
    </div>
  </div>
</div>

<?php
  require_once('mysqli_connect.php');
  $r = mysqli_query($dbc, "select id_TA, tahun, ajaran from ta order by id_TA");
  if(mysqli_num_rows($r)>0){
  //<p class="center" style="font-family:Hopper; font-size:12px;">Lihat Jadwal:</p>
  echo'
  <div id="fView" class="base">
	<table>
	  <tr class="exc">
		<td>
		<select name="idta" class="taTrig">
		  <option value="null"> - </option>';
	while(list($idta, $thn, $aj) = mysqli_fetch_row($r)){
	  if($aj==1){$ajr='Ganjil';}else{$ajr='Genap';}
	  echo"<option value=\"$idta-$aj\">$thn - $ajr</option>";
	}
  echo'	
		</select>
		<select name="idp" class="ocVProdi prodiVTrig">
		</select>
		<select name="smstr" class="ocVSmstr smstrVTrig">
		</select>
		<select name="idk" class="ocVKelas kelasVTrig">
		</select><br/><br/>
		<div id="cfView">view</div>
		</td>
	  </tr>
	</table>
  </div>';
	}
	?>
	
<?php
  include('footer.html');
?>