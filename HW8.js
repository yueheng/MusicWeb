var xhr = false;
	
function checkEmpty() {
	if(document.getElementById("searchTitle").value.length==0) {
		 alert("Please enter something in the search box");
		 return false;
	}
	else {
		searchTitleRaw = document.getElementById("searchTitle").value;
		searchType = document.getElementById("searchType").value;
		var toSplit = searchTitleRaw.split(" ");
		searchTitle = toSplit[0];
		for(var i = 1; i < toSplit.length; i++) {
			searchTitle += ("+"+toSplit[i]);
		}
		url = "http://cs-server.usc.edu:35266/examples/servlet/HelloWorldExample?title="+encodeURIComponent(searchTitle) +"&type="+searchType;  
		makeRequest(url);	
		return false;
	}	
}

function makeRequest(url) {	
	if(window.XMLHttpRequest) { xhr = new XMLHttpRequest();}
	else {
		if(window.ActiveXObject) {
			try{
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e) {
			}
		}
	}

	if(xhr) {
  		x = xhr.open("GET", url, true);
		xhr.onreadystatechange = myCallBack;
		xhr.setRequestHeader("Connection", "close");
 		xhr.setRequestHeader("Method", "GET"+url+"HTTP/1.1");
		xhr.send(null);
	}
	else {
		document.getElementById("updateArea").innerHTML = "Sorry, but I couldn't create an XMLHttpRequest";
	}
	
}

function myCallBack() {
	if(xhr.readyState == 4) {
		contents = "";
		if(xhr.status == 200) {			
			json = eval("(" + xhr.responseText + ")");
			resultNode = json.results.result;
			
			if(Object.keys(resultNode[0]).length == 0) contents += "<H3>" +searchTitleRaw + " of type "+ searchType +" was not found!</H3>";
			
			else {
				contents += "<CENTER>";
				contents += ("<H3>Displaying 5 results for " +document.getElementById("searchTitle").value + "</H3>");
				post = new Array();
				if(searchType == "artists") showArtists();
				else if(searchType == "albums") showAlbums();
				else if(searchType == "songs") showSongs();
			}
			document.getElementById("updateArea").innerHTML = contents;
		}
		else{	
			contents = "There was a problem with the request" + xhr.status
			document.getElementById("updateArea").innerHTML = contents;
		}		
	}	
}

function showArtists() {
	contents += "<TABLE BORDER id=tableText>\n";
	contents += "<TR><TH>Cover</TH><TH>Name</TH><TH>Genre(s)</TH><TH>Year</TH><TH>Details</TH><TH>Post To Facebook</TH></TR>\n";
	for(var i=0; i<resultNode.length; i++) {
		cover = resultNode[i].cover;
		title = resultNode[i].title;
		genre = resultNode[i].genre;
		year = resultNode[i].year;
		details = resultNode[i].details;
		contents += "<TR>";
		if(cover == "NA") contents += ("<TD>"+cover+"</TD>");
		else contents += ("<TD><IMG src=\""+cover+"\" height=100 width=100></TD>");
		contents += ("<TD>"+title+"</TD>");
		contents += ("<TD>"+genre+"</TD>");
		contents += ("<TD>"+year+"</TD>");
		contents += ("<TD><A href=\""+details+"\">details</A></TD>");
		contents += ("<TD><img src='http://cs-server.usc.edu:35266/examples/servlets/facebook.jpg' height='30' width='100' onClick=\"postToFeed("+i+")\"/></TD>");
		contents += "</TR>\n";
		post[i] = new Array();
		post[i][0] = title;
		post[i][1] = "I like " + title + " who is active since " + year;
		post[i][2] = "Genre of music is: " + genre;
		post[i][3] = details;
		if(cover == "NA") post[i][4] = 'http://cs-server.usc.edu:35266/examples/servlets/NA.jpg';
		else post[i][4] = cover;
	}
	contents += "</TABLE>\n"
}

function showAlbums() {
	contents += "<TABLE BORDER id=tableText>\n";
	contents += "<TR><TH>Cover</TH><TH>Title</TH><TH>Artist</TH><TH>Genre(s)</TH><TH>Year</TH><TH>Details</TH><TH>Post To Facebook</TH></TR>\n";
	for(var i=0; i<resultNode.length; i++) {
		cover = resultNode[i].cover;
		title = resultNode[i].title;
		artist = resultNode[i].artist;
		genre = resultNode[i].genre;
		year = resultNode[i].year;
		details = resultNode[i].details;
		contents += "<TR>";
		if(cover == "NA") contents += ("<TD>"+cover+"</TD>");
		else contents += ("<TD><IMG src=\""+cover+"\" height=100 width=100></TD>");
		contents += ("<TD>"+title+"</TD>");
		contents += ("<TD>"+artist+"</TD>");
		contents += ("<TD>"+genre+"</TD>");
		contents += ("<TD>"+year+"</TD>");
		contents += ("<TD><A href=\""+details+"\">details</A></TD>");
		contents += ("<TD><img src='http://cs-server.usc.edu:35266/examples/servlets/facebook.jpg' height='30' width='100' onClick=\"postToFeed("+i+")\"/></TD>");
		contents += "</TR>\n";
		post[i] = new Array();
		post[i][0] = title;
		post[i][1] = "I like " + title + " released in " + year;
		post[i][2] = "Artist: " + artist + " Genre: " + genre;
		post[i][3] = details;
		if(cover == "NA") post[i][4] = 'http://cs-server.usc.edu:35266/examples/servlets/NA.jpg';
		else post[i][4] = cover;
	}
	contents += "</TABLE>\n"
}

function showSongs() {
	contents += "<TABLE BORDER id=tableText>\n";
	contents += "<TR><TH>Sample</TH><TH>Title</TH><TH>Performer</TH><TH>Composer</TH><TH>Details</TH><TH>Post To Facebook</TH></TR>\n";
	for(var i=0; i<resultNode.length; i++) {
		sample = resultNode[i].sample;
		title = resultNode[i].title;
		performer = resultNode[i].performer;
		composer = resultNode[i].composer;
		details = resultNode[i].details;
		contents += "<TR>";
		if(sample == "NA") contents += ("<TD>"+sample+"</TD>");
		else contents += ("<TD><A href=\""+sample+"\">Sample</A></TD>");
		contents += ("<TD>"+title+"</TD>");
		contents += ("<TD>"+performer+"</TD>");
		contents += ("<TD>"+composer+"</TD>");
		contents += ("<TD><A href=\""+details+"\">details</A></TD>");
		contents += ("<TD><img src='http://cs-server.usc.edu:35266/examples/servlets/facebook.jpg' height='30' width='100' onClick=\"postToFeed("+i+")\"/></TD>");
		contents += "</TR>\n";
		post[i] = new Array();
		post[i][0] = title;
		post[i][1] = "I like " + title + " composed by " + composer;
		post[i][2] = "Performer: " + performer;
		post[i][3] = details;
		post[i][4] = "";
	}
	contents += "</TABLE>\n"
}
