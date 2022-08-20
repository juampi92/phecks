<?php

namespace Juampi92\Phecks\Domain\Sources\ValueObjects;

class RouteInfo
{
    public ?string $domain;

    public string $method;

    public string $uri;

    public ?string $name;

    public string $action;

    /** @var array<string> */
    public array $middleware;

    /**
     * @param  array<string>  $middleware
     */
    public function __construct(
        ?string $domain,
        string $method,
        string $uri,
        ?string $name,
        string $action,
        array $middleware
    ) {
        $this->domain = $domain;
        $this->method = $method;
        $this->uri = $uri;
        $this->name = $name;
        $this->action = $action;
        $this->middleware = $middleware;
    }
}
