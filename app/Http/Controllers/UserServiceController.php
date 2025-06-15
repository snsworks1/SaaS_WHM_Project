<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\WhmServer;
use App\Services\WhmApiService;

class UserServiceController extends Controller
{
   public function getCpanelUrl($id)
{
    $service = Service::with('whmServer')->findOrFail($id);

    if ($service->user_id !== auth()->id()) {
        abort(403, '권한 없음');
    }

    $server = $service->whmServer;
    if (!$server) {
        return response()->json(['success' => false]);
    }

    $api = new WhmApiService($server);
    $url = $api->createCpanelSession($service->whm_username);

    return response()->json(['success' => true, 'url' => $url]);
}


}
