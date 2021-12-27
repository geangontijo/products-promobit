<?php

// src/EventListener/ExceptionListener.php
namespace App\EventListener;

use Exception;
use PDOException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        if ($throwable instanceof PDOException) {

            if (stripos($throwable->getMessage(), '1062 ') !== false) {
                $response = new JsonResponse(['message' => 'Erro ao processar: Registro duplicado'], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        if (isset($response)) $event->setResponse($response);
    }
}
