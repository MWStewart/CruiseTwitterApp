/*
	name	: 	pageInit()
	desc	:	called by the onload handler in the html, this function simply does some quick setup stuff  when the page is being loaded
*/
function pageInit()
{
	document.getElementById("accName").value='Please enter a twitter account name';
}

/*
	name	: 	getTwitterSearchResults
	desc	:	sets up ajax to update the webpage, then calls the php script to populate the div with the search results
*/
function getTwitterSearchResults() 
{
	//using ajax to output the search results without refreshing the whole page
	//had i more time I probably would have consolidated this into one function that took the name of the php script to run as a param
	
	var xmlHttp; //ajax object for the main search results
	var xmlHashtags; //ajax object for the hashtags
	var xmlProfileImage; //ajax object for the profile pic
	var accountID = document.getElementById("accName").value; //grab the twitter name from the textbox
	//alert(accountID);
	
	//check the user input is valid
	if(accountID == "")
	{
		document.getElementById("accName").value = "Please enter a twitter account name";
		return;
	}
	else
	{	
		//make sure the @ is included in the submitted account name
		if(accountID.indexOf("@") == -1) //if not...
		{
			//...add the @ to the start of the account name
			var at = "@";
			accountID = at.concat(accountID);			
		}
	}
	
	//set the value in the text box to whatever the twitter name is 
	document.getElementById("accName").value = accountID;
	
	//init the ajax objects
	if(window.XMLHttpRequest)
	{
		xmlHttp = new XMLHttpRequest();		
		xmlHashtags = new XMLHttpRequest();	
		xmlProfileImage = new XMLHttpRequest();	
	}
	else	
	{
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");	
		xmlHashtags = new ActiveXObject("Microsoft.XMLHTTP");	
		xmlProfileImage = new ActiveXObject("Microsoft.XMLHTTP");	
	}
			
	//this is the function called when the ajaxed div is ready to be updated
	xmlHttp.onreadystatechange = function()
	{
		if(4==xmlHttp.readyState && 200==xmlHttp.status)
		{
			//alert('ajax ready');
			document.getElementById("resultsPanel").innerHTML = xmlHttp.responseText; 
		}
	}
	
	xmlHashtags.onreadystatechange = function()
	{
		if(4==xmlHashtags.readyState && 200==xmlHashtags.status)					
			document.getElementById("hashtagsPanel").innerHTML = xmlHashtags.responseText;		
	}
	
	xmlProfileImage.onreadystatechange = function()
	{
		if(4==xmlProfileImage.readyState && 200==xmlProfileImage.status)					
			document.getElementById("inputImgHolder").style.backgroundImage = xmlProfileImage.responseText;
	}
	
	//call the relevant php scripts
	xmlHttp.open("GET", "./getTwitterSearchResults.php?accName="+accountID+"&mode=0", true);	//the output from this file goes into the main results div
	xmlHashtags.open("GET", "./getTwitterSearchResultsHashtags.php?accName="+accountID, true);	//the output from this file goes into the hashtag div
	xmlProfileImage.open("GET", "./getTwitterProfileImage.php?accName="+accountID, true);		//the output from this file updates the profile pic div
	xmlHttp.send();	
	xmlHashtags.send();
	xmlProfileImage.send();
}

/*
	name	: 	getTwitterSearchResultsTimeSorted
	desc	:	sets up ajax to update the webpage, then calls the php script to populate the div with the search results, after sorting by time posted
*/
function getTwitterSearchResultsTimeSorted(asc) //asc:  a boolean controlling whether or not to search ascending(true) or descending(false) in the php script
{
	var xmlHttp; //ajax objects
	var xmlHashtags;
	var xmlProfileImage;
	var accountID = document.getElementById("accName").value; 	//grab the twitter name from the textbox

	//check the user input is valid
	if(accountID == "")
	{
		document.getElementById("accName").value = "Please enter a twitter account name";
		return;
	}
	else
	{	
		//make sure the @ is included in the submitted account name
		if(accountID.indexOf("@") == -1)
		{
			//add the @ to the start of the account name
			var at = "@";
			accountID = at.concat(accountID);			
		}
	}
	
	document.getElementById("accName").value = accountID; //put in the twitter name
	
	//init the ajax objects
	if(window.XMLHttpRequest)
	{
		xmlHttp = new XMLHttpRequest();		
		xmlHashtags = new XMLHttpRequest();	
		xmlProfileImage = new XMLHttpRequest();	
	}
	else	
	{
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");	
		xmlHashtags = new ActiveXObject("Microsoft.XMLHTTP");	
		xmlProfileImage = new ActiveXObject("Microsoft.XMLHTTP");	
	}
			
	//prep the divs the ajax objects should output to
	xmlHttp.onreadystatechange = function()
	{
		if(4==xmlHttp.readyState && 200==xmlHttp.status)
		{
			//alert('ajax ready');
			document.getElementById("resultsPanel").innerHTML = xmlHttp.responseText;
		}
	}
	
	xmlHashtags.onreadystatechange = function()
	{
		if(4==xmlHashtags.readyState && 200==xmlHashtags.status)					
			document.getElementById("hashtagsPanel").innerHTML = xmlHashtags.responseText;		
	}
	
	xmlProfileImage.onreadystatechange = function()
	{
		if(4==xmlProfileImage.readyState && 200==xmlProfileImage.status)					
			document.getElementById("inputImgHolder").style.backgroundImage = xmlProfileImage.responseText;
	}
	
	//call the scripts to update the divs
	xmlHttp.open("GET", "./getTwitterSearchResults.php?accName="+accountID+"&mode=1&asc="+asc, true);			
	xmlHttp.send();
	
	xmlHashtags.open("GET", "./getTwitterSearchResultsHashtags.php?accName="+accountID, true);	
	xmlHashtags.send();
	
	xmlProfileImage.open("GET", "./getTwitterProfileImage.php?accName="+accountID, true);	
	xmlProfileImage.send();	
}

