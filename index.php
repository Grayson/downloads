<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Downloads</title>
<link rel="stylesheet" href="style.css" type="text/css" media="screen" charset="utf-8">
</head>
<body>

<?
include('DownloadsDB.php');
$db = new DownloadsDB();
?>

<h1 id="banner">Downloads</h1>
<div class="copy">
	<h1>The following packages are available for download:</h1>
	<ul>
		<?
			foreach($db->query("SELECT DISTINCT(displayName) FROM files;") as $app) {
				$app = $app['displayName'];
				echo "<li><a href=\"$app\">$app</a></li>";
			}
		?>
	</ul>
</div>

<div class="copy">
	<h1>Recent releases</h1>
	<ul>
		<?
			foreach($db->query("SELECT filename, dateAdded FROM files ORDER BY dateAdded DESC LIMIT 10;") as $release) {
				$filename = $release['filename'];
				$date = $release['dateAdded'];
				echo "<li><a href=\"$filename\">$filename</a> $date</li>";
			}
		?>
	</ul>
</div>

</body>
</html>