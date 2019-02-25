<?php
	/*
		Project		:	CruiseTwitterApp
		Programmer	: 	Mikey Stewart
		Date		: 	11/06/2014
		
		Desc		:	Simple app to grab, sort and filter the last 20 tweets on a given twitter account.  
						
						sample twitter account used for testing:  @thebatman / @michaelpachter
						
						this page is the html entry point.  it's just a form.
		
	*/
	
	echo"
		<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Frameset//EN\" \"http://www.w3.org/TR/html4/frameset.dtd\">
		<html>
			<head>
				<link rel='stylesheet' type='text/css' href='style/style.css'/>
				<script src='script/twitJS.js'></script>
				<title>twitter app</title>
			</head>
			
			<body onLoad='pageInit()'>				
				<div id='main'>
					<div id='header'>
						<div id='headerTitle'>Twitter App </div>
						<div id='headerTitleText'>
							<b>About: </b> This is a small app that simply retrieves the last 20 tweets from a given account, using the Twitter API.  
							This was actually a programming exercise assigned to me as part of the interview process for a job I was going after.  Owing to time constraints 
							brought on by the fact that I didn't even have PHP or Apache installed on my machine at the time I never finished the exercise and submitted my code 
							with a LOT of features left not implemented.  I never got the job in the end but I figured, 'eff it.  Finish the code and put it in the portfolio'.<br><br>
							So that's what I did.<br><br>						
							<b>How to use: </b>Type in a Twitter account name, and press one of the search filter buttons below.
						</div>
					</div>
					
					 <div id='input'> 
						<div id='inputTitle'>Account </div>
						<div id='inputImgHolder'></div>
						<div id='inputTextBox'><input class='c_inputTextBox' type='text' id='accName' style='width:100%; font-size:20px'/></div>											
					 </div> 
				
					<div id='results'>	
						<div id='resultsTitle'>Results </div>
						<div id='resultsPanel'> 
						
						</div> 
					</div> 
					
					<div id='buttons'>
						<table border='0' style='border-collapse:collapse'>
							<tr>
								<td rowspan='2'><button class='btnType1' style='height:60px' type='button' onClick='getTwitterSearchResults()'>Last 20 Tweets</button></td>
								<td colspan='2'><button class='btnType1' style='height:30px'>Order By Date/Time</button></td>							
								<td colspan='2'><button class='btnType1' style='height:30px'>Order By Tweet Length</button></td>							
								<td rowspan='2'><button class='btnType1' style='height:60px' type='button' onClick='getTwitterSearchResultsLinkFiltered()'>Link Filter</button></td>
								<td rowspan='2'><button class='btnType1' style='height:60px' type='button' onClick='getTwitterSearchResultsMultHashtags()'>Tweets With <br>Mult. hashtags</button></td>
							</tr>
							<tr>							
								<td><button class='btnType1' style='height:30px' type='button' onClick='getTwitterSearchResultsTimeSorted(true)'>Asc</button></td>
								<td><button class='btnType1' style='height:30px' type='button' onClick='getTwitterSearchResultsTimeSorted(false)'>Desc</button></td>
								<td><button class='btnType1' style='height:30px' type='button' onClick='getTwitterSearchResultsLengthSorted(true)'>Asc</button></td>
								<td><button class='btnType1' style='height:30px' type='button' onClick='getTwitterSearchResultsLengthSorted(false)'>Desc</button></td>
							</tr>
						</table>
					</div>			
					
					<div id='hashtags'>
						<div id='hashtagsTitle'>Hashtags</div>
						<div id='hashtagsPanel'>
							
						</div>
					</div>
					
					<div id='footer'>
						Website coded by Mikey Stewart.  This uses the <a href='https://github.com/abraham/twitteroauth'>twitterouath</a> library, by Abraham Williams
					</div>
				</div>
			</body>
		</html>
	";
?>