/*
	name	: 	getTwitterSearchResultsLengthSorted
	desc	:	sets up ajax to update the webpage, then calls the php script to populate the div with 
				the search results, after sorting the tweets by tweet length
*/
function getTwitterSearchResultsLengthSorted(asc) 
{
	var xmlHttp; //ajax object
	var xmlHashtags;
	var xmlProfileImage;
	var accountID = document.getElementById("accName").value; 

	//check the user input is valid
	if(accountID == "")
	{
		document.getElementById("accName").value = "Please enter a twitter account name";
		return;
	}
	else
	{	
		//make sure the @ is included in the submitted account name
		if(accountID.indexOf("@") == -1)
		{
			//add the @ to the start of the account name
			var at = "@";
			accountID = at.concat(accountID);			
		}
	}
	
	//init the ajax objects
	document.getElementById("accName").value = accountID;
	
	if(window.XMLHttpRequest)
	{
		xmlHttp = new XMLHttpRequest();		
		xmlHashtags = new XMLHttpRequest();	
		xmlProfileImage = new XMLHttpRequest();	
	}
	else	
	{
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");	
		xmlHashtags = new ActiveXObject("Microsoft.XMLHTTP");	
		xmlProfileImage = new ActiveXObject("Microsoft.XMLHTTP");	
	}
	
	//prep the divs the ajax objects should output to		
	xmlHttp.onreadystatechange = function()
	{
		if(4==xmlHttp.readyState && 200==xmlHttp.status)
		{
			//alert('ajax ready');
			document.getElementById("resultsPanel").innerHTML = xmlHttp.responseText;
		}
	}
	
	xmlHashtags.onreadystatechange = function()
	{
		if(4==xmlHashtags.readyState && 200==xmlHashtags.status)					
			document.getElementById("hashtagsPanel").innerHTML = xmlHashtags.responseText;		
	}
	
	xmlProfileImage.onreadystatechange = function()
	{
		if(4==xmlProfileImage.readyState && 200==xmlProfileImage.status)					
			document.getElementById("inputImgHolder").style.backgroundImage = xmlProfileImage.responseText;
	}
	
	//call the scripts to update the divs
	xmlHttp.open("GET", "./getTwitterSearchResults.php?accName="+accountID+"&mode=2&asc="+asc, true);		
	xmlHttp.send();
	
	xmlHashtags.open("GET", "./getTwitterSearchResultsHashtags.php?accName="+accountID, true);	
	xmlHashtags.send();
	
	xmlProfileImage.open("GET", "./getTwitterProfileImage.php?accName="+accountID, true);	
	xmlProfileImage.send();	
}

