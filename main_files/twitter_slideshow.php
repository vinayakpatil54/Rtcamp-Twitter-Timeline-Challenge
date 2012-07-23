<?php 
session_start();

if(isset($_GET['user'])){ $get_screen_name = $_GET['user']; }else{ $get_screen_name =$_SESSION['screen_name'];} 

?>
<html>
<head>
<script src='http://code.jquery.com/jquery-1.4.2.min.js' type='text/javascript'></script>
<script src='slideshow/jquery.divslideshow-1.1-min.js' type='text/javascript'></script>

<style>

.slideshow {display:none; margin-bottom:20px; border:2px solid #aaaaaa; height:195px; width:500px;}
 .slide{background:#e0e0e0;font-size:12px;font-family:Tahoma;border:1px solid #cccccc; margin:3px; padding:2px;

 height:150px;
 }
 .slide-container{background:#bbbbbb; height:150px;}
 .separator{border:1px solid #aaaaaa;}
 .control{font-size:10px; width:12px; cursor:pointer;}
 .control-container{background:#bbbbbb;}
 .control-active{text-decoration:underline;}
 .control-hover{text-decoration:underline; font-weight:bold;}
</style>


<script language="javascript" type="text/javascript">
<!-- 
//Browser Support Code
function ajaxFunction(param){
	var ajaxRequest;  // The variable that makes Ajax possible!
	var global_param  = param; 
	
	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
	// Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4 && (ajaxRequest.status == 200 )){
					 
			alert(<?php echo $_GET['user'];?>);
			// var json_data = JSON.parse(ajaxRequest.responseText);
			//$("#file").load(ajaxRequest.responseText);
		//	$("#file").load(ajaxRequest.responseText);
		    $("#file").html(ajaxRequest.responseText);
    		alert("File loading successful");
			
		 
		}else if(ajaxRequest.readyState == 3){
		  $('#title_span').html(param+" server interaction");
		}else if(ajaxRequest.readyState == 1){
			 $('#title_span').html(param+" loading");
		}else if(ajaxRequest.readyState == 2){
			$('#title_span').html(param+" loaded");
		}
		else{
			 $('#title_span').html(param+" error | status: " +ajaxRequest.status+" | Text: "+ajaxRequest.statusText);
		
		}
		
	}
	ajaxRequest.open("GET", "twitter_slideshow.php?user="+param , true);
	
	ajaxRequest.send(null); 
}

//-->
</script>


</head>

<?php 
require("twitteroauth/twitteroauth.php"); 
session_start();


//echo "Slide Show Twitter";
?>
<body>
<center>
<h2>Hello <a href="twitter_slideshow.php"><?=(!empty($_SESSION['username']) ? '@' . $_SESSION['username'] : 'Guest'); ?></a></h2>  

<?php

if(!empty($_SESSION['username'])){
$twitteroauth = new TwitterOAuth('Bg51zTzQToqbxPilR02Jg', 'LzohomSfD0DyqSSwQUVPqmaJ2cbGwV3e7QwReYhlU', $_SESSION['oauth_access_token'], $_SESSION['oauth_access_token_secret']);  
  if(isset($_GET['user'])){
	  $home_timeline = $twitteroauth->get('statuses/user_timeline' , array('screen_name' =>$get_screen_name, 'count'=>10 ,'include_rts'=>1));  

  }else{
$home_timeline = $twitteroauth->get('statuses/home_timeline' , array('screen_name' =>$get_screen_name, 'count'=>10 ));  
  }
//$home_timeline = $twitteroauth->get('statuses/user_timeline' , array('screen_name' => 'ajaytated', 'count'=>10));  

$followers_id = $twitteroauth->get('followers/ids' , array('screen_name' => $_SESSION['screen_name']));  // count doesnot work  

$array_followers = array_slice($followers_id->ids,0,10);
$string_followers = implode(",",$array_followers);
$followers = $twitteroauth->get('users/lookup' , array('user_id' => $string_followers));  

?>

<?php 
 
if(isset($_GET['user'])){ $timeline = "User"; }else{ $timeline = "Home";} 
  
 echo "<h3>" . $timeline . " Timeline of :  " . "<span id=\"title_span\">". $get_screen_name."</span></h3>";

?>
<div class='slideshow'>

<?php 
 if(!empty($home_timeline)){
//print_r($home_timeline);  
  foreach($home_timeline as $count){
	  
	  
	      echo "<div class='slide'>";
		   ?>
          <div class="profile_title" style="margin:5px; height:60px; width:100%">
          <img class="profile_pic" src="<?php echo $count->user->profile_image_url;  ?>" style="padding:2px; margin-right:10px; float:left;" />
          <?php  echo "<p align=\"justify\"><strong>".$count->user->name."</strong></p>"; ?>
          </div>
          <?php
		  echo  "<hr>";
		  echo "<div style=\"clear:both\">";
		  echo "<p align=\"left\"><strong>" . $count->text ."</p></strong></br>";
		  echo "</div>";
		   echo "</div>";
	  //print_r($count);
	}   
 // echo "<hr/>";
   
   //echo $string_followers;
   //print_r($followers);
   
   //echo "<hr/>";
  // print_r($array_followers);
  
 }else{ 
   
     echo $get_screen_name ." has not tweeted yet or ";
   echo "you dont have permission to view  this profile";
  
 }
 
} // end of if to check is user logged in

?>
</div> <!--  end of slide show div -->
<h3>Your Followers : <?php echo $_SESSION['screen_name']; ?></h3>
<div style="width:300px;"> <!-- Followers  start -->
  
  <?php 
     
	   foreach($followers as $follower ){
	    
		 ?>
     <a href="twitter_slideshow.php?user=<?php echo $follower->screen_name; ?> ">    <img src="<?php echo $follower->profile_image_url; ?>" style="float:left; cursor:pointer;" hspace="5" vspace="5" title="<?php echo $follower->name."| ".$follower->screen_name; ?>"  /> </a>


                 
         <script> //onClick="ajaxFunction('<?php echo $follower->screen_name; ?>');"</script>
         
         
         <?php
	       
	   }
   
  
  ?>
</div>


<script>
    /* call divSlideShow without parameters */
    //$('.slideshow').divSlideShow();

    /* call divSlideShow with parameters */
   // $('.slideshow').divSlideShow( {width:300, height:150, arrow:'begin', controlClass:'custom', controlHoverClass:'leftarrow'} );
	
	
	$('.slideshow').divSlideShow({
		height:180, 
		width:350, 
		arrow:"split",
		loop:3,
		slideContainerClass:"slide-container",
		separatorClass:"separator", 
		controlClass:"control",  
		leftArrowClass:"control", 
		rightArrowClass:"control", 
		controlActiveClass:"control-active",
		controlHoverClass:"control-hover",
		controlContainerClass:"control-container"
		
	});
</script>

</div>
</center>
</body>
</html>