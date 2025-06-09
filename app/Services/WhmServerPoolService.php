<?php

namespace App\Services;

use App\Models\WhmServer;

class WhmServerPoolService
{
    public function selectAvailableServer($diskSize)
    {
        return WhmServer::whereRaw('total_disk_capacity - used_disk_capacity >= ?', [$diskSize])
            ->orderByRaw('(total_disk_capacity - used_disk_capacity) DESC')
            ->first();
    }

    
}
