<?php

namespace Propeller;

class Instagram
{

	public static function forge()
	{
		$auth = new \Instagram\Auth(\Config::get('instagram.auth'));

		$auth->authorize();
	}

}
