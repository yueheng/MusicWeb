window.fbAsyncInit = function() {
	// init the FB JS SDK
    FB.init({
      appId      : '148272635350482',                        // App ID from the app dashboard
      channelUrl : '//cs-server.usc.edu:35266/examples/servlets/channel.html', // Channel file for x-domain comms
      status     : true,                                 // Check Facebook Login status
      xfbml      : true                                  // Look for social plugins on the page
    });

    // Additional initialization code such as adding Event Listeners goes here
};

  // Load the SDK asynchronously
(function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/all.js";
     fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

function postToFeed(i) {
    // calling the API ...
    var obj = {
		method: 'feed',
		name: post[i][0],
		link: post[i][3],
		picture: post[i][4],
		caption: post[i][1],
		description: post[i][2],
		properties: {"Look at details" : {text : "here", href: post[i][3]}}
	};

    FB.ui(obj);
}
    