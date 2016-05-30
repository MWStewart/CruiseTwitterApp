<?php
/*
	class c_TwitterApp.  The code creates an object of this class.  This is the class that handles off the work
*/


include "twitteroauth/twitteroauth/twitteroauth.php";  //this is an external library used to set up the twitter authorisation

class c_TwitterApp
{
	private $str_ConsumerKey;			//these next four properties are used for the twitter api authorisation.
	private $str_consumerKeySecret;		//these are needed in order to call methods in the api
	private $str_accessToken;			//these keys are obtained by setting up an account on twitter's developer page and then setting up an app
	private $str_accessTokenSecret;
	private $twitterOauth;				//this is the authorisation object
	private $str_accName;				//holds the twitter name input by the user
	
	//constructor
	public function __construct()
	{
		//plug in the key values
		$str_consumerKey = "j4NtJk21YpDSgJiOoVAqOajWz";
		$str_consumerKeySecret = "k8gmrxWQTVTQhfGV7RRBlQgJQdeKFG8JWv3g29mrSpkEYbWMIj";
		$str_accessToken = "2554558351-I7DUDQYP8px9NSVDZhCD36eorQDuIk5i5TkeSh1";
		$str_accessTokenSecret = "S6RI74MU0slHvuuebwiAPbjElpcAbGTMxGsWSt5aMgYMP";
	
		//set up the authentication
		$this->twitterOauth = new TwitterOAuth($str_consumerKey, $str_consumerKeySecret, $str_accessToken, $str_accessTokenSecret); 
	}	
	
	//helper function used for the lengthsorted method
	public function m_lensort2($a,$b){
		return strlen($a->text)-strlen($b->text);
	}
	
	//this sets up the twitter api query
	public function m_runTwitterQuery($count, $includeEntities, $excludeReplies, $trimUser)//in time, add the api url command as a param ("user_timerline.json")
	{	
		//run the query 		
		$queryArray = array("screen_name" 	=>"".$this->str_accName."", 
						"count"	=>"".$count."", //these two fields are all we need
						"include_entities" => "".$includeEntities."", //the rest is to make the response string as short as possible (easier parsing) (was false)
						"exclude_replies" => "".$excludeReplies."",
						"trim_user" => "".$trimUser.""); //was true
						
		//url encode the array (values) before submitting the query
		$queryString = "";
		$index = 0;
		foreach($queryArray as $key => $value)	
		{
			//append key=(encoded)value to query string
			$queryString .= $key."=".rawurlencode($value);	
			if($index != count($queryArray)-1)
				$queryString.="&";
				
			++$index;
		}
	
		//return the results (array of tweets)
		$queryResult = (array)$this->twitterOauth->get("https://api.twitter.com/1.1/statuses/user_timeline.json?".$queryString); //this url changes depending on what you what the search to return
		
		return $queryResult;
	}
	
	/*this method uses the twitter api to grab the last 20 tweets from the submitted twitter account*/
	public function m_getSearchResults()
	{		
		$this->str_accName = $_GET["accName"]; //grab the user input	
				
		//run the query 		
		$result = $this->m_runTwitterQuery("20", "true", "true", "false");		
		
		//spit out the results 
		$rowColour;
		$rowIndex = 0;
		//echo var_dump($result); //use this grab the raw data 
		//return;		
		
		if (array_key_exists("error", $result) == true) //check if the twitter name is valid
		{
			echo "No records found";
			return;
		}
		
		if((count($result) == 0))  //check that there ARE results to display
			echo"No results found";
		else
		{		
			echo"<table style='border-collapse:collapse' border=0>";	
			foreach($result as $tweet)	
			{
				$date = substr($tweet->created_at, 3, 16); //grab the date by getting the substring from the 'created_at' field
				$year = substr($tweet->created_at, 26, 4); //ditto for the year
				($rowIndex%2)?$rowColour='#FFFFFF':$rowColour='#CCCCCC';
				echo"
					<tr style='background-color:".$rowColour."'>
						<td width=130px;>".$date." ".$year."</td>";
						
						$tempText = $tweet->text;
						$start = strpos($tempText, "http://t.co"); //check for a hyperlink (http://t.co), and rewrite the tweet text accordingly
						if($start == FALSE)
							echo"<td>".$tempText."</td>";
						else
						{						
							$end = strlen($tempText);
							$link = substr($tempText, $start, $end);
							$tempText = substr($tempText, 0, $start);
							echo										
							"<td>".$tempText."<a href='".$link."'>".$link."</a></td>";						
						}
					echo"	
					</tr>
				";
				
				++$rowIndex;				
			}
			echo"</table>";			
		}		
	}
	
