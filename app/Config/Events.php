<?php

namespace Config;

use CodeIgniter\Events\Events;
use CodeIgniter\Exceptions\FrameworkException;
use CodeIgniter\HotReloader\HotReloader;
use App\Libraries\SlackLogs;

/*
 * --------------------------------------------------------------------
 * Application Events
 * --------------------------------------------------------------------
 * Events allow you to tap into the execution of the program without
 * modifying or extending core files. This file provides a central
 * location to define your events, though they can always be added
 * at run-time, also, if needed.
 *
 * You create code that can execute by subscribing to events with
 * the 'on()' method. This accepts any form of callable, including
 * Closures, that will be executed when the event is triggered.
 *
 * Example:
 *      Events::on('create', [$myInstance, 'myMethod']);
 */

Events::on('pre_system', static function (): void {
    if (ENVIRONMENT !== 'testing') {
        if (ini_get('zlib.output_compression')) {
            throw FrameworkException::forEnabledZlibOutputCompression();
        }

        while (ob_get_level() > 0) {
            ob_end_flush();
        }

        ob_start(static fn ($buffer) => $buffer);
    }

    /*
     * --------------------------------------------------------------------
     * Debug Toolbar Listeners.
     * --------------------------------------------------------------------
     * If you delete, they will no longer be collected.
     */
    if (CI_DEBUG && ! is_cli()) {
        Events::on('DBQuery', 'CodeIgniter\Debug\Toolbar\Collectors\Database::collect');
        service('toolbar')->respond();
        // Hot Reload route - for framework use on the hot reloader.
        if (ENVIRONMENT === 'development') {
            service('routes')->get('__hot-reload', static function (): void {
                (new HotReloader())->run();
            });
        }
    }
});

// if (ENVIRONMENT === 'production') {
    Events::on('exception', function (Throwable $exception) {
        // Log dulu ke file lokal untuk memastikan Event ini jalan
        log_message('error', 'Event Slack Terpicu!'); 

        try {
            SlackLogs::send("Test Error: " . $exception->getMessage(), 'error');
        } catch (\Exception $e) {
            log_message('error', 'Gagal kirim Slack: ' . $e->getMessage());
        }
    });

    Events::on('exception', function (Throwable $exception) {
        $message = "⚠️ *DATABASE/SYSTEM ERROR* ⚠️\n\n";
        $message .= "*Message:* " . $exception->getMessage() . "\n";
        $message .= "*File:* " . $exception->getFile() . " (Line: " . $exception->getLine() . ")";

        \App\Libraries\TelegramLogs::send($message);
    });
// }
