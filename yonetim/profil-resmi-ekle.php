<?php
if (!isset($_SESSION)) {
  session_start();
}

$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
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
    <div class="col-md-4 col-md-offset-4">
      <form name="yuklemeformu" method="post" action="" enctype="multipart/form-data"> 
        <label for="file"><input type="file" name="file"/></label>
        <input type="submit" value="Yükle" class="btn btn-info" name="B1">
      </form>

      <?php $yeniisim = "../img/profil.jpg";
      if(@is_uploaded_file($_FILES["file"]['tmp_name'])) {
      if(move_uploaded_file($_FILES["file"]['tmp_name'],$yeniisim)) ?>
      <br/>
      <div class="alert alert-success" role="alert">Profil resmi başarıyla güncellendi!</div>

      <img src="../img/profil.jpg" class="thumbnail img-responsive" alt="Profil Resmi" />

      <?php }else{ ?>
      <br/>

      <div class="alert alert-success" role="alert">Şuan ki Profil resminiz! Önerilen profil resmi boyutu 150x150 pixeldir. Yeniden boyutlandırma yapılmaz.</div>
      <img src="../img/profil.jpg" class="thumbnail img-responsive" alt="Profil Resmi" />

      <?php } ?>
      </div>
  </div>
<?php include "alt.php"; ?>