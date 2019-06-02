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
use Illuminate\Support\Facades\DB;
use Auth;

class EventController extends Controller
{
    /**
     * Lista todos os eventos do usuário logado.
     * @return EventResource eventos do usuário logado
     */
    public function index()
    {
		$events = Auth::user()->events;
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
		$event = Event::findOrFail($id);
		return json($event, 'Evento encontrado.');
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
}
