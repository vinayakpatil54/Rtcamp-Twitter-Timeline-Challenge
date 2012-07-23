<?php

require("twitteroauth/twitteroauth.php");  
session_start(); 

if(!empty($_GET['oauth_verifier']) && !empty($_SESSION['oauth_request_token']) && !empty($_SESSION['oauth_request_token_secret'])){  
    // We've got everything we need  
	
	
	// TwitterOAuth instance, with two new parameters we got in twitter_login.php  
$twitteroauth = new TwitterOAuth('Bg51zTzQToqbxPilR02Jg', 'LzohomSfD0DyqSSwQUVPqmaJ2cbGwV3e7QwReYhlU', $_SESSION['oauth_request_token'], $_SESSION['oauth_request_token_secret']);  
// Let's request the access token  
$access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']); 
// Save it in a session var 
$_SESSION['access_token'] = $access_token; 
// Let's get the user's info 
$user_info = $twitteroauth->get('account/verify_credentials'); 
// Print user's info  
//print_r($user_info);  


	$_SESSION['screen_name'] = $access_token['screen_name'];
	$_SESSION['username'] = $access_token['screen_name'];
	$_SESSION['user_id'] = $access_token['user_id'];
	$_SESSION['oauth_access_token'] = $access_token['oauth_token'];
	$_SESSION['oauth_access_token_secret'] = $access_token['oauth_token_secret'];
	
	header('Location: http://aadiprabhu.netne.net/twitter_timeline/twitter_slideshow.php'); 
	die(1);
	
} else {  
    // Something's missing, go back to square 1  
    header('Location: http://aadiprabhu.netne.net/twitter_timeline/index.php');  
}  





?>
