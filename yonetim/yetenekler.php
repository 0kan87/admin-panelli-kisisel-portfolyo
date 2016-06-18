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

$currentPage = $_SERVER["PHP_SELF"];

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_yetenekler = 10;
$pageNum_yetenekler = 0;
if (isset($_GET['pageNum_yetenekler'])) {
  $pageNum_yetenekler = $_GET['pageNum_yetenekler'];
}
$startRow_yetenekler = $pageNum_yetenekler * $maxRows_yetenekler;

mysql_select_db($database_baglan, $baglan);
$query_yetenekler = "SELECT * FROM yetenekler ORDER BY id DESC";
$query_limit_yetenekler = sprintf("%s LIMIT %d, %d", $query_yetenekler, $startRow_yetenekler, $maxRows_yetenekler);
$yetenekler = mysql_query($query_limit_yetenekler, $baglan) or die(mysql_error());
$row_yetenekler = mysql_fetch_assoc($yetenekler);

if (isset($_GET['totalRows_yetenekler'])) {
  $totalRows_yetenekler = $_GET['totalRows_yetenekler'];
} else {
  $all_yetenekler = mysql_query($query_yetenekler);
  $totalRows_yetenekler = mysql_num_rows($all_yetenekler);
}
$totalPages_yetenekler = ceil($totalRows_yetenekler/$maxRows_yetenekler)-1;

$maxRows_sagyetenekler = 10;
$pageNum_sagyetenekler = 0;
if (isset($_GET['pageNum_sagyetenekler'])) {
  $pageNum_sagyetenekler = $_GET['pageNum_sagyetenekler'];
}
$startRow_sagyetenekler = $pageNum_sagyetenekler * $maxRows_sagyetenekler;

mysql_select_db($database_baglan, $baglan);
$query_sagyetenekler = "SELECT * FROM sag_yetenekler ORDER BY id DESC";
$query_limit_sagyetenekler = sprintf("%s LIMIT %d, %d", $query_sagyetenekler, $startRow_sagyetenekler, $maxRows_sagyetenekler);
$sagyetenekler = mysql_query($query_limit_sagyetenekler, $baglan) or die(mysql_error());
$row_sagyetenekler = mysql_fetch_assoc($sagyetenekler);

if (isset($_GET['totalRows_sagyetenekler'])) {
  $totalRows_sagyetenekler = $_GET['totalRows_sagyetenekler'];
} else {
  $all_sagyetenekler = mysql_query($query_sagyetenekler);
  $totalRows_sagyetenekler = mysql_num_rows($all_sagyetenekler);
}
$totalPages_sagyetenekler = ceil($totalRows_sagyetenekler/$maxRows_sagyetenekler)-1;

$queryString_yetenekler = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_yetenekler") == false && 
        stristr($param, "totalRows_yetenekler") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_yetenekler = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_yetenekler = sprintf("&totalRows_yetenekler=%d%s", $totalRows_yetenekler, $queryString_yetenekler);

$queryString_sagyetenekler = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_sagyetenekler") == false && 
        stristr($param, "totalRows_sagyetenekler") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_sagyetenekler = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_sagyetenekler = sprintf("&totalRows_sagyetenekler=%d%s", $totalRows_sagyetenekler, $queryString_sagyetenekler);

$queryString_yetenekler = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_yetenekler") == false && 
        stristr($param, "totalRows_yetenekler") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_yetenekler = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_yetenekler = sprintf("&totalRows_yetenekler=%d%s", $totalRows_yetenekler, $queryString_yetenekler);
