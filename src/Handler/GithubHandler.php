<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets\Handler;

use GuzzleHttp\Psr7\Response;
use Org_Heigl\GetLatestAssets\Service\GithubService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;
use Slim\Routing\RouteContext;
use Zend\Diactoros\Response\RedirectResponse;

class GithubHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly GithubService $service
    ) {
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        if ($route === null) {
            throw new RuntimeException('Route not found');
        }
        $user    = urldecode((string ) $route->getArgument('user'));
        $project = urldecode((string) $route->getArgument('project'));
        $file    = urldecode((string) $route->getArgument('name'));
        $query   = $request->getQueryParams();
        $constraint = null;
        if (isset($query['version'])) {
            $constraint = htmlentities($query['version']);
        }

        $url = ($this->service)($user, $project, $file, $constraint);

        return new Response(302, ['location' => (string) $url]);
    }
}
