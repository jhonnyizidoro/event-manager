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
		return $this->belongsToMany('App\Models\User', 'user_notifications', 'notification_id', 'user_id');
	}

	// public function send($users)
	// {
	// 	$response = Curl::to('https://fcm.googleapis.com/fcm/send')
	// 	->asJson()
	// 	->withTimeout(2)
	// 	->withData([
	// 		'address' => $address,
	// 		'key' => env('GOOGLE_GEOCODE_KEY')
	// 	])
	// 	->get();
	// }

	// curl -X POST -H "Authorization: key=AAAA_htbGI4:APA91bHuS2WnWTsBsPTC9O1mkr2DlOX7lEku9li2zjPClviFUnFIJYR4pkXEe39ehL9pqHXhV88bNt6XGivq9nQhAabwe4o3Lsk1CyysDFSRJy2gQDeddXpF9DiA3I_zEMdMJ6UUOvS2" -H "Content-Type: application/json" -d '{
	//     "to": "ftV4wk03T-8:APA91bGZxYb2JAOGGr4nORqQwQWLW8S2FsrBumbefa0iNE6qb6h9UfGJNS3XjqRqaTDBlrOEDD4w7ol6yPSDJzXUovQEMk6c85fXvwfdmOVPvmwnK9TsQrGq2Il39SayLr6FROgSzXhD",
	//     "notification": {
	//       "title": "FCM Message",
	//       "body": "This is an FCM Message",
	//       "icon": "./img/icons/android-chrome-192x192.png"
	//     }
	//   }' https://fcm.googleapis.com/fcm/send

}
