<?
include_once('DB.php');
include_once('settings.php');
class DownloadsDB extends DB {
	function __construct() {
		// $this->handle = mysql_connect(DBSERVER, DBUSER, DBPASS) or die("Error: can't connect");
		$this->handle = mysql_connect('localhost', 'root', '') or die("Error: can't connect");
		$dbase = mysql_select_db('fcsdownloads', $this->handle) or die("Error: Couldn't open database forthcoming papers data set");
	}
	
	function packages() {
		$names = $this->query('SELECT DISTINCT(package) FROM releases');
		$ret = array();
		foreach ($names as $name) { $ret[] = $name['package']; }
		return $ret;
	}
	
	function numberOfDownloadsForPackage_inDays_($package, $days=NULL) {
		$query = "SELECT COUNT(*) FROM requests, releases WHERE releases.package = '$package' and releases.id = requests.requestedFileID";
		if ($days != NULL) {
			$query .= " AND dateRequested > '" . date('Y-n-j', time() - ($days * 60 * 60 * 24)) . "'";
		}
		$tmp = $this->query($query);
		return $tmp['COUNT(*)'];
	}
	
	function recentReleases($limit=10) {
		$query = "SELECT * FROM releases ORDER BY dateAdded DESC LIMIT 0,$limit";
		return $this->query($query);
	}
	
	function numberOfDownloadsWithID_inDays_($id, $days=NULL) {
		$query = "SELECT COUNT(*) FROM requests WHERE requestedFileId=$id";
		if ($days) $query .= " AND dateRequested > '" . date('Y-n-j', time() - ($days * 60 * 60 * 24)) . "'";
		$tmp = $this->query($query);
		return $tmp['COUNT(*)'];
	}
	
	function latestReleaseOfPackage($package) {
		return $this->query("SELECT * FROM releases WHERE package='$package' ORDER BY dateAdded LIMIT 0,1");
	}
	
	function isLoggedIn() {
		$pass = $_COOKIES['password'];
		$user = $_COOKIES['username'];
		if (!$pass && !$user) {
			$pass = $_POST['password'];
			$user = $_POST['username'];
		}
		$tmp = $this->query("SELECT password FROM users WHERE name = '$user'");
		return $tmp['password'] == $pass;
	}
}
?>