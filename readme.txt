=== Anonymous Posting ===
Contributors: wlk
Tags: anonymous, anonymous posting, anon, post, captcha, recaptcha
Requires at least: 2.0
Tested up to: 2.8.6
Stable tag: 1.3

Anonymous Posting plugin allows anonymous user to write their own posts using special page, no access to WP panel is needed.

== Description ==

It allows anonymous users/readers to write their own posts, writing is protected with reCAPTCHA.
Plugin can be used for some kind of message board or public forum, you can configure comments under anonymous posts.
Writing is avaialbe via special page/post.

To setup page for writing anonymous posts do the following:<ol>
<li>Create new page/post.</li>
<li>Name it as you want, write some text to be displayed above the data form</li>
<li>Add custom field to this page (it is located in the lower part of new post page):<br>
	'anonPost' and set it to 'true' (no quotes)</li>
</ol>
Configuration options are explained on "Installation" page.
<br><br>
API key for reCAPTCHA is required to use reCAPTCHA.<br>

plugin page: [http://binary10.info/blog/2009/10/anonymous-posting-wordpress-plugin/](http://binary10.info/blog/2009/10/anonymous-posting-wordpress-plugin/)

If you know CSS please contact me, so I'll make 'new anonymous post' page look better:)

== Installation ==

Upload the AnonPost plugin to your blog, activate it, then edit this plugin to select options you want to use.

Options are located in the beginning of the anonpost.php file.

List of options:<ul>

<li>$anonUserName = 'Anonymous'; // 'name' of anonymous user, doesn't have to exist</li>
<li>$enableComments = false; // this option enables comments under anonymous posts, set to true/false</li>
<li>$category = array(); //this array should contain IDs for categories you want anonymous post to be in, example: array(1,2,4). NOTE: create those categories first</li>

<li>$captchaEnabled = true; // enable reCAPTCHA (set to: true/false)</li>
<li>$publickey = ''; // enter your API keys here</li>
<li>$privatekey = '';</li>

<li>$allowAnonComments = 'true' // if set to true this option will be using [Anonymous Comments](http://wordpress.org/extend/plugins/anon-comments/) plugin for writing comments (remember to install it first, else comments will be closed) 
</ul>
(other options are explained in file itself)

== Changelog ==

= 1.3 =
- Added support for mod_rewrite (nice looking URL's)
- Added support for Anonymous Comment plugin
- small code fixes
= 1.2 =
- Added a few new configuration options
- much better compatibility with other plug-ins
- improved efficency
= 1.0.2 =
Improved php
= 1.0.1 =
Small html code fixes
= 1.0 =
First public relase of AnonPost plugin
