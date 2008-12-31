<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Downloads install/upgrade</title>
</head>
<body>

<?
include('DownloadsDB.php');
$db = new DownloadsDB();

if (!$db->query("SELECT * FROM users LIMIT 1") && !$_POST['password'] && !$_POST['username']) { ?>
	<form action="install.php" method="post" accept-charset="utf-8">
		<p><label for="username">Username</label> <input type="text" name="username" value="" id="username"></p>
		<p><label for="password">Password</label> <input type="password" name="password" value="" id="password"></p>
		
		<p><input type="submit" value="Continue &rarr;"></p>
	</form>
<?
} else {

	function getVersionFromFilename($filename) {
		$matches = null;
		if (!preg_match("/(\d+\.\d+)/", $filename, $matches)) return "";
		return $matches[0];
	}

	$tables = array();
	foreach($db->query("SHOW TABLES") as $tmp) $tables[] = $tmp['Tables_in_' . DBNAME];


	if (!in_array('requests', $tables)) {
		$db->query("CREATE TABLE IF NOT EXISTS `requests` (
		  `id` int(11) NOT NULL auto_increment,
		  `dateRequested` datetime default NULL,
		  `IPAddress` varchar(255) default NULL,
		  `requestedFileID` int(11) default NULL,
		  `userAgent` varchar(255) default NULL,
		  PRIMARY KEY  (`id`)
		) DEFAULT CHARSET=utf8");
	}

	if (!in_array('releases', $tables)) {
		$db->query("CREATE TABLE `releases` (
		  `id` int(11) NOT NULL auto_increment,
		  `package` varchar(255) default NULL,
		  `version` varchar(20) default NULL,
		  `path` varchar(255) default NULL,
		  `dateAdded` datetime default NULL,
		  PRIMARY KEY  (`id`)
		) DEFAULT CHARSET=utf8");
	}

	if (!in_array('users', $tables)) {
		$db->query("CREATE TABLE `users` (
		  `id` int(11) NOT NULL auto_increment,
		  `name` varchar(255) default NULL,
		  `password` varchar(255) default NULL,
		  PRIMARY KEY  (`id`)
		) DEFAULT CHARSET=utf8");
	}
	
	if (!$db->query("SELECT * FROM users LIMIT 1")) {
		$name = $_POST['username'];
		$pass = md5($_POST['password']);
		$db->query("INSERT INTO users (name, password) VALUES ('$name', '$pass')");
	}

	$tmp = $db->query("SELECT * FROM files");
	if ($tmp) {
		foreach ($tmp as $file) {
			$package = $file['displayName'];
			$dateAdded = $file['dateAdded'];
			$path = $file['downloadPath'];
			$version = getVersionFromFilename($file['filename']);
			$db->query("INSERT into releases (package, version, path, dateAdded) VALUES ('$package', '$version', '$path', '$dateAdded')");
		}
	} ?>
	<h1>Success</h1>
	<p>The FCS database has been installed/updated.</p>
<?
}

?>

</body>
</html>
