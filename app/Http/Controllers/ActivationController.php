<?php

namespace App\Http\Controllers;

use App\Services\LicenseClient;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivationController extends Controller
{
    public function __construct(private LicenseClient $client) {}

    public function show(Request $request): View
    {
        $domain = strtolower($request->getHost());
        $activated = $this->client->isPaired($domain);
        $data = $activated ? $this->client->verify($domain) : null;

        return view('activation.index', [
            'domain'    => $domain,
            'activated' => $activated,
            'valid'     => $activated && $data !== null,
            'license'   => $data,
        ]);
    }

    public function activate(Request $request)
    {
        $request->validate([
            'activation_key' => ['required', 'string', 'min:10', 'max:64'],
        ]);

        $domain = strtolower($request->getHost());
        $result = $this->client->activate($request->input('activation_key'), $domain);

        return redirect()->route('activation.show')->with(
            ($result['success'] ?? false) ? 'success' : 'error',
            $result['message'] ?? 'Aktivasi tidak diketahui hasilnya.'
        );
    }

    public function revoke()
    {
        $this->client->clearLock();

        return redirect()->route('activation.show')
            ->with('success', 'License berhasil di-revoke. Lock file dihapus.');
    }
}
