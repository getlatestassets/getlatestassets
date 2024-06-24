<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets\Handler;

use GuzzleHttp\Psr7\Response;
use Org_Heigl\GetLatestAssets\Service\GithubService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;
use Zend\Diactoros\Response\RedirectResponse;

class GithubHandler implements RequestHandlerInterface
{
    private $service;

    public function __construct(GithubService $service)
    {
        $this->service = $service;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        $user    = urldecode($route->getArgument('user'));
        $project = urldecode($route->getArgument('project'));
        $file    = urldecode($route->getArgument('name'));
        $query   = $request->getQueryParams();
        $constraint = null;
        if (isset($query['version'])) {
            $constraint = htmlentities($query['version']);
        }

        $url = ($this->service)($user, $project, $file, $constraint);

        return new Response(302, ['location' => (string) $url]);
    }
}
