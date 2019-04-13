<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\NewEvent as NewEventRequest;
use App\Http\Requests\Event\UpdateEvent as UpdateEventRequest;
use App\Helpers\File;
use App\Models\Address;
use App\Models\Event;
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
		$request->merge([
			'user_id' => Auth::user()->id,
			'cover' => File::uploadBase64($request->cover, 'event/cover')
		]);
        //Endereço
		$address = Address::create();
		//Cria evento e vincula endereço à ele
		$request->merge(['address_id' => $address->id]);
		$event = Event::create($request->all());

		return json($event, 'Evento criado com sucesso.'); 
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
