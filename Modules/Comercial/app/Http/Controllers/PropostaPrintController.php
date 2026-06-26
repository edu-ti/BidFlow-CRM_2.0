<?php

namespace Modules\Comercial\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Comercial\Models\PropostaComercial;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class PropostaPrintController extends Controller
{
    public function imprimir($id)
    {
        $proposta = PropostaComercial::with([
            'itens.produto', 
            'oportunidade.user', 
            'fornecedor'
        ])->findOrFail($id);

        $keys = [
            'company_logo', 'razao_social', 'nome_fantasia', 'cnpj', 
            'cep', 'logradouro', 'numero', 'complemento', 'bairro', 'cidade', 'uf',
            'telefone', 'celular', 'email', 'site'
        ];
        
        $settings = Setting::whereIn('key', $keys)->pluck('value', 'key')->toArray();

        // Converter logo para base64 se existir (para evitar problemas de exibição no print)
        $logoBase64 = null;
        $logoMime = null;
        if (!empty($settings['company_logo'])) {
            $disk = Storage::disk('local'); // baseando-se no que vimos no step anterior
            if ($disk->exists($settings['company_logo'])) {
                $logoMime = $disk->mimeType($settings['company_logo']);
                $logoBase64 = base64_encode($disk->get($settings['company_logo']));
            } else {
                $diskPub = Storage::disk('public');
                if ($diskPub->exists($settings['company_logo'])) {
                    $logoMime = $diskPub->mimeType($settings['company_logo']);
                    $logoBase64 = base64_encode($diskPub->get($settings['company_logo']));
                }
            }
        }

        return view('comercial::proposta-print', compact('proposta', 'settings', 'logoBase64', 'logoMime'));
    }
}