	/*this method uses the twitter api to grab the last 20 tweets from the submitted twitter account, sorted by tweet length*/
	public function m_getLengthSortedSearchResults($sortOrder)
	{
		$this->str_accName = $_GET["accName"]; //grab the user input
		$asc = $sortOrder; 
		
		//run the query 		
		$result = $this->m_runTwitterQuery("20", "false", "true", "true");		
		
		if (array_key_exists("error", $result) == true) //check if the twitter name is valid
		{
			echo "No records found";
			return;
		}	
		
		if(count($result) == 0)  //check that there ARE results to display
			echo"No results found";
		else
		{
			//build a temp array using the date and text (look at array_push)
			$tempResults = $result;
		
			//sort the array
			//usort($tempResults,'m_lensort2'); 
			usort($tempResults, array($this, 'm_lensort2'));  //this is how to use usort with a class method
		
			if($asc == "false")
				$tempResults = array_reverse($tempResults);
		
		
			//spit out the results		
			$rowColour;
			$rowIndex = 0;
			
			echo"<table style='border-collapse:collapse' border=0>"; //make this table a css style?
			foreach($tempResults as $tweet)	
			{		
				$date = substr($tweet->created_at, 3, 16); 
				$year = substr($tweet->created_at, 26, 4); 
				($rowIndex%2)?$rowColour='#FFFFFF':$rowColour='#CCCCCC';
				echo"
					<tr style='background-color:".$rowColour."'>
						<td width=130px;>".$date." ".$year."</td>";
							$tempText = $tweet->text;
							$start = strpos($tempText, "http://t.co");
							if($start == FALSE)
								echo"<td>".$tempText."</td>";
							else
							{						
								$end = strlen($tempText);
								$link = substr($tempText, $start, $end);
								$tempText = substr($tempText, 0, $start);
								echo											
								"<td>".$tempText."<a href='".$link."'>".$link."</a></td>";						
							}
							echo"
						<td>".strlen($tweet->text)." chars</td>
					</tr>
				";
				++$rowIndex;			
			}
			echo"</table>";	
		}		
	}
	
	/*this method uses the twitter api to grab those tweets that have links*/
	public function m_getLinkFilteredSearchResults()
	{
		$this->str_accName = $_GET["accName"]; //grab the user input	
		
		//run the query 		
		$result = $this->m_runTwitterQuery("20", "true", "true", "true");		
		
		//spit out the results		
		//go through the results array, check the entities element for a url object.
		//if there is one, display the tweet. 
		
		if (array_key_exists("error", $result) == true) //if there are no results (dodgy twitter name, for example) quit out
		{
			echo "No records found";
			return;
		}
		
		$rowColour;
		$rowIndex = 0;	
		if(count($result) == 0)  //check that there ARE results to display
			echo"No results found";
		else
		{
			echo"<table style='border-collapse:collapse' border=0>";	
			foreach($result as $tweet)	
			{		
				($rowIndex%2)?$rowColour='#FFFFFF':$rowColour='#CCCCCC';
				
				//grab the entities object
				$urls = $tweet->entities->urls;
				if(sizeof($urls) > 0) //check the urls
				{
					$date = substr($tweet->created_at, 3, 16); 
					$year = substr($tweet->created_at, 26, 4); 			
					echo"
						<tr style='background-color:".$rowColour."'>
							<td width=130px;>".$date." ".$year."</td>";
							$tempText = $tweet->text;
							$start = strpos($tempText, "http://t.co");
							if($start == FALSE)
								echo"<td>".$tempText."</td>";
							else
							{						
								$end = strlen($tempText);
								$link = substr($tempText, $start, $end);
								$tempText = substr($tempText, 0, $start);
								echo											
								"<td>".$tempText."<a href='".$link."'>".$link."</a></td>";						
							}
							echo"
							<td>".strlen($tweet->text)." chars</td>
						</tr>
					";	
	
					++$rowIndex;
				}
		
				$entities = $tweet->entities;
				if(array_key_exists("media", $entities) == true)
				{
					$date = substr($tweet->created_at, 3, 16); 
					$year = substr($tweet->created_at, 26, 4); 
					echo"
						<tr style='background-color:".$rowColour."'>
							<td width=130px;>".$date." ".$year."</td>";
							$tempText = $tweet->text;
							$start = strpos($tempText, "http://t.co");
							if($start == FALSE)
								echo"<td>".$tempText."</td>";
							else
							{						
								$end = strlen($tempText);
								$link = substr($tempText, $start, $end);
								$tempText = substr($tempText, 0, $start);
								echo											
								"<td>".$tempText."<a href='".$link."'>".$link."</a></td>";						
							}
							echo"
							<td>".strlen($tweet->text)." chars</td>
						</tr>
					";
					
					++$rowIndex;
				}				
			}
			echo"</table>";	
		}
	}
	
