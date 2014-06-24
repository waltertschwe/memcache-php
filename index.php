<?php 
    
    $mysqlHostname = "localhost";
	$mysqlUser = "root";
	$mysqlPassword = "###";
	$mysqlDatabase = "memcache";
	$bd = mysql_connect($mysqlHostname, $mysqlUser, $mysqlPassword)  or die("Opps some thing went wrong");
	mysql_select_db($mysqlDatabase, $bd) or die("Opps some thing went wrong");
	
	$memcache = new Memcache;
	$memcache->connect('localhost', 11211) or die ("Could not connect");
	
	$key = md5('Walter Testing Memcache'); // Unique Words
	$cacheResult = array();
	$cacheResult = $memcache->get($key);
	
	if($cacheResult) {
		// Second User Request
		$demosResult = $cacheResult;
		echo "<b>We have the cached result</b><br/>";
	} else {
		// First User Request 
		$result = mysql_query("select * from demos order by id");
		while($row = mysql_fetch_array($result)) {
			$demosResult[]= $row; // Results storing in array
		}
		
		$memcache->set($key, $demosResult, MEMCACHE_COMPRESSED, 1200);
		// 1200 Seconds	
	}

	// Result
	foreach($demosResult as $row) {
	    echo '<a href=' . $row['link'] . '>' . $row['title'] . '</a>';
		echo "<br/>";
	}
	
	
	
	
	
	echo "complete";