include "ust.php";
?>
  <div class="container">
    <div class="col-xs-12 col-sm-6 col-md-6">
      <div class="panel panel-default">
        <div class="btn-group btn pull-right">
          <button type="button" data-toggle="dropdown">
            Yeni Ekle <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" role="menu">
            <li><a href="yetenek-ekle.php">Sol Bölüme Ekle</a></li>
            <li><a href="sag-yetenek-ekle.php">Sağ Bölüme Ekle</a></li>
          </ul>
        </div>
        <div class="panel-heading">Yetenekler Sol Bölüm</div>
          <table class="table table-bordered table-hover table-condensed table-responsive">
            <thead bgcolor="#46b8da" style="color:white;">
              <tr>
                <th>Renk</th>
                <th>Yetenekler</th>
                <th colspan="3">Yüzde</th>
              </tr>
            </thead>
            <?php do { ?>
            <tbody>
              <tr>
                <td width="5" bgcolor="<?php echo $row_yetenekler['renkkodu']; ?>"></td>
                <td><?php echo $row_yetenekler['yetenekler']; ?></td>
                <td><?php echo $row_yetenekler['nekadariyisin']; ?></td>
                <td width="4"><center><a href="yetenek-duzenle.php?id=<?php echo $row_yetenekler['id']; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></center></td>
                <td width="4"><center><a href="yetenek-sil.php?id=<?php echo $row_yetenekler['id']; ?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></center></td>
              </tr>
            </tbody>
            <?php } while ($row_yetenekler = mysql_fetch_assoc($yetenekler)); ?>
          </table>
      </div>
    </div>

    <table border="0">
      <tr>
        <td><?php if ($pageNum_yetenekler > 0) { ?><a href="<?php printf("%s?pageNum_yetenekler=%d%s", $currentPage, 0, $queryString_yetenekler); ?>">&#304;lk</a><?php } ?></td>
        <td><?php if ($pageNum_yetenekler > 0) { ?><a href="<?php printf("%s?pageNum_yetenekler=%d%s", $currentPage, max(0, $pageNum_yetenekler - 1), $queryString_yetenekler); ?>">&Ouml;nceki</a><?php } ?></td>
        <td><?php if ($pageNum_yetenekler < $totalPages_yetenekler) { ?><a href="<?php printf("%s?pageNum_yetenekler=%d%s", $currentPage, min($totalPages_yetenekler, $pageNum_yetenekler + 1), $queryString_yetenekler); ?>">Sonraki</a><?php } ?></td>
        <td><?php if ($pageNum_yetenekler < $totalPages_yetenekler) { ?><a href="<?php printf("%s?pageNum_yetenekler=%d%s", $currentPage, $totalPages_yetenekler, $queryString_yetenekler); ?>">Son</a><?php } ?></td>
      </tr>
    </table>

    <div class="col-xs-12 col-sm-6 col-md-6">
      <div class="panel panel-default">
      <div class="panel-heading">Yetenekler Sağ Bölüm</div>
        <table class="table table-bordered table-hover table-condensed table-responsive">
          <thead bgcolor="#46b8da" style="color:white;">
            <tr>
              <th>Renk</th>
              <th>Yetenekler</th>
              <th colspan="3">Yüzde</th>
            </tr>
          </thead>
          <?php do { ?>
          <tbody>
            <tr>
              <td width="5" bgcolor="<?php echo $row_sagyetenekler['renkkodu']; ?>"></td>
              <td><?php echo $row_sagyetenekler['yetenekler']; ?></td>
              <td><?php echo $row_sagyetenekler['nekadariyisin']; ?></td>
              <td width="4"><center><a href="sag-yetenek-duzenle.php?id=<?php echo $row_sagyetenekler['id']; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></center></td>
              <td width="4"><center><a href="sag-yetenek-sil.php?id=<?php echo $row_sagyetenekler['id']; ?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></center></td>
            </tr>
          </tbody>
          <?php } while ($row_sagyetenekler = mysql_fetch_assoc($sagyetenekler)); ?>
        </table>
      </div>
    </div>

    <table border="0">
          <tr>
            <td><?php if ($pageNum_sagyetenekler > 0) {  ?>
                <a href="<?php printf("%s?pageNum_sagyetenekler=%d%s", $currentPage, 0, $queryString_sagyetenekler); ?>">&#304;lk</a>
              <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_sagyetenekler > 0) {  ?>
                <a href="<?php printf("%s?pageNum_sagyetenekler=%d%s", $currentPage, max(0, $pageNum_sagyetenekler - 1), $queryString_sagyetenekler); ?>">&Ouml;nceki</a>
              <?php }?></td>
            <td><?php if ($pageNum_sagyetenekler < $totalPages_sagyetenekler) { ?>
                <a href="<?php printf("%s?pageNum_sagyetenekler=%d%s", $currentPage, min($totalPages_sagyetenekler, $pageNum_sagyetenekler + 1), $queryString_sagyetenekler); ?>">Sonraki</a>
              <?php }?></td>
            <td><?php if ($pageNum_sagyetenekler < $totalPages_sagyetenekler) {  ?>
                <a href="<?php printf("%s?pageNum_sagyetenekler=%d%s", $currentPage, $totalPages_sagyetenekler, $queryString_sagyetenekler); ?>">Son</a>
              <?php }?></td>
          </tr>
    </table>
</div>
<?php
include "alt.php";
mysql_free_result($yetenekler);
mysql_free_result($sagyetenekler);
?>