<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "giris.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  $isValid = False; 
  if (!empty($UserName)) { 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "giris.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
include "ust.php";
?>
  <div class="container">
    <form name="yuklemeformu" method="post" action="" enctype="multipart/form-data"> 
          <label for="file"><input type="file" name="file" class="btn btn-info" /></label>
          
          <input type="submit" value="Yükle" class="btn btn-info" name="B1">
    </form>
    <p>
      <?php
$yeniisim = "../img/profil.jpg";
if(@is_uploaded_file($_FILES["file"]['tmp_name'])) {
if(move_uploaded_file($_FILES["file"]['tmp_name'],$yeniisim))
echo '<p>Profil resminiz <b>güncellendi</b>...</p>
	<table width="93" border="0">
      <tr>
        <td width="87"><img src="../img/profil.jpg" width="200" height="200" alt="Profil Resmi" /></td>
      </tr>
    </table>';
}else{
echo '<p>Şuan ki profil resminiz...</p>
	<table width="93" border="0">
      <tr>
        <td width="87"><img src="../img/profil.jpg" width="200" height="200" alt="Profil Resmi" /></td>
      </tr>
    </table>';
}
 ?>
      
    Önerilen profil resmi boyutu 150x150 pixeldir. Yeniden boyutlandırma yapılmaz.</p>

    </div>
<?php include "alt.php"; ?>