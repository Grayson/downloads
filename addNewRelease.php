<?
if (!$_POST || !$_POST['package'] || !$_POST['version'] || !$_POST['path']) {
	print "MISSING PARAMETERS";
}
else {
	include("DownloadsDB.php");
	$db = new DownloadsDB();
	if (!$db->isLoggedIn()) { print "INVALID CREDENTIALS"; return; }
	$package = $db->escape($_POST['package']);
	$version = $db->escape($_POST['version']);
	$path = $db->escape($_POST['path']);
	$db->query("INSERT INTO releases (package, version, path, dateAdded) VALUES ('$package', '$version', '$path', NOW())");
}
print "SUCCESS";

?>