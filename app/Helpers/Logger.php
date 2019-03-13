<?php

namespace App\Helpers;

use App\ApplicationLog;

class Logger
{

    /*
     *
     * @params array $json_data
     */
    public function log($status, string $description, array $json_data = [], $request = null)
    {
        $log = new ApplicationLog;
        $log->status = $status;
        $log->description = $description;
        $log->value = $json_data;

        if ($request) {
            $log->meta = json_encode(['user_agent' => request()->header('User-Agent'), 'ip' => request()->ip()], true);
        } else {
            $log->meta = json_encode(['no meta data']);
        }
        $log->save();
        return $this;
    }
}
