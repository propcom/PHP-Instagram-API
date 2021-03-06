<?php

/**
 * Propeller Instagram
 * @author Paul Westerdale <paul@propcom.co.uk>
 */

namespace Propeller\Instagram;

/**
 * This operates outside of the grounds of the usual Instagram API as it does
 * not require user credentials or auth to create.
 * This is solely created by the Application itself and managed on a per APP basis.
 */

class Subscription
{

	public static function forge($alias, $params)
	{
		$config = \Config::get('instagram.auth');
		$guid = uniqid();

		$sub = \Propeller\Instagram\Model_Subscription::query()
			->where('object_id', $params['object_id'])
			->get_one();

		if(!$sub) {
			$sub = \Propeller\Instagram\Model_Subscription::forge();
		}

		$params = array_merge($params, array(
				'verify_token' => $guid,
				'callback_url' => \Uri::create('instagram/handler/subscribe'),
				'client_id' => $config['client_id'],
				'client_secret' => $config['client_secret']
		));

		$sub->guid = $guid;
		$sub->params = json_encode($params);
		$sub->alias = $alias;
		$sub->status = 'Requested';
		$sub->object_id = $params['object_id'];
		$sub->save();


		$curl = new \Instagram\Net\CurlClient();
		$result = $curl->post('https://api.instagram.com/v1/subscriptions/', $params);
		$response = json_decode($result);


		if (
			isset($response->meta)
			and isset($response->meta->code) // Who knows what might not exist
			and $response->meta->code !== 200
		) {
			\Log::error(
				sprintf(
					'Instagram subscription failed (%s) - %s',
					$response->meta->code,
					isset($response->meta->error_message) ? $response->meta->error_message : ''
				),
				__METHOD__
			);
			throw new \RuntimeException('Instagram integration failed - ' . (isset($response->meta->error_type) ? $response->meta->error_type : ''));
		}

		$sub->instagram_subscription_id = $response->data->id;
		$sub->status = 'Live';
		$sub->save();

		return true;
	}

	public static function cancel($id)
	{
		$config = \Config::get('instagram.auth');

		$params = array(
			'client_secret' => $config['client_secret'],
			'client_id' => $config['client_id'],
			'id' => $id
		);
		$curl = new \Instagram\Net\CurlClient();
		$result = $curl->delete('https://api.instagram.com/v1/subscriptions/', $params);

		return $result;
	}

	public static function get()
	{
		$config = \Config::get('instagram.auth');

		$params = array(
			'client_secret' => $config['client_secret'],
			'client_id' => $config['client_id'],
		);
		$curl = new \Instagram\Net\CurlClient();
		$result = $curl->get('https://api.instagram.com/v1/subscriptions/', $params);
		$data = json_decode($result);
		return $data->data;

	}

	public static function fieldset()
	{
		$fieldset = \Fieldset::forge();
		$fieldset->add('name', '', array('type' => 'text', 'placeholder' => 'Name'));
		$fieldset->add('tag', '', array('type' => 'text', 'placeholder' => 'Instagram Tag'));
		$fieldset->add('submit', '', array('type' => 'submit', 'class' => 'btn'))->set_value('Create Tag Search');

		return $fieldset;
	}

}
