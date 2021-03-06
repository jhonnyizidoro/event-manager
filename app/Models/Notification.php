<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Ixudra\Curl\Facades\Curl;
use App\Models\User;

class Notification extends Model
{
	protected $fillable = [
		'is_hidden', 'link', 'text'
	];

	public function users()
	{
		return $this->belongsToMany('App\Models\User', 'user_notifications', 'notification_id', 'user_id')->withPivot(['is_read', 'is_hidden']);
	}

	public function send(User $user)
	{
		$responseWeb = $responseMobile = null;

		if (!is_null($user->fcm_mobile_token)) {
			$responseMobile = Curl::to('https://fcm.googleapis.com/fcm/send')
			->asJson()
			->withTimeout(2)
			->withHeader("Authorization: key=" . env('FIREBASE_FCM_KEY'))
			->withData([
				'to' => $user->fcm_mobile_token,
				'notification' => [
					'title' => 'EVENTA',
					'body' => $this->text
				],
				'data' => [
					'model' => $this->toArray()
				]
			])
			->post();
		}

		if (!is_null($user->fcm_web_token)) {
			$responseWeb = Curl::to('https://fcm.googleapis.com/fcm/send')
			->asJson()
			->withTimeout(2)
			->withHeader("Authorization: key=" . env('FIREBASE_FCM_KEY'))
			->withData([
				'to' => $user->fcm_web_token,
				'notification' => [
					'title' => 'EVENTA',
					'body' => $this->text
				],
				'data' => [
					'model' => $this->toArray()
				]
			])
			->post();
		}

		return ['responseWeb' => $responseWeb, 'responseMobile' => $responseMobile];
	}

}
