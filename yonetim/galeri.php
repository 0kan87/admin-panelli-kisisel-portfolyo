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

$maxRows_resim = 3;
$pageNum_resim = 0;
if (isset($_GET['pageNum_resim'])) {
  $pageNum_resim = $_GET['pageNum_resim'];
}
$startRow_resim = $pageNum_resim * $maxRows_resim;

mysql_select_db($database_baglan, $baglan);
$query_resim = "SELECT * FROM resim ORDER BY id ASC";
$query_limit_resim = sprintf("%s LIMIT %d, %d", $query_resim, $startRow_resim, $maxRows_resim);
$resim = mysql_query($query_limit_resim, $baglan) or die(mysql_error());
$row_resim = mysql_fetch_assoc($resim);

if (isset($_GET['totalRows_resim'])) {
  $totalRows_resim = $_GET['totalRows_resim'];
} else {
  $all_resim = mysql_query($query_resim);
  $totalRows_resim = mysql_num_rows($all_resim);
}
$totalPages_resim = ceil($totalRows_resim/$maxRows_resim)-1;

$queryString_resim = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_resim") == false && 
        stristr($param, "totalRows_resim") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_resim = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_resim = sprintf("&totalRows_resim=%d%s", $totalRows_resim, $queryString_resim);
include "ust.php";
?>
  <div class="container">
    <div class="btn-group" role="group">
      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
      Yeni Ekle
      <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu">
      <li><a href="resimler.php">Galeriye Ekle</a></li>
      <li><a href="profil-resmi-ekle.php">Profile Ekle</a></li>
      </ul>
    </div>  
   
    <div class="row">
      <?php do { ?>
      <div class="col-sx-12 col-sm-4 col-md-4">
        <div class="thumbnail">
        <img src="../img/<?php echo $row_resim['resim']; ?>.jpg" alt="...">
          <div class="caption">
            <h4>Resim Bilgileri</h4>
            <p>Üst Açıklama: <?php echo $row_resim['ustbaslik']; ?></p>
            <p>Alt Açıklama: <?php echo $row_resim['altbaslik']; ?></p>
            <p><a href="resim-sil.php?id=<?php echo $row_resim['id']; ?>" class="btn btn-danger" role="button"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Resmi Sil</a></p>
          </div>
        </div>
      </div>
      <?php } while ($row_resim = mysql_fetch_assoc($resim)); ?>
    </div>

    <nav>
      <ul class="pager">
        <li class="onceki"><a href="<?php printf("%s?pageNum_resim=%d%s", $currentPage, max(0, $pageNum_resim - 1), $queryString_resim); ?>"><span aria-hidden="true">&larr;</span> Önceki Resimler</a></li>
        <li class="sonraki"><a href="<?php printf("%s?pageNum_resim=%d%s", $currentPage, min($totalPages_resim, $pageNum_resim + 1), $queryString_resim); ?>">Sonraki Resimler <span aria-hidden="true">&rarr;</span></a></li>
      </ul>
    </nav>
    <?php if ($pageNum_resim > 0) { // Show if not first page ?>
    <?php } // Show if not first page ?>
    <?php if ($pageNum_resim < $totalPages_resim) { // Show if not last page ?>
    <?php } // Show if not last page ?>
  </div>
<?php
include "alt.php";
mysql_free_result($resim);
?>