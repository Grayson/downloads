<?
include_once("DownloadsDB.php");

function redirectToFile($file)
{
	$db = new DownloadsDB();
	$ipaddress = $db->escape($_SERVER['REMOTE_ADDR']);
	$agent = $db->escape($_SERVER['HTTP_USER_AGENT']);
	$id = $db->escape($file['id']);
	$path = $db->escape($file['path']);
	$db->query("INSERT INTO requests (dateRequested, IPAddress, requestedFileID, userAgent) VALUES (NOW(), '$ipaddress', $id, '$agent')");
	header("Status: 302 Found");
	header("Location: $path");
}


$uri = $_SERVER['REQUEST_URI'];

$components = explode('/', $uri);
$db = new DownloadsDB();

// "Simple": /downloads/packageName
$last = urldecode(array_pop($components));
$tmp = $db->latestReleaseOfPackage($last);
if ($tmp) {
	redirectToFile($tmp);
	return;
}

$matches = null;
$package = $last;
// "Version A": /downloads/PackageName 2.0
if (preg_match("/([a-zA-Z ]+)[ \tvV]*([\d\.]+)/", $last, $matches)) {
	$package = trim($matches[1]);
	$version = $matches[2];
	$tmp = $db->query("SELECT * FROM files WHERE displayName='$package' LIMIT 1");
	if ($tmp) {
		redirectToFile($tmp);
		return;
	}
}

// "Version B": /downloads/PackageName/2.0
if (preg_match("/[\d\.]+/", $last)) {
	$version = $last;
	$package = urldecode(array_pop($components));
	$tmp = $db->query("SELECT * FROM files WHERE displayName='$package' LIMIT 1");
	if ($tmp) {
		redirectToFile($tmp);
		return;
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Downloads Error</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
	<h1 id="banner">Your file could not be found</h1>	

	<div class="copy">
<?
$tmp = $db->query("SELECT * FROM files WHERE displayName LIKE '%$package%' LIMIT 10");
if ($tmp) {
	if (array_key_exists('id', $tmp)) $tmp = Array($tmp);
	print "<h1>Could one of these be the file you were looking for?</h1>";
	print "<ul>";
	foreach($tmp as $release) {
		$package = $release['displayName'];
		$filename = $release['filename'];
		$path = $release['downloadPath'];
		$date = date('F j, Y, g:ia', strtotime($release['dateAdded']));
		print "<li><a href=\"$path\">$filename</a> (released on $date)</li>";
	}
	print "</ul>";
}
else { ?>
		<h1>No matching files could be found</h1>
		<p>Neither the requested file nor any similar files could be found.  Please make sure that the URL is correct.  If it is, please contact the webmaster and report this problem.</p>
<? } ?>
	</div>
	
</body>
</html>

