<?php

define('FACEBOOK_API','');

define('FACEBOOK_SECRET','');

define('REDIRECT_URI','');

$facebook_redirect_uri = 'https://www.facebook.com/v2.2/dialog/oauth?client_id='.FACEBOOK_API.'&redirect_uri='.urlencode(REDIRECT_URI).'&sdk=php-sdk-4.0.12&scope=email';

$fb_user = '';
if(!empty($_REQUEST['code'])){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/oauth/access_token?fields=email,name&client_id=".FACEBOOK_API."&redirect_uri=".urlencode(REDIRECT_URI)."&client_secret=".FACEBOOK_SECRET."&code=".$_REQUEST['code']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	$fb_params = json_decode(curl_exec($ch));
	curl_close($ch);
	if(isset($fb_params->access_token)){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/me?fields=email,name&access_token=".$fb_params->access_token); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$output = curl_exec($ch);   
		$fb_user = json_decode($output);
		curl_close($ch);
	}
}
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<?php 
	if($fb_user){
		?>
			<p>Witaj <?= $fb_user->name ?>!</p>
			<p>Twój adres email to <?= $fb_user->email ?></p>
		<?php
	}else{
		?>
			<a href="<?= $facebook_redirect_uri; ?>">Login by Facebook!</a>
		<?php
	}
?>

</body>
</html>