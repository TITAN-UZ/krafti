<?php

namespace App\Service;

use App\Model\Log;
use Monolog\Handler\AbstractProcessingHandler;

class Logger extends AbstractProcessingHandler
{

    /**
     * @param array $record
     */
    protected function write(array $record)
    {
        /*
         * merge $record['context'] and $record['extra'] as additional info of Processors
         * getting added to $record['extra']
         * @see https://github.com/Seldaek/monolog/blob/master/doc/02-handlers-formatters-processors.md
         */
        if (isset($record['extra'])) {
            $record['context'] = array_merge($record['context'], $record['extra']);
        }

        //'context' contains the array
        $contentArray = array_merge([
            'channel' => $record['channel'],
            'level' => $record['level'],
            'message' => $record['message'],
            'time' => date('Y-m-d H:i:s', $record['datetime']->format('U')),
        ], $record['context']);

        (new Log())
            ->fill($contentArray)
            ->save();
    }
}
