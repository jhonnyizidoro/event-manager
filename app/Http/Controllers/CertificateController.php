<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Event;
use App\Helpers\File;
use App\Http\Requests\Certificate\UpdateCertificate as UpdateCertificateRequest;
use Auth;

class CertificateController extends Controller
{
    /**
     * Mostra a lista de certificados do usuário logado
     */
    public function index()
    {
		$certificates = Auth::user()->certificates()->get();
		return json($certificates, 'Certificados encontrados.');
    }

    /**
     * Cria um novo certificado ou atualiza o certificado do evento
     * @return Resource com o certificado criado
     */
    public function update(UpdateCertificateRequest $request)
    {
		$event = Event::find($request->event_id);
		$data = $request->all();
		$data['signature_image'] = File::uploadBase64($request->signature_image, 'certificate/signatures');
		$data['logo'] = File::uploadBase64($request->logo, 'certificate/logos');
		$certificate = Certificate::updateOrCreate(['event_id' => $event->id], $data);
		return json($certificate, 'Certificado criado.');
    }
}
