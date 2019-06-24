<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\NewEvent as NewEventRequest;
use App\Http\Requests\Event\UpdateEvent as UpdateEventRequest;
use App\Helpers\File;
use App\Models\Address;
use App\Models\Event;
use App\Models\Certificate;
use App\Models\User;
use App\Models\Staff;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Http\Request;
use App\Models\Post;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Lista eventos
     */
    public function index()
    {
		$events = Event::with([
            'address',
            'address.city',
            'address.city.state',
            'owner',
            'owner.profile'
        ])->orderBy('starts_at', 'asc')->where('is_active', true)->get();

        foreach ($events as $event) {
            $event->follows = Auth::user()->events_followed->contains($event);
        }

		return json($events, 'Eventos buscados.');
    }

    /**
     * Cria um evento e vincula um endereço à ele
     * @return Resource evento criado
     */
    public function store(NewEventRequest $request)
    {

        try {
            DB::beginTransaction();

            $address = Address::create($request->post('address'));

            $eventData = $request->post('event');
            $eventData['cover'] = File::uploadBase64($eventData['cover'], 'event/cover');
            $eventData['user_id'] = Auth::user()->id;
            $eventData['address_id'] = $address->id;
            $event = Event::create($eventData);

            if ($event->is_certified) {
                $certificateData = $request->post('certificate');
                $certificateData['logo'] = File::uploadBase64($certificateData['logo'], 'certificate/logo');
                $certificateData['signature_image'] = File::uploadBase64($certificateData['signature'], 'certificate/signature');
                $certificateData['signature_name'] = $certificateData['name'];
                $certificateData['event_id'] = $event->id;
                $certificate = Certificate::create($certificateData);
            }

            foreach ($request->post('organizers') as $organizer) {
                $user = User::findOrFail($organizer['id']);
                $event->administrators()->save($user);
            }

            foreach ($request->post('staffs') as $staff) {
                $staff = Staff::findOrFail($staff['id']);
                $event->staffs()->save($staff);
            }

            DB::commit();
            return response()->json($event, 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response(['msg' => 'Erro ao tentar salvar evento.'.$e->getMessage()], 500);
        }
    }

    /**
     * TODO: busca um evento usando o id
     * @return Resource evento buscado
     */
    public function show($id)
    {
		$event = Event::with([
            'address',
            'address.city:id,name,state_id',
            'address.city.state:id,name,code',
            'owner:id,name,nickname',
			'owner.profile',
			'followers',
			'followers.profile',
			'posts',
			'posts.user',
			'posts.user.profile',
			'posts.comments',
        ])->findOrFail($id);
		return response()->json($event, 200);
    }

    /**
     * TODO: atualiza um evento
     * @return Resource evento atualizado
     */
    public function update(UpdateEventRequest $request)
    {
		$event = Event::find($request->event_id);
		if ($request->cover) {
			$request->merge(['cover' => File::uploadBase64($request->cover, 'event/cover')]);
		}
		$event->update($request->all());
		return json($event, 'Evento atualizado');
    }

    /**
     * Ativa ou desativa um evento
	 * @return Resource: evento ativado/desativado
     */
    public function destroy($id)
    {
		$event = Event::findOrFail($id);
		$event->update([
			'is_active' => !$event->is_active
		]);
		return json($event, 'Evento ativado/desativado com sucesso.');
    }

    public function follow($id)
    {
        $event = Event::findOrFail($id);
        $follows = Auth::user()->events_followed()->where('followable_id', $event->id)->first();

        if (!is_null($follows)) {
            $follows->pivot->delete();
        } else {
            Auth::user()->events_followed()->save($event);
        }

        return response()->json('Evento seguido/deixado de seguir.', 200);
	}

	public function addPost(Request $request)
    {
        try {
            $event = Event::findOrFail(request('id'));

            if ($request->file) {
                $request->merge([
                    'image_path' => File::uploadBase64($request->file, 'events/pictures')
                ]);
            }

            $post = new Post();
            $post->fill($request->all());
            $post->user_id = Auth::user()->id;

            $event->posts()->save($post);
            $post->user;
            $post->user->profile;
            $post->comments;

            return response()->json($post, 200);

        } catch (\Exception $e) {
            return response()->json(['msg' => 'Erro ao tentar salvar post.'.$e->getMessage()], 500);
        }
    }

    public function subscribe($id)
    {
        $event = Event::findOrFail($id);

        $subscription = Auth::user()->subscriptions()->where('event_id', $event->id)->whereNull('check_in')->whereNull('check_out')->first();

        if (is_null($subscription)) {
            $model = new Subscription();
            $model->event_id = $event->id;

            Auth::user()->subscriptions()->save($model);
        } else {
            $subscription->delete();
        }

        return response()->json('Inscrição realizada/excluída', 200);
    }

    public function checkin($id)
    {
        $user = User::findOrFail(request('user_id'));
        $event = Event::findOrFail($id);

        $subscription = Subscription::where([
            'user_id' => $user->id,
            'event_id' => $event->id
        ])->first();

        if (is_null($subscription)) {
            Subscription::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'check_in' => Carbon::now(),
                'user_responsible_id' => Auth::user()->id
            ]);
        } else {
            $subscription->update([ 'check_in' => Carbon::now(), 'user_responsible_id' => Auth::user()->id ]);
        }

        return response()->json(['msg' => 'Check-in realizado com sucesso!'], 200);
    }

    public function checkout($id)
    {
        $user = User::findOrFail(request('user_id'));
        $event = Event::findOrFail($id);

        $subscription = Subscription::where([
            'user_id' => $user->id,
            'event_id' => $event->id
        ])->whereNotNull('check_in')->first();

        if (is_null($subscription)) {
            return response()->json(['msg' => 'Usuário não realizou Check-in.'], 200);
        } else {
            $subscription->update([ 'check_out' => Carbon::now() ]);
        }

        return response()->json(['msg' => 'Check-out realizado com sucesso!'], 200);
    }

    public function listPosts($id)
    {
        $event = Event::findOrFail($id);

        $posts = $event->posts()
        ->with([
            'user:id,name',
            'user.profile:id,picture,user_id',
            'comments:id,text,user_id,commentable_id,created_at',
            'comments.user:id,name',
            'comments.user.profile:id,picture,user_id',
            'comments.replies:id,text,user_id,commentable_id,created_at',
            'comments.replies.user:id,name',
            'comments.replies.user.profile:id,picture,user_id',
            'postable'
        ])
        ->where('is_active', true)
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json($posts, 200);
    }
}
