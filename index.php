<?php require_once('Connections/baglan.php');
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
$query_ayarlar = "SELECT adsoyadi, emailadresi, telefonnumarasi, siteadresi, ilksayfabaslik, ilksayfaicerik, sitebaslik, siteaciklama, anahtarkelime FROM ayarlar";
$ayarlar = mysql_query($query_ayarlar, $baglan) or die(mysql_error());
$row_ayarlar = mysql_fetch_assoc($ayarlar);
$totalRows_ayarlar = mysql_num_rows($ayarlar);
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

$maxRows_deneyimler = 10;
$pageNum_deneyimler = 0;
if (isset($_GET['pageNum_deneyimler'])) {
  $pageNum_deneyimler = $_GET['pageNum_deneyimler'];
}
$startRow_deneyimler = $pageNum_deneyimler * $maxRows_deneyimler;

mysql_select_db($database_baglan, $baglan);
$query_deneyimler = "SELECT * FROM deneyimler ORDER BY id DESC";
$query_limit_deneyimler = sprintf("%s LIMIT %d, %d", $query_deneyimler, $startRow_deneyimler, $maxRows_deneyimler);
$deneyimler = mysql_query($query_limit_deneyimler, $baglan) or die(mysql_error());
$row_deneyimler = mysql_fetch_assoc($deneyimler);

if (isset($_GET['totalRows_deneyimler'])) {
  $totalRows_deneyimler = $_GET['totalRows_deneyimler'];
} else {
  $all_deneyimler = mysql_query($query_deneyimler);
  $totalRows_deneyimler = mysql_num_rows($all_deneyimler);
}
$totalPages_deneyimler = ceil($totalRows_deneyimler/$maxRows_deneyimler)-1;

