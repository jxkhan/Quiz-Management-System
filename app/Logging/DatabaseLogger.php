<?php

namespace App\Logging;

use Monolog\Logger;
use App\Models\Log as LogModel;

class DatabaseLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        return new Logger('database', [
            new \Monolog\Handler\CallbackHandler(function ($record) {
                // Ensure your 'Log' model exists with the necessary fields
                LogModel::create([
                    'level' => $record['level_name'],
                    'message' => $record['message'],
                    'context' => json_encode($record['context']),
                ]);
            }),
        ]);
    }
}
