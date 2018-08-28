<?php

return array(
	'auth' => array(
		'client_id'         => null,
		'client_id_proxy'   => null,
		'client_secret'     => null,
		'redirect_uri'      => \Uri::create('instagram/handler/subscribe'),
		'scope'             => array('basic', 'public_content'),
		'display'           => ''
	),

	'api_url'           => 'https://api.instagram.com/v1',
	'api_url_proxy'     => 'https://api.social.salient.aws.prop.cm/instagram',

	'api_headers' => array(
		'X-APP-KEY' => 'Eo.L#A/4KX5OlshGY+[1eO$U{OB/#,%iHb@bmkQje*|^tv>C#umi~D+":pjmY(t',
	),

	/**
	 * Change the place for the defaul nav to be placed.
	 */
	'nav' => 'Your CMS',

	/**
	 * Set to true to auto approve submissions
	 */
	'auto_approve' => false,
);
