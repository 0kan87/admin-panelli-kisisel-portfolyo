<?php require_once('../Connections/baglan.php');
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
  $updateSQL = sprintf("UPDATE sag_yetenekler SET yetenekler=%s, nekadariyisin=%s, renkkodu=%s WHERE id=%s",
  GetSQLValueString($_POST['yetenekler'], "text"),
  GetSQLValueString($_POST['nekadariyisin'], "text"),
  GetSQLValueString($_POST['renkkodu'], "text"),
  GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_baglan, $baglan);
  $Result1 = mysql_query($updateSQL, $baglan) or die(mysql_error());

  $updateGoTo = "yetenekler.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_sagyetenekduzenle = "-1";
if (isset($_GET['id'])) {
  $colname_sagyetenekduzenle = $_GET['id'];
}
mysql_select_db($database_baglan, $baglan);
$query_sagyetenekduzenle = sprintf("SELECT * FROM sag_yetenekler WHERE id = %s", GetSQLValueString($colname_sagyetenekduzenle, "int"));
$sagyetenekduzenle = mysql_query($query_sagyetenekduzenle, $baglan) or die(mysql_error());
$row_sagyetenekduzenle = mysql_fetch_assoc($sagyetenekduzenle);
$totalRows_sagyetenekduzenle = mysql_num_rows($sagyetenekduzenle);
include "ust.php";
?>
  <div class="container">
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <div class="col-xs-12 col-sm-6 col-md-3">
      <div class="panel panel-default">
        <div  class= "input-group" > 
        <span  class= "input-group-addon"  id= "basic-addon1" > Yetenek Adı: </span> 
        <input  type= "text" name="yetenekler" class= "form-control" value="<?php echo htmlentities($row_sagyetenekduzenle['yetenekler'], ENT_COMPAT, 'utf-8'); ?>"  aria-describedby= "basic-addon1" > 
        </div> 
      </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-3">
      <div class="panel panel-default">
        <div  class= "input-group" > 
        <span  class= "input-group-addon"  id= "basic-addon1" > Nekadar İyisin (0-100 arası): </span> 
        <input  type= "text" name="nekadariyisin" class= "form-control" value="<?php echo htmlentities($row_sagyetenekduzenle['nekadariyisin'], ENT_COMPAT, 'utf-8'); ?>"  aria-describedby= "basic-addon1" > 
        </div> 
      </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-3">
      <div class="panel panel-default">
        <div  class= "input-group" > 
        <span  class= "input-group-addon"  id= "basic-addon1" > Yetenek Rengi: </span>
        <div id="cp2" class="input-group colorpicker-component">
        <input  type= "text" name="renkkodu" class= "form-control" value="<?php echo htmlentities($row_sagyetenekduzenle['renkkodu'], ENT_COMPAT, 'utf-8'); ?>"  aria-describedby= "basic-addon1" >
        <span class="input-group-addon"><i></i></span>
        </div>
        <script>
            $(function() {
                $('#cp2').colorpicker();
            });
        </script>
        </div> 
      </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-3">
    <input type="submit" class="btn btn-info" value="Yetenek Güncelle" />
    <input type="hidden" name="MM_update" value="form1" />
    <input type="hidden" name="id" value="<?php echo $row_sagyetenekduzenle['id']; ?>" />
    </div>
    </form>
  </div>
<?php
include "alt.php";
mysql_free_result($sagyetenekduzenle);
?>
