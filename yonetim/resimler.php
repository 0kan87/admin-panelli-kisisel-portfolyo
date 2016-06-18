<?php require_once('../Connections/baglan.php'); ?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {
  $insertSQL = sprintf("INSERT INTO resim (resim, altbaslik, ustbaslik, aciklama) VALUES (%s, %s, %s, %s)",
   GetSQLValueString($_POST['resim'], "file"),
   GetSQLValueString($_POST['altbaslik'], "text"),
   GetSQLValueString($_POST['ustbaslik'], "text"),
   GetSQLValueString($_POST['aciklama'], "text"));

  mysql_select_db($database_baglan, $baglan);
  $Result1 = mysql_query($insertSQL, $baglan) or die(mysql_error());
}
?>
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
		<form name="form" action="resim-ekle.php" enctype="multipart/form-data" method="POST"> 
			<table class="table table-bordered table-responsive">
				<thead bgcolor="#46b8da" style="color:white;">
					<tr>
						<th>Yön</th>
						<th>Alt Başlık</th>
						<th colspan="3">Üst Başlık</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
						<select name="aciklama" class="form-control">
							<option value="portfolio_post filter1 filter3" <?php if (!(strcmp("portfolio_post filter1 filter3", ""))) {echo "SELECTED";} ?>>Başta</option>
							<option value="portfolio_post filter2 filter1" <?php if (!(strcmp("portfolio_post filter2 filter1", ""))) {echo "SELECTED";} ?>>Ortada</option>
							<option value="portfolio_post filter3 filter2" <?php if (!(strcmp("portfolio_post filter3 filter2", ""))) {echo "SELECTED";} ?>>Sonda</option>
						</select>
						</td>
						<td><input type="text" class="form-control" name="altbaslik" required/></td>
						<td><input type="text" class="form-control" name="ustbaslik" required/></td>
						<td><input type="file" class="form-control" name="resim"/></td>
						<td><input type="submit" class="btn btn-warning" value="Kaydet" /></td>
						<input type="hidden" name="MM_insert" value="form" />
					</tr>
				</tbody>
			</table>
		</form>  
    </div>

<?php include "alt.php"; ?>