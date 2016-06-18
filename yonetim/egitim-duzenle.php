<?php require_once('../Connections/baglan.php'); ?>
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
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE egitim SET baslamatarihi=%s, bitirmetarihi=%s, okulduzeyi=%s, bolum=%s, aciklama=%s WHERE id=%s",
 GetSQLValueString($_POST['baslamatarihi'], "date"),
 GetSQLValueString($_POST['bitirmetarihi'], "date"),
 GetSQLValueString($_POST['okulduzeyi'], "text"),
 GetSQLValueString($_POST['bolum'], "text"),
 GetSQLValueString($_POST['aciklama'], "text"),
 GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_baglan, $baglan);
  $Result1 = mysql_query($updateSQL, $baglan) or die(mysql_error());

  $updateGoTo = "egitim.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_egitimduzenle = "-1";
if (isset($_GET['id'])) {
  $colname_egitimduzenle = $_GET['id'];
}
mysql_select_db($database_baglan, $baglan);
$query_egitimduzenle = sprintf("SELECT * FROM egitim WHERE id = %s", GetSQLValueString($colname_egitimduzenle, "int"));
$egitimduzenle = mysql_query($query_egitimduzenle, $baglan) or die(mysql_error());
$row_egitimduzenle = mysql_fetch_assoc($egitimduzenle);
$totalRows_egitimduzenle = mysql_num_rows($egitimduzenle);
include "ust.php";
?>
  <div class="container">
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <div class="col-xs-12 col-sm-6 col-md-3">
        <table class="table table-bordered table-hover table-responsive">
          <thead bgcolor="#46b8da" style="color:white;">
            <tr>
              <th>Eğitime Başlama Tarihi</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><input type="date" name="baslamatarihi" class="form-control" value="<?php echo htmlentities($row_egitimduzenle['baslamatarihi'], ENT_COMPAT, 'utf-8'); ?>"/></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-3">
        <table class="table table-bordered table-hover table-responsive">
          <thead bgcolor="#46b8da" style="color:white;">
            <tr>
              <th>Eğitimi Bitirme Tarihi</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><input type="date" name="bitirmetarihi" class="form-control" value="<?php echo htmlentities($row_egitimduzenle['bitirmetarihi'], ENT_COMPAT, 'utf-8'); ?>"/></td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="col-xs-12 col-sm-6 col-md-3">
        <table class="table table-bordered table-hover table-responsive">
          <thead bgcolor="#46b8da" style="color:white;">
            <tr>
              <th>Eğitim Türü</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><input type="text" name="okulduzeyi" class="form-control" value="<?php echo htmlentities($row_egitimduzenle['okulduzeyi'], ENT_COMPAT, 'utf-8'); ?>"/></td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="col-xs-12 col-sm-6 col-md-3">
        <table class="table table-bordered table-hover table-responsive">
          <thead bgcolor="#46b8da" style="color:white;">
            <tr>
              <th>Eğitim Bölümü/Eylem</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><input type="text" name="bolum" class="form-control" value="<?php echo htmlentities($row_egitimduzenle['bolum'], ENT_COMPAT, 'utf-8'); ?>"/></td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="col-xs-12 col-sm-12 col-md-12">
        <table class="table table-bordered table-hover table-responsive">
          <thead bgcolor="#46b8da" style="color:white;">
            <tr>
              <th>Bu Eğitiminiz Hakkında Açıklama Yazın</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><textarea class="ckeditor" name="aciklama"><?php echo htmlentities($row_egitimduzenle['aciklama'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
            </tr>
          </tbody>
        </table>
        <input type="hidden" name="MM_update" value="form1" />
        <input type="hidden" name="id" value="<?php echo $row_egitimduzenle['id']; ?>" />
        <input type="submit" class="btn btn-warning pull-right" value="Eğitimi Güncelle" />
      </div>
    </form>
  </div>
<?php
include "alt.php";
mysql_free_result($egitimduzenle);
?>