$queryString_deneyimler = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_deneyimler") == false && 
        stristr($param, "totalRows_deneyimler") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_deneyimler = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_deneyimler = sprintf("&totalRows_deneyimler=%d%s", $totalRows_deneyimler, $queryString_deneyimler);
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
mysql_select_db($database_baglan, $baglan);
$query_sosyal = "SELECT id, link, icontur, renk FROM sosyal";
$sosyal = mysql_query($query_sosyal, $baglan) or die(mysql_error());
$row_sosyal = mysql_fetch_assoc($sosyal);
$totalRows_sosyal = mysql_num_rows($sosyal);
mysql_select_db($database_baglan, $baglan);
$query_resim = "SELECT * FROM resim";
$resim = mysql_query($query_resim, $baglan) or die(mysql_error());
$row_resim = mysql_fetch_assoc($resim);
$totalRows_resim = mysql_num_rows($resim);

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
$queryString_yetenekler = sprintf("&totalRows_yetenekler=%d%s", $totalRows_yetenekler, $queryString_yetenekler);?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $row_ayarlar['adsoyadi']; ?></title>
		<meta charset="UTF-8">
		<meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport"/>
	    <meta content="<?php echo $row_ayarlar['siteaciklama']; ?>" name="description"/>
	    <meta content="<?php echo $row_ayarlar['anahtarkelime']; ?>" name="keywords"/>
	    <meta content="<?php echo $row_ayarlar['adsoyadi']; ?>" name="author"/>
		<link rel="stylesheet" href="css/style.css"/>
		<link rel="stylesheet" href="css/responsive.css"/>
		<link rel="stylesheet" href="assets/prettyphoto/css/prettyPhoto.css"/>
		<link rel="stylesheet" href="assets/colorpicker/colorpicker.css"/>
		<script src="assets/jquery-1.10.2.min.js"></script>
		<script src="assets/jquery.tools.min.js"></script>
		<script src="assets/jquery.easing.js"></script>
		<script src="assets/googlemap_init.js"></script>
		<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
		<script src="assets/jquery.isotope.min.js"></script>
		<script src="assets/main.js"></script>
		<script src="assets/prettyphoto/jquery.prettyPhoto.js"></script>
		<script src="assets/colorpicker/colorpicker.js"></script>
		<!--[if lt IE 9]>
	        <script src="assets/html5shiv.js"></script>
	    <![endif]-->
		<SCRIPT LANGUAGE="JavaScript">
		var da = (document.all) ? 1 : 0;
		var pr = (window.print) ? 1 : 0;
		var mac = (navigator.userAgent.indexOf("Mac") != -1); 

		function yazdir() {
		if (pr) // NS4, IE5
		window.print()
		else if (da && !mac) // IE4 (Windows)
		vbPrintPage()
		else // other browsers
		alert("Tarayıcınız bu özelliği desteklememektedir.");
		return false;
		}
		</SCRIPT>
	</head>
	<body>
		<div id="page">
			<header id="header">
				<ul id="nav_tabs">
					<li class="splash">
						<a href="#splash">
							<div id="profile_photo">
								<img src="img/profil.jpg" height="150" width="150" alt="Okan IŞIK">
							</div>
							<div id="profile_name">
								<div id="author_name">
									<div class="profile_inner">
										<div class="name"><?php echo $row_ayarlar['adsoyadi']; ?></div>
										<div class="pos"><?php echo $row_ayarlar['sitebaslik']; ?></div>
									</div>
								</div>
							</div>
						</a>
					</li>
					<li class="profile"><a href="#profile"><span class="icon">a</span></a></li>
					<li class="portfolio"><a href="#portfolio"><span class="icon">b</span></a></li>
					<li class="contacts"><a href="#contacts"><span class="icon">c</span></a></li>
				</ul>
			</header><!-- /Header -->
			<div id="main">
				<div id="tab_section">
					<div id="splash" class="tab_content"><!-- Main page -->
						<div class="author_info">
							<div class="user_desc">
								<center><?php echo $row_ayarlar['ilksayfabaslik']; ?><center>
							</div>
							<p><?php echo $row_ayarlar['ilksayfaicerik']; ?></p>
						</div>
						<div class="social_links">
							<ul><?php do { ?>
								<li><a href="<?php echo $row_sosyal['link']; ?>" class="icon1" style="background-color: <?php echo $row_sosyal['renk']; ?>; hover: fff"><span><?php echo $row_sosyal['icontur']; ?></span></a></li>
							<?php } while ($row_sosyal = mysql_fetch_assoc($sosyal)); ?>
							</ul>
						</div>
					</div><!-- /Main Page -->

					<div id="resume" class="tab_content"><!-- Resume Section -->
			            <div class="section-header opened">
			                <a href="#" class="section_toggle"><span><img src="images/section_toggle.png" height="78" width="48" alt="Title Example"></span></a>
			              <h2 class="section-title icon2">
			                <span class="icon category1"></span>
			                Eğitim
			              </h2>
			            </div>

						<div class="section-content">
            				<?php do { ?>
							<!-- Resume Post -->
							<article class="post resume_post resume_post_1 first odd">
								<div class="resume_period category1">
									<span class="period_from"><?php echo $row_egitim['bitirmetarihi']; ?></span>
									<div class="period_to"><?php echo $row_egitim['baslamatarihi']; ?></div>
								</div>

								<div class="extra_wrap">
									<div class="resume_header">
										<h4 class="post_title">
											<span class="post_title_icon category1 icon2"></span>
											<?php echo $row_egitim['okulduzeyi']; ?>
										</h4>
										<h5 class="resume_position category1">
											<span class="icon icon2"></span>
											<?php echo $row_egitim['bolum']; ?>
										</h5>
									</div>
									<div class="resume_content">
										<p><?php echo $row_egitim['aciklama']; ?></p>
									</div>
								</div>
							</article>
					        <?php } while ($row_egitim = mysql_fetch_assoc($egitim)); ?>
					    </div>
						<!-- /Resume Post -->
						<!-- Resume Post -->
			            <div class="section-header opened">
			              <a href="#" class="section_toggle"><span><img src="images/section_toggle.png" height="78" width="48" alt="Title Example"></span></a>
			              <h2 class="section-title icon2">
			                <span class="icon category2"></span>
			                Deneyimler
			              </h2>
			            </div>

						<div class="section-content">
              				<?php do { ?>
							<article class="post resume_post resume_post_2 odd">
								<div class="resume_period category2">
									<span class="period_from"><?php echo $row_deneyimler['ayrilmatarihi']; ?></span>
									<div class="period_to"><?php echo $row_deneyimler['baslamatarihi']; ?></div>
								</div>

								<div class="extra_wrap">
									<div class="resume_header">
										<h4 class="post_title">
											<span class="post_title_icon category2 icon3"></span>
											<?php echo $row_deneyimler['calistigiyer']; ?>
										</h4>
										<h5 class="resume_position category2">
											<span class="icon icon2"></span>
											<?php echo $row_deneyimler['gorevi']; ?>
										</h5>
									</div>
									<div class="resume_content">
										<p><?php echo $row_deneyimler['aciklama']; ?></p>
									</div>
								</div>
							</article>
              				<?php } while ($row_deneyimler = mysql_fetch_assoc($deneyimler)); ?>
						</div>

			            <div class="section-header widgets_section opened">
			              <a href="#" class="section_toggle"><span><img src="images/section_toggle.png" height="78" width="48" alt="Title Example"></span></a>
			              <h2 class="section-title icon2">
			                <span class="icon"></span>
			                Skills (Beceriler)
			              </h2>
			            </div>

						<div class="skills_sidebar_section">
							<!-- Skills widget -->
							<aside class="widget-even widget widget_skills">
								<h3 class="widget_title">Program Yetenekleri</h3>
                				<?php do { ?>
								<div class="widget_inner">
									<div class="skills_row odd first">
										<span class="progressbar">
											<span class="progress" style="background-color: <?php echo $row_yetenekler['renkkodu']; ?>">
												<span class="caption_wrap">
													<span class="caption"><?php echo $row_yetenekler['yetenekler']; ?>/</span>
													<span class="value"><?php echo $row_yetenekler['nekadariyisin']; ?>%</span>
												</span>
											</span>
										</span>
									</div>
				                <?php } while ($row_yetenekler = mysql_fetch_assoc($yetenekler)); ?>
				                </div>							
							</aside>
							<!-- /Skills widget -->

							<!-- Skills widget -->
							<aside class="widget-odd widget widget_skills">
								<h3 class="widget_title">Kodlama Yetenekleri</h3>
                				<?php do { ?>
								<div class="widget_inner">
									<div class="skills_row odd first">
										<span class="progressbar">
											<span class="progress" style="background-color: <?php echo $row_sagyetenekler['renkkodu']; ?>;">
												<span class="caption_wrap">
						                          <span class="caption"><?php echo $row_sagyetenekler['yetenekler']; ?>/</span>
						                          <span class="value"><?php echo $row_sagyetenekler['nekadariyisin']; ?>%</span>
												</span>
											</span>
										</span>
									</div>
                  				<?php } while ($row_sagyetenekler = mysql_fetch_assoc($sagyetenekler)); ?>
								</div>
							</aside>
							<!-- /Skills widget -->

						</div>
						<div class="resume_buttons">
			              <a href="#" class="button_link download"><span>İndir</span></a>
			              <a href="#" onClick="return yazdir()" class="button_link"><span>Yazdır</span></a>
						</div>
					</div><!-- /Resume Section -->
					
					<div id="portfolio" class="tab_content"><!-- Portfolio Section -->
						<div class="portfolio_wrapper">
							<ul id="portfolio_iso_filters">
								<!-- Portfolio Categories -->
								<li><a data-filter="*" href="#" class="current">Fotoğraflar</a></li>
							</ul>
							<div class="portfolio_items">
								<!-- Portfolio post -->
			                <?php do { ?>
			                <article class="<?php echo $row_resim['aciklama']; ?>">
								<div class="post_pic portfolio_post_pic">
									<a href="img/<?php echo $row_resim['resim']; ?>.jpg" class="w_hover img-link img-wrap" rel="prettyPhoto[gallery]" title="<?php echo $row_resim['altbaslik']; ?>">
										<span class="overlay"></span>
										<span class="link-icon"><img src="images/magnify.png" alt="<?php echo $row_resim['ustbaslik']; ?>"></span>
										<img src="img/<?php echo $row_resim['resim']; ?>.jpg" alt="<?php echo $row_resim['ustbaslik']; ?>" height="400" width="600">
										<span class="caption">
											<h5><?php echo $row_resim['altbaslik']; ?></h5>
											</span>
									</a>
								</div>
							</article>
			                <?php } while ($row_resim = mysql_fetch_assoc($resim)); ?> 

								<!-- /Portfolio post -->
								<!-- /Portfolio post -->
							</div>
							<div id="portfolio_load_more">
							</div>
						</div>
					</div><!-- /Portfolio Section -->

					<div id="resume" class="tab_content"><!-- Contacts Section -->
						<div class="section-header opened">
							<div class="col1" style="text-align:center;">
								<div class="phone_num">
									<span class="icon"></span>
									<span class="phone"><a href="tel" style="text-decoration:none;"><?php echo $row_ayarlar['telefonnumarasi']; ?></a></span>
									<p><a href="mailto:<?php echo $row_ayarlar['emailadresi']; ?>"><?php echo $row_ayarlar['emailadresi']; ?></a></p>
									<p><a href="<?php echo $row_ayarlar['siteadresi']; ?>"><?php echo $row_ayarlar['siteadresi']; ?></a></p>
								</div>
							</div>
						</div>
					</div><!-- /Contacts Section -->

				</div>
			</div><!-- /Main -->
			<footer id="footer">
				<a href="#" id="toTop"></a>
				<div class="footer_copyright">
					COPYRIGHT <?php echo date("o"); ?> - <?php echo $row_ayarlar['adsoyadi']; ?> - TUM HAKKI SAKLIDIR.
				</div>
			</footer>
			<script>
				jQuery(document).ready(function(){
				  	setColorPicker('bg_col');
				});
			</script>
			<div id="opt_block">
				<div class="opt_header">
					<span></span>
				</div>
				<div class="opt_row bg_color">
					<h3>background color:</h3>
					<div id="bg_col" class="colorSelector"></div>
				</div>
				<div class="opt_row bg_pat">
					<h3>Background Pattern:</h3>
					<ul class="patterns_select">
						<li><a href="#"><img src="assets/patterns/pattern1.png" alt=""></a></li>
						<li><a href="#"><img src="assets/patterns/pattern2.png" alt=""></a></li>
						<li><a href="#"><img src="assets/patterns/pattern3.jpg" alt=""></a></li>
						<li><a href="#"><img src="assets/patterns/pattern4.png" alt=""></a></li>
						<li><a href="#"><img src="assets/patterns/pattern5.png" alt=""></a></li>
					</ul>
				</div>
				<div class="opt_row bg_img">
					<h3>Background Image:</h3>
					<ul class="bg_select">
						<li><a href="#"><img src="assets/bg1.jpg" alt=""></a></li>
						<li><a href="#"><img src="assets/bg2.jpg" alt=""></a></li>
						<li><a href="#"><img src="assets/bg3.jpg" alt=""></a></li>
					</ul>
				</div>
			</div>
		</div><!--/Page-->
	</body>
</html>
<?php
mysql_free_result($ayarlar);
mysql_free_result($sosyal);
mysql_free_result($resim);
?>