	/*
		this method gets tweets that have more than one hashtag
	*/
	public function m_getMultHashtaggedSearchResults()
	{
		$this->str_accName = $_GET["accName"]; //grab the user input			
		
		//run the query 		
		$result = $this->m_runTwitterQuery("20", "true", "true", "true");				
					
		//spit out the results		
		//go through the results array, check the entities element for a hashtag object.
		//if there is more than one, display the tweet. 	
		//echo var_dump($result);	
		
		$rowColour;
		$rowIndex = 0;	
		
		if (array_key_exists("error", $result) == true)
		{
			echo "No records found";
			return;
		}
		
		if(count($result) == 0)  //check that there ARE results to display
			echo"No results found";
		else
		{
			$resultsFound = false;
			echo"<table style='border-collapse:collapse' border=0>";	
			foreach($result as $tweet)	
			{		
				//grab the entities->hashtags object
				$hashtags = $tweet->entities->hashtags;
				if(sizeof($hashtags) > 1) //check the number of hashtags.  we want posts that have at least 2
				{
					$resultsFound = true;
					$date = substr($tweet->created_at, 3, 16); 
					$year = substr($tweet->created_at, 26, 4); 
					($rowIndex%2)?$rowColour='#FFFFFF':$rowColour='#CCCCCC';
					echo"
						<tr style='background-color:".$rowColour."'>
							<td width=130px;>".$date." ".$year."</td>";
							$tempText = $tweet->text;
							$start = strpos($tempText, "http://t.co");
							if($start == FALSE)
								echo"<td>".$tempText."</td>";
							else
							{						
								$end = strlen($tempText);
								$link = substr($tempText, $start, $end);
								$tempText = substr($tempText, 0, $start);
								echo											
								"<td>".$tempText."<a href='".$link."'>".$link."</a></td>";						
							}
							echo"
							<!-- <td>".strlen($tweet->text)." chars</td> -->
							<!-- <td>".$hashtags[0]->text." </td> -->
						</tr>
					";
					++$rowIndex;
				}		
			}
			echo"</table>";	/**/
			
			if($resultsFound == false)
				echo"No results found";
		}
	}
	
	//this method gets the last 20 tweets, sorted by tweet date
	public function m_getTimeSortedSearchResults($sortOrder)
	{
		$this->str_accName = $_GET["accName"]; //grab the user input
		//get the sort order
		$asc = $sortOrder; 
		if($asc == 'false')
			$asc = false;
		else
			$asc = true;
			
		//run the query 				
		$result = $this->m_runTwitterQuery("20", "true", "true", "true");		
		
		if (array_key_exists("error", $result) == true) //check if the twitter name is valid
		{
			echo "No records found";
			return;
		}
				
		//sort the results
		if($asc == false)
			$result = array_reverse($result);//reverse the array
		
		//spit out the results
		$rowColour;
		$rowIndex = 0;	
		if(count($result) == 0)  //check that there ARE results to display
			echo"No results found";
		else
		{
			echo"<table style='border-collapse:collapse' border=0>";
			foreach($result as $tweet)	
			{
				$date = substr($tweet->created_at, 3, 16); //grab the date by getting the substring from the 'created_at' field
				$year = substr($tweet->created_at, 26, 4); //ditto for the year
				($rowIndex%2)?$rowColour='#FFFFFF':$rowColour='#CCCCCC';
				echo"
					<tr style='background-color:".$rowColour."'>
						<td width=130px;>".$date." ".$year."</td>";
						$tempText = $tweet->text;
						$start = strpos($tempText, "http://t.co");
						if($start == FALSE)
							echo"<td>".$tempText."</td>";
						else
						{						
							$end = strlen($tempText);
							$link = substr($tempText, $start, $end);
							$tempText = substr($tempText, 0, $start);
							echo											
							"<td>".$tempText."<a href='".$link."'>".$link."</a></td>";						
						}
					echo"
					</tr>
				";
				++$rowIndex;			
			}
			echo"</table>";	
		}		
	}
	
	//this method gets all the hashtags included in the 20 tweets and displays them in the div specified by the corresponding 
	public function m_getHashtags()
	{
		$this->str_accName = $_GET["accName"]; //grab the user input
		
		//run the query 		
		$result = $this->m_runTwitterQuery("20", "true", "true", "true");		
		
		//spit out the results	
		$noHashtagsFound = true;
		$tweetIndex=0;
		$hashtagIndex=0;
		
		if(array_key_exists("error", $result))
		{
			echo "No hashtags found";
			return;
		}
		
		foreach($result as $tweet)	
		{		
			//grab the entities object
			$hashtags = $tweet->entities->hashtags;
			if(sizeof($hashtags) > 0) //check the urls
			{			
				$noHashtagsFound = false;
				foreach($hashtags as $hashtag)
				{	
					if(($tweetIndex == count($result)-1) && ($hashtagIndex == count($hashtags)-1))
						echo "#".$hashtag->text;
					else
						echo "#".$hashtag->text.", ";
						
					++$hashtagIndex;
				}
			}
	
			++$tweetIndex;
			$hashtagIndex = 0;
		}
		
		if($noHashtagsFound == true)
			echo"No hashtags found";
	}		

	//uses the twitter api to get the profile pic of the passed account, and display it on screen
	public function m_getProfileImage()
	{
		$this->str_accName = $_GET["accName"]; //grab the user input
			
		//run the query 		
		$result = $this->m_runTwitterQuery("20", "true", "true", "false");		
		
		//spit out the results 
		$rowColour;
		$rowIndex = 0;
		//echo var_dump($result); use this grab the raw data 
		
		if(count($result) > 0)
		{		
			echo "url('".$result[0]->user->profile_image_url."')";
		}	
	}
}

?>