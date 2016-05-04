<?php
	/*use thr wtitter api to get the profile image for the passed account*/
	
	include "c_TwitterApp.php";	
	$twitterApp = new c_TwitterApp();
	$twitterApp->m_getProfileImage();
?>