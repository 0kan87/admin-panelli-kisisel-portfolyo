<?php require_once('../Connections/baglan.php'); ?>
<?php
//initialize the session
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
  $updateSQL = sprintf("UPDATE sosyal SET link=%s, iconno=%s, icontur=%s, renk=%s WHERE id=%s",
                       GetSQLValueString($_POST['link'], "text"),
                       GetSQLValueString($_POST['iconno'], "text"),
                       GetSQLValueString($_POST['icontur'], "text"),
                       GetSQLValueString($_POST['renk'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_baglan, $baglan);
  $Result1 = mysql_query($updateSQL, $baglan) or die(mysql_error());

  $updateGoTo = "sosyal.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_sosyalduzenle = "-1";
if (isset($_GET['id'])) {
  $colname_sosyalduzenle = $_GET['id'];
}
mysql_select_db($database_baglan, $baglan);
$query_sosyalduzenle = sprintf("SELECT id, link, icontur, renk FROM sosyal WHERE id = %s", GetSQLValueString($colname_sosyalduzenle, "int"));
$sosyalduzenle = mysql_query($query_sosyalduzenle, $baglan) or die(mysql_error());
$row_sosyalduzenle = mysql_fetch_assoc($sosyalduzenle);
$totalRows_sosyalduzenle = mysql_num_rows($sosyalduzenle);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Yönetim Paneli</title>
<link href="../SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
	<div class="navbar navbar-inverse navbar-static-top">
		<div class="container">
			<a href="#" class="navbar-brand">Admin Paneli</a>
			
			<button class="navbar-toggle" data-toggle="collapse" data-target=".navbarSec">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<div class="collapse navbar-collapse navbarSec">
				<ul class="nav navbar-nav navbar-right">
					<li class="active"><a href="index.php">Anasayfa</a></li>
                    <li><a href="ayarlar.php">Ayarlar</a></li>
                    <li><a href="profil-resmi-ekle.php">Profil Resmi</a></li>
                    <li><a href="galeri.php">Resim Galerisi</a></li>
                    <li><a href="deneyimler.php">Deneyimler</a></li>
                    <li><a href="egitim.php">Eğitimler</a></li>
                    <li><a href="sosyal.php">Sosyal Linkler</a></li>
                    <li><a href="yetenekler.php">Yetenekler</a></li>
                    <li><a href="../index.php">Site Anasayfa</a></li>
					<li><a href="<?php echo $logoutAction ?>">Çıkış</a></li>
				</ul>
			</div>
		</div>
	</div>
    <div class="container">
    	<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
			<table class="table table-responsive">
				<thead>
					<tr>
						<th>
							Sosyal Link
						</th>
						<th>
						<div class="col-md-12">
							<input type="url" class="form-control" name="link" value="<?php echo htmlentities($row_sosyalduzenle['link'], ENT_COMPAT, 'utf-8'); ?>"/>
						</div>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							Link İkonu
						</td>
						<td>
							<div class="col-md-3">
								<select name="icontur" class="form-control">
					            <option value="0" <?php if (!@(strcmp(0, htmlentities($row_sosyalduzenle['icontur'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Facebook</option>
					            <option value="1" <?php if (!@(strcmp(1, htmlentities($row_sosyalduzenle['icontur'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Twitter</option>
					            <option value="2" <?php if (!@(strcmp(2, htmlentities($row_sosyalduzenle['icontur'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Google+</option>
					            <option value="5" <?php if (!@(strcmp(5, htmlentities($row_sosyalduzenle['icontur'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Pinterest</option>
					            <option value="6" <?php if (!@(strcmp(6, htmlentities($row_sosyalduzenle['icontur'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Vimeo</option>
					            <option value="7" <?php if (!@(strcmp(7, htmlentities($row_sosyalduzenle['icontur'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Youtube</option>
					            <option value="l" <?php if (!@(strcmp(l, htmlentities($row_sosyalduzenle['icontur'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Skype</option>
					            <option value="p" <?php if (!@(strcmp(p, htmlentities($row_sosyalduzenle['icontur'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>İnstagram</option>
					            <option value="4" <?php if (!@(strcmp(4, htmlentities($row_sosyalduzenle['icontur'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Linkedin</option>            
					            </select>
					           </div>
						</td>
					</tr>
					<tr>
						<td>
							İkon Rengi
						</td>
						<td>
						<div class="col-md-6">
							<input type="color" name="renk" value="<?php echo htmlentities($row_sosyalduzenle['renk'], ENT_COMPAT, 'utf-8'); ?>"/>
						</div>
						</td>
					</tr>
					<tr>
						<td>
							
						</td>
						<td>
						<div class="col-md-6">
							<input type="submit" class="btn btn-info" value="Linki Güncelle" />
						</div>
						</td>
					</tr>
				</tbody>
			</table>
	        <input type="hidden" name="MM_update" value="form1" />
	        <input type="hidden" name="id" value="<?php echo $row_sosyalduzenle['id']; ?>" />
	    </form>

		<table class="table table-bordered table-hover">
		  <thead>
		    <tr>
		      <th>Renk</th>
		      <th>Renk Adı</th>
		      <th>#Hex</th>
		      <th>RGB</th>
		    </tr>
		  </thead>
		  <tbody>
		    <tr>
		      <th scope="row" bgcolor="#3b5998"></th>
		      <td>Facebook Blue</td>
		      <td>#3b5998</td>
		      <td>59,89,152</td>
		    </tr>
		    <tr>
		      <th scope="row" bgcolor="#00aced"></th>
		      <td>Twitter Blue</td>
		      <td>#00aced</td>
		      <td>0,172,237</td>
		    </tr>
		    <tr>
		      <th scope="row" bgcolor="#dd4b39"></th>
		      <td>Google+ Red</td>
		      <td>#dd4b39</td>
		      <td>221,75,57</td>
		    </tr>
		    <tr>
		      <th scope="row" bgcolor="#bb0000"></th>
		      <td>YouTube Red</td>
		      <td>#bb0000</td>
		      <td>187,0,0</td>   
		    </tr>
		    <tr>
		      <th scope="row" bgcolor="#517fa4"></th>
		      <td>Instagram Blue</td>
		      <td>#517fa4</td>
		      <td>81,127,164</td>
		    </tr>
		    <tr>
		      <th scope="row" bgcolor="#cb2027"></th>
		      <td>Pinterest Red</td>
		      <td>#cb2027</td>
		      <td>203,32,39</td>
		    </tr>
		    <tr>
		      <th scope="row" bgcolor="#aad450"></th>
		      <td>Vimeo Green</td>
		      <td>#aad450</td>
		      <td>170,212,80</td>
		    </tr>
		    <tr>
		      <th scope="row" bgcolor="#007bb6"></th>
		      <td>Linkedin Blue</td>
		      <td>#007bb6</td>
		      <td>0,123,182</td>   
		    </tr>    
		  </tbody>
		</table>
    </div>
    <div class="container">
           <div class="alert alert-warning alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          Oluşabilecek her hata için lütfen <a href="http://okandiyebiri.com/admin-panelli-kisisel-site-scripti/"><strong>destek</strong></a> sitesini ziyaret edin.
          </div>
          </br></br>
     </div>
    <div class="navbar navbar-default navbar-fixed-bottom">
		<div class="container">
			<p class="navbar-text pull-left">Okan IŞIK</p>
			<a href="http://okandiyebiri.com" class="navbar-btn btn-info btn pull-right">okandiyebiri.com</a>
		</div>
	</div>
<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($sosyalduzenle);
?>
