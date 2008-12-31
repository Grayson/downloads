<?
// Grayson Hansard, 2008
// DB is a simple class to make working with the RFS database more convenient.

/*
Class: DB
	DB is a convenience class designed specifically for working with the SFS database.  On instantiation, it attempts
	to connect to the database automatically.  It also provides methods to easily query the database and safely escape
	strings.
*/
class DB
{
	public $last_query_num_rows = 0;
	var $handle;
	
	// On instantiation, connect to the RFS database.
	function __construct($server, $user, $pass, $database)
	{
		$this->handle = mysql_connect($server, $user, $pass) or die("Error: can't connect");
		$dbase = mysql_select_db($database, $this->handle) or die("Error: Couldn't open database forthcoming papers data set");
	}
	
	// Close the mysql connection when the DB is dealloced.
	function __destruct() {
	       mysql_close();
	  }
	
	/* 
	Function: query
	Queries the database.  In case of error, it throws an exception.  It also sets the $last_query_num_rows to 
	indicate how many records were received.  Results are converted to a hash or an array of hashes and returned.
	*/
	function query($query) {
		$result = mysql_query($query);
		if ($result == FALSE) {
			// $result = $this->query("SELECT @@ERROR AS 'Last error was:'");
			throw new Exception ( "Database error: " . mysql_error($this->handle) );
		}
		if ($result === TRUE) {
			// mysql_query() returnes TRUE if no rows were returned.  Here, I catch before mysql_fetch_assoc() can be 
			// called and return 0.  I prefer 0 to true because it tells me more explicitly that 0 results were returned.
			return 0;
		}
		$this->last_query_num_rows = mysql_num_rows($result);
		if ($this->last_query_num_rows > 1) {
			$ret = array();
			$i = 0;
			$j = $this->last_query_num_rows;
			while ($i++ < $j) $ret[] = mysql_fetch_assoc($result);
			return $ret;
		}
		return mysql_fetch_assoc($result);
	}
	
	/* 
	Function: escape
	Simple method to escape strings so that they are safe for insertion into an mysql record.
	*/
	function escape($str) {
		$str = stripslashes($str);
		return ereg_replace("'", "''", $str);
		// return addslashes($str);
	}
}

// Tester class to easily see what queries would be made using DB using  the same protocol.
class DBt extends DB {
	function __construct() {}
	function __destruct() {}
	function query($query) {
		print "<p><code>$query</code></p>";
	}
}



?>