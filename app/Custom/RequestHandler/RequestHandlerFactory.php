<?php

namespace App\Custom\RequestHandler;

class RequestHandlerFactory
{
    /**
     * Returns as Request type class from the given name
     *
     * @param string $requestType
     * @return RequestHandlerInterface
     * @throws \Exception
     */
    public static function make($requestType)
    {
        $requestType = ucfirst($requestType);

        $class = "\\App\\Custom\\RequestHandler\\{$requestType}Type";

        if (!class_exists($class)) {
            throw new \Exception("{$class} class does not exist.");
        }

        return new $class;
    }

}
