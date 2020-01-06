<?php
$time_start = microtime(true); 

use App\Kernel;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__).'/config/bootstrap.php';

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? $_ENV['TRUSTED_PROXIES'] ?? false) {
    Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
}

if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? $_ENV['TRUSTED_HOSTS'] ?? false) {
    Request::setTrustedHosts([$trustedHosts]);
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
//print_r(get_included_files());
//$execution_time = (microtime(true) - $time_start);
//echo "<b>Total Execution Time:</b> {$execution_time} secs"; die;
$response = $kernel->handle($request);
//print_r(get_included_files());
//$execution_time = (microtime(true) - $time_start);
//echo "<b>Total Execution Time:</b> {$execution_time} secs"; die;
$response->send();
$kernel->terminate($request, $response);
