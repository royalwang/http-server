<?php
require_once __DIR__ . '/bootstrap.php';
$binOptions = (new Aerys\Start\BinOptions)->loadOptions();
list($reactor, $server, $hosts) = (new Aerys\Start\Bootstrapper)->boot($binOptions);
register_shutdown_function(function() use ($server) {
    $fatals = [E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING];
    if (($lastError = error_get_last()) && in_array($lastError['type'], $fatals)) {
        extract($lastError);
        $errorMsg = sprintf("%s in %s on line %d", $message, $file, $line);
        //$server->logError($errorMsg);
        $server->stop();
    }
});
$server->start($hosts);
$reactor->run();