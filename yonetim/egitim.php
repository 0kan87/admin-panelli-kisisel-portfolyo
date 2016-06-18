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
$currentPage = $_SERVER["PHP_SELF"];
$maxRows_egitim = 10;
$pageNum_egitim = 0;
if (isset($_GET['pageNum_egitim'])) {
  $pageNum_egitim = $_GET['pageNum_egitim'];
}
$startRow_egitim = $pageNum_egitim * $maxRows_egitim;

mysql_select_db($database_baglan, $baglan);
$query_egitim = "SELECT * FROM egitim ORDER BY id DESC";
$query_limit_egitim = sprintf("%s LIMIT %d, %d", $query_egitim, $startRow_egitim, $maxRows_egitim);
$egitim = mysql_query($query_limit_egitim, $baglan) or die(mysql_error());
$row_egitim = mysql_fetch_assoc($egitim);

if (isset($_GET['totalRows_egitim'])) {
  $totalRows_egitim = $_GET['totalRows_egitim'];
} else {
  $all_egitim = mysql_query($query_egitim);
  $totalRows_egitim = mysql_num_rows($all_egitim);
}
$totalPages_egitim = ceil($totalRows_egitim/$maxRows_egitim)-1;

$queryString_egitim = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_egitim") == false && 
        stristr($param, "totalRows_egitim") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_egitim = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_egitim = sprintf("&totalRows_egitim=%d%s", $totalRows_egitim, $queryString_egitim);
include "ust.php";
?>
  <div class="container">
    <a href="egitim-ekle.php" class="navbar-btn btn-info btn pull-right">Eğitim Ekle</a>
    <table class="table table-hover table-bordered">
      <tr>
        <thead bgcolor="#46b8da" style="color:white;">
          <th>Başlama Yılı</th>
          <th>Bitirme Yılı</th>
          <th>Eğitim Türü</th>
          <th colspan="3">Eğitim Bölümü/Eylemi</th>
        </thead>
      </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_egitim['baslamatarihi']; ?></td>
            <td><?php echo $row_egitim['bitirmetarihi']; ?></td>
            <td><?php echo $row_egitim['okulduzeyi']; ?></td>
            <td width="203"><?php echo $row_egitim['bolum']; ?></td>
            <td width="4"><center><a href="egitim-duzenle.php?id=<?php echo $row_egitim['id']; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></center></td>
            <td width="4"><center><a href="egitim-sil.php?id=<?php echo $row_egitim['id']; ?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></center></td>
          </tr>
          <?php } while ($row_egitim = mysql_fetch_assoc($egitim)); ?>
    </table>

    <table border="0">
      <tr>
        <td><?php if ($pageNum_egitim > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_egitim=%d%s", $currentPage, 0, $queryString_egitim); ?>">&#304;lk</a>
            <?php } // Show if not first page ?></td>
        <td><?php if ($pageNum_egitim > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_egitim=%d%s", $currentPage, max(0, $pageNum_egitim - 1), $queryString_egitim); ?>">&Ouml;nceki</a>
            <?php } // Show if not first page ?></td>
        <td><?php if ($pageNum_egitim < $totalPages_egitim) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_egitim=%d%s", $currentPage, min($totalPages_egitim, $pageNum_egitim + 1), $queryString_egitim); ?>">Sonraki</a>
            <?php } // Show if not last page ?></td>
        <td><?php if ($pageNum_egitim < $totalPages_egitim) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_egitim=%d%s", $currentPage, $totalPages_egitim, $queryString_egitim); ?>">Son</a>
            <?php } // Show if not last page ?></td>
      </tr>
    </table>
  </div>
<?php
include "alt.php";
mysql_free_result($egitim);
?>