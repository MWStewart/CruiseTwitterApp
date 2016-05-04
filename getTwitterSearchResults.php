<?php	
	/*this is the main script called by the javascript.  this creates the twitterapp object and 
		then depending on which function was called, runs the appropriate method in the class*/
	
	include "c_TwitterApp.php";	
	
	/*0=normal, 1=timesorted, 2=lengthsorted, 3=linkfiltered, 4=multhashatags*/
	$mode = $_GET["mode"];
	$twitterApp = new c_TwitterApp();
	
	if($mode == "0")
		$twitterApp->m_getSearchResults();		
	
	
	if($mode=="1")
	{
		$asc = $_GET["asc"];		
		$twitterApp->m_getTimeSortedSearchResults($asc);
	}
	
	if($mode=="2")
	{
		$asc = $_GET["asc"];	
		$twitterApp->m_getLengthSortedSearchResults($asc);
	}
	
	if($mode=="3")
		$twitterApp->m_getLinkFilteredSearchResults();
	
	
	if($mode=="4")
		$twitterApp->m_getMultHashtaggedSearchResults();
		
	//put in a call to gethashtags, and getprofileimage here?
	
?>