<?php

return array(
	'auth' => array(
		'client_id'     => 'e2a904382e2448958d984cffa5554956',
		'client_id_new' => 'dc227053-9aea-11e8-baaa-0a88840e70d7',
		'client_secret' => 'c080c821eef54f94844f0679ff77538f',
		'redirect_uri'  => \Uri::create('instagram/handler/subscribe'),
		'scope'         => array('basic', 'public_content'),
		'display'       => ''
	),

	'api_url'           => 'https://api.instagram.com/v1',
	'api_url_new'       => 'https://api.social.salient.aws.prop.cm/instagram',
	'api_header'        => 'X-APP-KEY:Eo.L#A/4KX5OlshGY+[1eO$U{OB/#,%iHb@bmkQje*|^tv>C#umi~D+":pjmY(t',

	/**
	 * Change the place for the defaul nav to be placed.
	 */
	'nav' => 'Your CMS',

	/**
	 * Set to true to auto approve submissions
	 */
	'auto_approve' => false,
);
