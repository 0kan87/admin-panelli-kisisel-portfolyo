<?php require_once('../Connections/baglan.php'); ?>
<?php
ob_start();
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
ob_start();
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
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
ob_start();
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

mysql_select_db($database_baglan, $baglan);
$query_resim = "SELECT * FROM resim";
$resim = mysql_query($query_resim, $baglan) or die(mysql_error());
$row_resim = mysql_fetch_assoc($resim);
$totalRows_resim = mysql_num_rows($resim);
include "ust.php";
?>
  <div class="container">
<!-- InstanceBeginEditable name="EditRegion1" -->

<?php include 'class.upload.php';
ob_start();
if($_POST)
{
$aciklama = trim ($_POST["aciklama"]);
$altbaslik = trim ($_POST["altbaslik"]);
$ustbaslik = trim ($_POST["ustbaslik"]);

   
   $klasor = '../img';
   $yeniresim = 'okandiyebiri' . rand(999,99999);

  $handle = new upload($_FILES['resim']);
  if ($handle->uploaded) {
      $handle->file_new_name_body   = $yeniresim;
      $handle->image_resize         = true; //dosya yeniden boyutlandirilacaksa true olarak degistir
	  $handle->image_convert 		= 'jpg'; //dosya dönüştürülür
      $handle->image_x              = 600; //yeniden boyutlandirmada genislik
      $handle->image_ratio_y        = true;
     
      $handle->process($klasor);
      if ($handle->processed) {
          $handle->clean();
         
            $ekle = mysql_query ( "INSERT INTO resim (aciklama, altbaslik, ustbaslik, resim ) values ('$aciklama','$altbaslik','$ustbaslik','$yeniresim') " );
            if ($ekle) {
				echo "Bilgiler kaydedildi.";
                header ("Location: galeri.php");
                exit(0);
            }else{
                echo 'Kayıt başarısız! ... HATA : ' . mysql_error();
               // header ("Refresh:2; url: resim-ekle.php");
                exit(0);
                } 
          } else {
              echo 'Dosya kopyalanamıyor ... HATA : ' . $handle->error;
              exit(0);
          }
  }
}
?>  
    <!-- InstanceEndEditable -->

    </div>
<?php
include "alt.php";
mysql_free_result($resim);
?>
