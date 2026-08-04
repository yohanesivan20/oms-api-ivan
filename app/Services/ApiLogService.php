<?php

namespace App\Services;

use App\Models\ApiLog;

class ApiLogService
{
    public function store(array $data): void
    {
        ApiLog::create([
            'service_name' => $data['service_name'],
            'endpoint'     => $data['endpoint'],
            'method'       => $data['method'],
            'request'      => $data['request'] ?? null,
            'response'     => $data['response'] ?? null,
            'status_code'  => $data['status_code'] ?? null,
            'is_success'   => $data['is_success'],
            'error_message'=> $data['error_message'] ?? null,
        ]);
    }
}