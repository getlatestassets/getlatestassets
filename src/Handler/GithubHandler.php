<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets\Handler;

use Org_Heigl\GetLatestAssets\Service\GithubService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
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
        $user    = filter_var($request->getAttribute('user'), FILTER_SANITIZE_STRING);
        $project = filter_var($request->getAttribute('project'), FILTER_SANITIZE_STRING);
        $file    = filter_var($request->getAttribute('name'), FILTER_SANITIZE_STRING);

        $url = ($this->service)($user, $project, $file);

        return new RedirectResponse($url);
    }
}
