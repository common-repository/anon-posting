<?php
/*
 Plugin Name: Anon Post
 Plugin URI: http://binary10.info/blog/lang/en/2009/10/anonymous-posting-wordpress-plugin
 Description: This plugin allows anonymous users to write their own posts using special page. No access to wordpress panel is needed.
 Version: 1.3
 Author: Wojciech Langiewicz
 Author URI: http://binary10.info
 */


/* Copyright 2009  Wojciech Langiewicz  (email : wlang(dont spam me)iewicz@gmail.com)
 * This script is relased under BSD license.
 */


require_once ( ABSPATH . WPINC . '/registration.php' );

//------------------------- start of options section ---------------------------

/*
 * You can change this options to suit you needs.
 *
 * If you want to use reCAPTCHA you need to get API key,
 * for more information see: http://recaptcha.net/whyrecaptcha.html
 * then click: "Sign up Now!" or log into your account
 */

$anonUserName = 'Anonymous'; // 'name' of anonymous user, don't have to exist
$category = array(); //this array should contain IDs for categories you want anonymous post to be in, example: array(1,2,4). NOTE: create those categories first, and check their numbers

$captchaEnabled = true; // enable reCAPTCHA (set to: true/false), do not use ' or "
$publickey = ''; // enter your API keys here
$privatekey = '';
$enableComments = false; // true if you want to enable comments under posts created with plugin
$my_post_type = 'publish'; // avaiable options: 'publish' for posts to be published directly
						// 'draft' if you want to validate posts (by admin for example), post will appear as draft
$enable_css = false;	// set to true/false if you want css or not (css can be edited at the end of this file)
$display_content_after_post_created = true; // set to true/false if you want to show content of page displayed above the form after post is sucessfully created on confirmation page
$use_anonComment = true; // set to true/false if you want to use Anonymous Comment plugin to allow users to comment posts creted with this plugin to be anonymously commented, remember about installing this plugin first

// ---------- end of options, DO NOT CHANGE CODE BELOW THIS LINE ------------

if($captchaEnabled){
	require_once('recaptcha/recaptchalib.php');
}

function addAnonContent(){
	global $wp_query;
	global $captchaEnabled;
	global $publickey, $enable_css, $display_content_after_post_created;
	
	$form = "<br>";
	
	$this_post_id = $wp_query->post->ID;

	if(get_post_meta($this_post_id, 'anonPost', true) == 'true'){
		//add css:
		
		if($enable_css)
			$form .= add_css();

		if($_POST){
			$form .= enteredByPOST();
		}
		else{
			$form .= displayFormStart();
			if($captchaEnabled){
				$form .= '<center>';
				$form .= recaptcha_get_html($publickey);
				$form .= '</center>';
			}
			$form .= displayFormEnd();
		}
	}
	else{
		return get_the_content();
	}

	if($display_content_after_post_created){
		return get_the_content().$form;
	}
	else{
		return $form;
	}
	
	//this should not happen
	//return get_the_content();
}

function enteredByPOST(){
	global $captchaEnabled;
	global $privatekey, $publickey;
	global $anonUserName, $category, $enableComments, $my_post_type;
	
	$to_return = '';

	if ((!$captchaEnabled) || recaptcha_check_answer($privatekey, $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field'])->is_valid) {
		//check if user exists, if not, add this user
       	if(username_exists($anonUserName)){
       		$user_id = get_profile( 'ID', $anonUserName );
       	}
       	else{
			$user_id = wp_create_user($anonUserName, wp_generate_password(20, false), 'no@email.com' );
       	}

       	$my_post = array(
       		'post_title' => $_POST['Title'],
       		'post_content' => $_POST['Message'],
       		'post_status' => $my_post_type,
       		'post_author' => $user_id,
       		'post_category' => $category,
       	//this thing enables normal comments only when comments are open and using Anon Comment is turned off
       		'comment_status' => (($enableComments && !$use_anonComment)  ? 'open' : 'closed'),
       		'ping_status' => 'closed'
       	);
		
		// Insert the post into the database
		$post_id = wp_insert_post( $my_post );
		if($post_id === 0){
			$to_return .= 'error adding post';
		}
		else{
			//add meta tag, so AnonComment could add its comment field
			if($use_anonComment){
				add_post_meta($post_id, 'AnonComment', 'true', true);
			}
			
			//display new post link
			//mod_rewrite will work with normal url's, like ?p=123
			$urlArray = explode('?', $_SERVER['REQUEST_URI']);
			$url = $urlArray[0];
			$to_return .= 'post sucessfully added to database, post page: <a href='.$url.'?p='.$post_id.'>is here</a>';
		}		
	}
	else{
		//error validating captcha
		$error = $resp->error;
		$to_return .= $error;
		$to_return .= 'reCAPTCHA validation failed, try again';
		$to_return .= displayFormStart($_POST['Title'], $_POST['Message']);
		$to_return .= recaptcha_get_html($publickey);
		$to_return .= displayFormEnd();
	}	
	return $to_return;
}

function displayFormStart($title = '', $message = ''){
	$form =<<< FORMSTART
<form action="{$_SERVER['REQUEST_URI']}" method="post">
Post title:<br><input type="text" name="Title" value="{$title}" size="40" maxlength="200"><br>
Your message:<br><textarea name="Message" cols=50 rows=20>{$message}</textarea><br>
FORMSTART;
	
	return $form;
}

function displayFormEnd(){
	$form_end =<<< FORMEND
<input type="submit" name="submit" value="Submit">
</form>
<br>
FORMEND;
	
	return $form_end;
}

function add_css(){
	$css =<<< CSS
	<style type="text/css">
	form {
		align: center;
	}
	textarea {
		background-color: white;
		color: black;
		align: center;
	}
	</style>
CSS;
	return $css;
}

add_action('the_content', 'addAnonContent');