/*
	name	: 	getTwitterSearchResultsLinkFiltered
	desc	:	sets up ajax to update the webpage, then calls the php script to 
				populate the div with the search results, containing only those tweets that 
				have links in them
*/
function getTwitterSearchResultsLinkFiltered() 
{
	//ajax objects
	var xmlHttp; 
	var xmlHashtags;
	var xmlProfileImage;
	var accountID = document.getElementById("accName").value; 	//get the twitter name
	
	//check the user input is valid
	if(accountID == "")
	{
		document.getElementById("accName").value = "Please enter a twitter account name";
		return;
	}
	else
	{	
		//make sure the @ is included in the submitted account name
		if(accountID.indexOf("@") == -1)
		{
			//add the @ to the start of the account name
			var at = "@";
			accountID = at.concat(accountID);			
		}
	}
	
	//plug in the amended twitter name
	document.getElementById("accName").value = accountID;
	
	//set up the ajax objects
	if(window.XMLHttpRequest)
	{
		xmlHttp = new XMLHttpRequest();		
		xmlHashtags = new XMLHttpRequest();	
		xmlProfileImage = new XMLHttpRequest();	
	}
	else	
	{
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");	
		xmlHashtags = new ActiveXObject("Microsoft.XMLHTTP");	
		xmlProfileImage = new ActiveXObject("Microsoft.XMLHTTP");	
	}
			
	//prep the divs the ajax objects should output to
	xmlHttp.onreadystatechange = function()
	{
		if(4==xmlHttp.readyState && 200==xmlHttp.status)
		{
			//alert('ajax ready');
			document.getElementById("resultsPanel").innerHTML = xmlHttp.responseText;
		}
	}
	
	xmlHashtags.onreadystatechange = function()
	{
		if(4==xmlHashtags.readyState && 200==xmlHashtags.status)					
			document.getElementById("hashtagsPanel").innerHTML = xmlHashtags.responseText;		
	}
	
	xmlProfileImage.onreadystatechange = function()
	{
		if(4==xmlProfileImage.readyState && 200==xmlProfileImage.status)					
			document.getElementById("inputImgHolder").style.backgroundImage = xmlProfileImage.responseText;
	}
	
	//call the scripts to update the divs
	xmlHttp.open("GET", "./getTwitterSearchResults.php?accName="+accountID+"&mode=3", true);			
	xmlHttp.send();
	
	xmlHashtags.open("GET", "./getTwitterSearchResultsHashtags.php?accName="+accountID, true);	
	xmlHashtags.send();
	
	xmlProfileImage.open("GET", "./getTwitterProfileImage.php?accName="+accountID, true);	
	xmlProfileImage.send();	
}

/*
	name	: 	getTwitterSearchResultsMultHashtags
	desc	:	sets up ajax to update the webpage, then calls the php script to populate the div 
				with the search results, after sorting out which ones have more than one hashtag
*/
function getTwitterSearchResultsMultHashtags()
{
	//ajax objects
	var xmlHttp; 
	var xmlHashtags;
	var xmlProfileImage;
	var accountID = document.getElementById("accName").value; 	
	
	//check the user input is valid
	if(accountID == "")
	{
		document.getElementById("accName").value = "Please enter a twitter account name";
		return;
	}
	else
	{	
		//make sure the @ is included in the submitted account name
		if(accountID.indexOf("@") == -1)
		{
			//add the @ to the start of the account name
			var at = "@";
			accountID = at.concat(accountID);			
		}
	}
	
	document.getElementById("accName").value = accountID; //plug in the amended twitter account name
	
	//set up the ajax objects
	if(window.XMLHttpRequest)
	{
		xmlHttp = new XMLHttpRequest();		
		xmlHashtags = new XMLHttpRequest();	
		xmlProfileImage = new XMLHttpRequest();	
	}
	else	
	{
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");	
		xmlHashtags = new ActiveXObject("Microsoft.XMLHTTP");	
		xmlProfileImage = new ActiveXObject("Microsoft.XMLHTTP");	
	}
	
	//prep the divs the ajax objects should output to
	xmlHttp.onreadystatechange = function()
	{
		if(4==xmlHttp.readyState && 200==xmlHttp.status)
		{
			//alert('ajax ready');
			document.getElementById("resultsPanel").innerHTML = xmlHttp.responseText;
		}
	}
	
	xmlHashtags.onreadystatechange = function()
	{
		if(4==xmlHashtags.readyState && 200==xmlHashtags.status)					
			document.getElementById("hashtagsPanel").innerHTML = xmlHashtags.responseText;		
	}
	
	xmlProfileImage.onreadystatechange = function()
	{
		if(4==xmlProfileImage.readyState && 200==xmlProfileImage.status)					
			document.getElementById("inputImgHolder").style.backgroundImage = xmlProfileImage.responseText;
	}
	
	//call the scripts and update the divs
	xmlHttp.open("GET", "./getTwitterSearchResults.php?accName="+accountID+"&mode=4", true);		
	xmlHttp.send();
	
	xmlHashtags.open("GET", "./getTwitterSearchResultsHashtags.php?accName="+accountID, true);	
	xmlHashtags.send();
	
	xmlProfileImage.open("GET", "./getTwitterProfileImage.php?accName="+accountID, true);	
	xmlProfileImage.send();	
}
