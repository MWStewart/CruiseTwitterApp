<?php
	/*uses the twitter api to find the hashtags in the 20 tweets*/
	include "c_TwitterApp.php";	
	$twitterApp = new c_TwitterApp();
	$twitterApp->m_getHashtags();
?>