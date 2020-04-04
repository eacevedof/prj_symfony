<?php
# src/Api/Listener/PreWriteListener.php
declare(strict_types=1);
namespace App\Api\Listener;

use Symfony\Component\HttpKernel\Event\ViewEvent;

interface PreWriteListener
{
    //este método se ejecutará siempre antes de escribir en la bd
    //justo antes de que doctrine lo guarde en la bd
    public function onKernelView(ViewEvent $event):void;
}
//PreWriteListener.onKernelView(ViewEvent):void