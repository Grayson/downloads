<?
include('DownloadsDB.php');
$db = new DownloadsDB();

if ($_POST['id'] && $_POST['package'] && $_POST['version'] && $_POST['path'] && $db->isLoggedIn()) {
	$package = $db->escape($_POST['package']);
	$version = $db->escape($_POST['version']);
	$path = $db->escape($_POST['path']);
	$id = $db->escape($_POST['id']);
	$db->query("UPDATE releases SET package='$package', version='$version', path='$path' WHERE id=$id");
	header("Location: admin.php");
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit</title>
<style type="text/css" media="screen">
	label {
		width: 200px;
		display: block;
		float: left;
		text-align: right;
		position: relative;
		top: 2px;
		margin-right: 10px;
	}
</style>
</head>
<body>

<?
if (!$db->isLoggedIn()) { print "<h1>Invalid credentials - log in first.</h1>"; return; }

$id = $db->escape($_GET['id']);
if (!$id) { print "<h1>No id given - Can't get information.</h1>"; return; }

$release = $db->query("SELECT * FROM releases WHERE id=$id");
?>

<form action="edit.php" method="post" accept-charset="utf-8">
	<input type="hidden" name="id" value="<? print $id; ?>" id="id">
	<p><label for="package">Package:</label> <input type="text" name="package" value="<? print $release['package']; ?>" id="package"></p>
	<p><label for="version">Version:</label> <input type="text" name="version" value="<? print $release['version']; ?>" id="version"></p>
	<p><label for="path">Path:</label> <input type="text" name="path" value="<? print $release['path']; ?>" id="path"></p>
	
	<p><input type="submit" value="Update"></p>
</form>


</body>
</html>