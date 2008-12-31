<?
if ($_POST['password']) setcookie('password', md5($_POST['password']));
if ($_POST['username']) setcookie('username', $_POST['username']);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Downloads admin</title>
<link rel="stylesheet" href="style.css" type="text/css">
<script src="mootools-core.js" type="text/javascript" charset="utf-8"></script>
<script src="admin.js" type="text/javascript" charset="utf-8"></script>
</head>
<body>

<h1 id="banner">Downloads admin</h1>

<? include("DownloadsDB.php");
$db = new DownloadsDB();
// Check for password and prompt if necessary.
if (!$db->isLoggedIn()) { ?>
	<div id="login"><h1>Log in</h1>
	<form action="" method="post" accept-charset="utf-8">
		<p><label for="password">Password: </label><input type="password" name="password" value="" id="password_entry"></input>
		<p><input type="submit" value="Continue &rarr;"></p>
	</form></div>
<?}
else { 
	$packages = $db->packages();
	?>
	
	<!-- Add new release -->
	<div class="center">
		<div id="new_release">
			<h1>Add new release</h1>
		
			<form action="addNewRelease.php" method="POST" accept-charset="utf-8">
				<p>
					<label for="package">Package</label>
					<select name="package" id="package" size="1">
						<? foreach($packages as $package) { echo "<option value=\"$package\">$package</option>"; }?>
					</select>
					<a href="#" id="add_new_package">New package</a>
				</p>

				<p><label for="version">Version</label><input type="text" name="version" value="" id="version"></p>
				<p><label for="path">Path</label><input type="text" name="path" value="" id="path"></p>
				<p style="text-align: center;"><input type="submit" value="Add release" id="submit_button" style="text-align: center; width: auto;"></p>
			</form>
		</div>
	</div>
	
	
	<!-- By package -->
	<h1 class="divider">By package</h1>
	<div id="packages">
		<? foreach($packages as $package) { ?>
			<div class="package">
			<h1><? echo $package; ?> <span><? print $db->numberOfDownloadsForPackage_inDays_($package); ?></span></h1>
				<table border="0" cellspacing="0">
<tr><td class="l">Last 24 hours</td><td><? print $db->numberOfDownloadsForPackage_inDays_($package, 1); ?></td></tr>
<tr><td class="l">Last 7 days</td><td><? print $db->numberOfDownloadsForPackage_inDays_($package, 7); ?></td></tr>
<tr><td class="l">Last 30 days</td><td><? print $db->numberOfDownloadsForPackage_inDays_($package, 30); ?></td></tr>
<tr><td class="l">Last 365 days</td><td><? print $db->numberOfDownloadsForPackage_inDays_($package, 365); ?></td></tr>
				</table>
			</div>
		<? } ?>
	</div>
	
	<h1 class="divider">By release</h1>
	<div id="recents">
		<h1>Last 10 file releases</h1>
		<table border="0" cellspacing="0" cellpadding="0">
			<tr class="h"><th></th><th class="l">Release</th><th>Last 24 hours</th><th>Last 7 days</th><th>Last 30 days</th><th>Last 365 days</th><th>Total</th></tr>
			<?
			$recents = $db->recentReleases();
			foreach($recents as $recent) {
				$filename = $recent['package'] . ' ' . $recent['version'];
				$id = $recent['id'];
				$day = $db->numberOfDownloadsWithID_inDays_($id, 1);
				$week = $db->numberOfDownloadsWithID_inDays_($id, 7);
				$month = $db->numberOfDownloadsWithID_inDays_($id, 30);
				$year = $db->numberOfDownloadsWithID_inDays_($id, 365);
				$total = $db->numberOfDownloadsWithID_inDays_($id);
				print "<tr><td><a href=\"edit.php?id=$id\">Edit</a></td><td class=\"l\">$filename</td><td>$day</td><td>$week</td><td>$month</td><td>$year</td><td>$total</td></tr>";
			}
			?>
		</table>
	</div>
	<?
}
?>


</body>
</html>