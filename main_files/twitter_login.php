<?php 

require("twitteroauth/twitteroauth.php");  
session_start(); 





// The TwitterOAuth instance  
$twitteroauth = new TwitterOAuth('Bg51zTzQToqbxPilR02Jg', 'LzohomSfD0DyqSSwQUVPqmaJ2cbGwV3e7QwReYhlU');  
// Requesting authentication tokens, the parameter is the URL we will be redirected to  
$request_token = $twitteroauth->getRequestToken('http://aadiprabhu.netne.net/twitter_timeline/twitter_oauth.php');  
  
// Saving them into the session  
$_SESSION['oauth_request_token'] = $request_token['oauth_token'];  
$_SESSION['oauth_request_token_secret'] = $request_token['oauth_token_secret'];  
  
// If everything goes well..  
if($twitteroauth->http_code==200){  
    // Let's generate the URL and redirect  
    $url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']); 
    header('Location: '. $url); 
} else { 
    // It's a bad idea to kill the script, but we've got to know when there's an error.  
    die('Something wrong happened.');  
}  

?>