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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO egitim (id, baslamatarihi, bitirmetarihi, okulduzeyi, bolum, aciklama) VALUES (%s, %s, %s, %s, %s, %s)",
 GetSQLValueString($_POST['id'], "int"),
 GetSQLValueString($_POST['baslamatarihi'], "date"),
 GetSQLValueString($_POST['bitirmetarihi'], "date"),
 GetSQLValueString($_POST['okulduzeyi'], "text"),
 GetSQLValueString($_POST['bolum'], "text"),
 GetSQLValueString($_POST['aciklama'], "text"));

  mysql_select_db($database_baglan, $baglan);
  $Result1 = mysql_query($insertSQL, $baglan) or die(mysql_error());

  $insertGoTo = "egitim.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_baglan, $baglan);
$query_egitimekle = "SELECT * FROM egitim";
$egitimekle = mysql_query($query_egitimekle, $baglan) or die(mysql_error());
$row_egitimekle = mysql_fetch_assoc($egitimekle);
$totalRows_egitimekle = mysql_num_rows($egitimekle);
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
              <td><input type="date" class="form-control" name="baslamatarihi" value="" size="32" required /></td>
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
              <td><input type="date" class="form-control" name="bitirmetarihi" value="" size="32" required /></td>
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
              <td><input type="text" class="form-control" name="okulduzeyi" value="" size="32" required /></td>
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
              <td><input type="text" class="form-control" name="bolum" value="" size="32" required /></td>
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
              <td><textarea class="ckeditor" name="aciklama"></textarea></td>
            </tr>
          </tbody>
        </table>
          <input type="hidden" name="MM_insert" value="form1" />
          <input type="submit" class="btn btn-warning pull-right" value="Eğitim Ekle" />
      </div>
    </form>
  </div>
<?php
include "alt.php";
mysql_free_result($egitimekle);
?>