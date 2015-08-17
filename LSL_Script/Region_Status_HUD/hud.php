<?php	
	$primUrl = $_GET['url'];
	
	//get the managed stats url
	$primUrl = str_replace("Eta", "devgrid.deep-horizons.net", $primUrl);
	$primUrl = str_replace("beta", "grid.deep-horizons.net", $primUrl);
			
	$statsUrl = substr($primUrl, 0, strrpos($primUrl, '/lslhttp/'));
	$statsUrl = $statsUrl . "/ManagedStats/";
	

	//get the json info we need to send back to the virtual world.
	
	//cpu %
	//region sim fps
	//ping (response time)
	$ch = curl_init(); 

	curl_setopt($ch, CURLOPT_URL, $statsUrl); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

	$output = curl_exec($ch);
	$time = curl_getinfo($ch, CURLINFO_PRETRANSFER_TIME);
	curl_close($ch);  
	
	$jsonInfo = json_decode($output, true);
	
	$region = "";
	foreach($jsonInfo['clientstack'] AS $key => $name) {
		$region = $key;
	}
	
	$regionInfo = "CPUPercent=" . $jsonInfo['server']['processor']['CPUPercent']['Value'];
	$regionInfo = $regionInfo . "&ActiveScripts=" . $jsonInfo['scene'][$region]['ActiveScripts']['Value'];
	$regionInfo = $regionInfo . "&RootAgents=" . $jsonInfo['scene'][$region]['RootAgents']['Value'];
	$regionInfo = $regionInfo . "&SimFPS=" . $jsonInfo['scene'][$region]['SimFPS']['Value'];
	$regionInfo = $regionInfo . "&TimeDilation=" . $jsonInfo['scene'][$region]['TimeDilation']['Value'];
	$regionInfo = $regionInfo . "&TotalPrims=" . $jsonInfo['scene'][$region]['TotalPrims']['Value'];
	$regionInfo = $regionInfo . "&HeapMemory=" . $jsonInfo['server']['memory']['HeapMemory']['Value'];
	$regionInfo = $regionInfo . "&ProcessMemory=" . $jsonInfo['server']['memory']['ProcessMemory']['Value'];
	$regionInfo = $regionInfo . "&IncomingPacketsMalformedCount=" . $jsonInfo['clientstack'][$region]['IncomingPacketsMalformedCount']['Value'];
	$regionInfo = $regionInfo . "&Region=" . $region;
	$regionInfo = $regionInfo . "&time=" . $time;
	
	echo $regionInfo;
	

?>