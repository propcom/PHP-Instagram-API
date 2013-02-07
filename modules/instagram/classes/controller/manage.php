<?php

namespace Instagram;

class Controller_Manage extends \Admin\Controller_Template
{

	public function before()
	{
		parent::before();
		\Config::load('instagram', true);
	}

	public function action_unsubscribe($id)
	{
		$cancel = \Propeller\Instagram\Subscription::cancel($id);
		\Session::set_flash('success', 'This subscription has been closed.');
		$sub = \Propeller\Instagram\Model_Subscription::query()
			->where('instagram_subscription_id', $id)
			->get_one();

		$sub->status = 'Disabled';
		$sub->save();

		\Response::redirect('/admin/instagram/manage/index');
	}

	public function action_index()
	{
		$account = \Propeller\Instagram\Model_Account::query()
			->where('active', 1)
			->get_one();

		if($account) {

			$view = \View::forge('manage');
			$view->set('fieldset', \Propeller\Instagram\Subscription::fieldset(), false);
			$subscriptions = \Propeller\Instagram\Subscription::get();
			$prop_sub = \Propeller\Instagram\Model_Subscription::find('all');
			$merged = array();

			foreach($prop_sub as $subscription) {
				$merged[$subscription->object_id] = $subscription;
			}

			foreach($subscriptions as $subscription) {
				$merged[$subscription->object_id]->instagram = $subscription;
			}

			if(\Input::post()) {
				 \Propeller\Instagram\Subscription::forge(\Input::post('name'), array(
						 'object_id' => \Input::post('tag'),
						 'aspect' => 'media',
						 'object' => 'tag'
					 )
				 );
			}

			$view->set('subscriptions', $merged);

		} else {

			$auth = new \Instagram\Auth(\Config::get('instagram.auth'));
			$auth->authorize();

		}

		$this->template->title = 'Instagram';
		$this->template->content = $view;
	}
}
