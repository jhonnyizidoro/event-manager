<?php

namespace App\Http\Controllers;

use App\Models\EventSerie;
use App\Http\Requests\EventSerie\NewEventSerie as NewEventSerieRequest;
use App\Helpers\File;

use Auth;

class EventSerieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$series = Auth::user()->series()->where('is_active', true)->get();
		return json($series, 'Séries buscadas com sucesso!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewEventSerieRequest $request)
    {
		$request->merge([
			'cover' => File::uploadBase64($request->cover, 'serie/cover'),
			'user_id' => Auth::user()->id
		]);
		$serie = EventSerie::create($request->all());
		return json($serie, 'Série criada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EventSerie  $eventSerie
     * @return \Illuminate\Http\Response
     */
    public function show(EventSerie $eventSerie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EventSerie  $eventSerie
     * @return \Illuminate\Http\Response
     */
    public function edit(EventSerie $eventSerie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EventSerie  $eventSerie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EventSerie $eventSerie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EventSerie  $eventSerie
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$serie = EventSerie::findOrFail($id);
		$serie->update([
			'is_active' => !$serie->is_active
		]);
		return json($serie, 'Série ativada/desativada com sucesso.');
    }
}
