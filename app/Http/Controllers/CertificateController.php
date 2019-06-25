<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Event;
use App\Helpers\File;
use App\Http\Requests\Certificate\UpdateCertificate as UpdateCertificateRequest;
use Auth;
use PDF;
use App;
use Carbon\Carbon;

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
		$request->merge([
			'signature_image' => File::uploadBase64($request->signature_image, 'certificate/signatures'),
			'logo' => File::uploadBase64($request->logo, 'certificate/logos')
		]);
		$certificate = Certificate::updateOrCreate(['event_id' => $event->id], $request->all());
		return json($certificate, 'Certificado criado.');
    }

    public function userCertificates($id = null)
    {
        $user = is_null($id) ? Auth::user() : User::find($id);
        $subscriptions = $user->subscriptions()
            ->with('event.address.city.state', 'event.owner')
            ->whereNotNull('check_out')
            ->whereNotNull('check_out')
            ->get();

        return response()->json($subscriptions, 200);
    }

    public function download($subscription)
    {
        $subscription = Subscription::findOrFail($subscription);
        $background = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/certificate-bg.png')));

        $time = $subscription->event->duration / 60;

        if (intval($time) > 0) {
            $durationString = intval($time) . (intval($time) == 1 ? ' hora' : ' horas') . ($time - intval($time) > 0 ? ' e ' . 60 * ($time - intval($time)) . ' minutos' : '');
        } else {
            $durationString = 60 * ($time - intval($time)) . ' minutos';
        }

        $pdf = PDF::loadView('pdf.certificate', [
            'subscription' => $subscription,
            'background' => $background,
            'duration' => $durationString
        ])->setPaper('a4', 'landscape');

        if (request('download')) {
            return $pdf->download("eventa_certificate_$subscription->id.pdf");
        } else {
            return $pdf->stream("eventa_certificate_$subscription->id.pdf");
        }
    }

    public function validateCertificate($code)
    {
        $subscription = Subscription::where([ 'uuid' => $code ])->first();

        if (is_null($subscription)) {
            return response()->json(['status' => false, 'msg' => 'Código não encontrado.'], 500);
        }

        return response()->json([
            'status' => true,
            'msg' => 'Certificado válido.',
            'data' => [
                'user' => $subscription->user->name,
                'event' => $subscription->event->name,
                'duration' => $subscription->event->getNiceDurationString(),
                'date' => Carbon::parse($subscription->event->starts_at)->isoFormat('D [de] MMMM [de] Y')
            ]
        ], 200);
    }
}
