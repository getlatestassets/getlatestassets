<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets;

use bitExpert\Disco\BeanFactoryRegistry;
use bitExpert\Disco\Annotations\Alias;
use bitExpert\Disco\Annotations\Bean;
use bitExpert\Disco\Annotations\Parameter;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Zend\Expressive\Helper\ServerUrlHelper;
use Zend\Stratigility\MiddlewarePipeInterface;
use Closure;

trait ManeroConfigTrait
{
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Router\FastRouteRouter"}),
     *     @Alias({"name" = "Zend\Expressive\Router\RouterInterface"})
     * }})
     */
    public function getZendExpressiveRouterFastRouteRouter() : \Zend\Expressive\Router\FastRouteRouter
    {
        return (new \Zend\Expressive\Router\FastRouteRouterFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Helper\ServerUrlMiddleware"})
     * }})
     */
    public function getZendExpressiveHelperServerUrlMiddleware() : \Zend\Expressive\Helper\ServerUrlMiddleware
    {
        return (new \Zend\Expressive\Helper\ServerUrlMiddlewareFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Helper\UrlHelper"})
     * }})
     */
    public function getZendExpressiveHelperUrlHelper() : \Zend\Expressive\Helper\UrlHelper
    {
        return (new \Zend\Expressive\Helper\UrlHelperFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Helper\UrlHelperMiddleware"})
     * }})
     */
    public function getZendExpressiveHelperUrlHelperMiddleware() : \Zend\Expressive\Helper\UrlHelperMiddleware
    {
        return (new \Zend\Expressive\Helper\UrlHelperMiddlewareFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Application"})
     * }})
     */
    public function getZendExpressiveApplication() : \Zend\Expressive\Application
    {
        return (new \Zend\Expressive\Container\ApplicationFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\ApplicationPipeline"})
     * }})
     */
    public function getZendExpressiveApplicationPipeline() : MiddlewarePipeInterface
    {
        return (new \Zend\Expressive\Container\ApplicationPipelineFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\HttpHandlerRunner\Emitter\EmitterInterface"})
     * }})
     */
    public function getZendHttpHandlerRunnerEmitterEmitterInterface() : \Zend\HttpHandlerRunner\Emitter\EmitterInterface
    {
        return (new \Zend\Expressive\Container\EmitterFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Stratigility\Middleware\ErrorHandler"})
     * }})
     */
    public function getZendStratigilityMiddlewareErrorHandler() : \Zend\Stratigility\Middleware\ErrorHandler
    {
        return (new \Zend\Expressive\Container\ErrorHandlerFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Handler\NotFoundHandler"}),
     *     @Alias({"name" = "Zend\Expressive\Delegate\DefaultDelegate"}),
     *     @Alias({"name" = "Zend\Expressive\Middleware\NotFoundMiddleware"})
     * }})
     */
    public function getZendExpressiveHandlerNotFoundHandler() : \Zend\Expressive\Handler\NotFoundHandler
    {
        return (new \Zend\Expressive\Container\NotFoundHandlerFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\MiddlewareContainer"})
     * }})
     */
    public function getZendExpressiveMiddlewareContainer() : \Zend\Expressive\MiddlewareContainer
    {
        return (new \Zend\Expressive\Container\MiddlewareContainerFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\MiddlewareFactory"})
     * }})
     */
    public function getZendExpressiveMiddlewareFactory() : \Zend\Expressive\MiddlewareFactory
    {
        return (new \Zend\Expressive\Container\MiddlewareFactoryFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\HttpHandlerRunner\RequestHandlerRunner"})
     * }})
     */
    public function getZendHttpHandlerRunnerRequestHandlerRunner() : \Zend\HttpHandlerRunner\RequestHandlerRunner
    {
        return (new \Zend\Expressive\Container\RequestHandlerRunnerFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Psr\Http\Message\ResponseInterface"})
     * }})
     */
    public function getPsrHttpMessageResponseInterface() : Closure
    {
        return (new \Zend\Expressive\Container\ResponseFactoryFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Response\ServerRequestErrorResponseGenerator"})
     * }})
     */
    public function getZendExpressiveResponseServerRequestErrorResponseGenerator() : \Zend\Expressive\Response\ServerRequestErrorResponseGenerator
    {
        return (new \Zend\Expressive\Container\ServerRequestErrorResponseGeneratorFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Psr\Http\Message\ServerRequestInterface"})
     * }})
     */
    public function getPsrHttpMessageServerRequestInterface() : Closure
    {
        return (new \Zend\Expressive\Container\ServerRequestFactoryFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Psr\Http\Message\StreamInterface"})
     * }})
     */
    public function getPsrHttpMessageStreamInterface() : Closure
    {
        return (new \Zend\Expressive\Container\StreamFactoryFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Router\Middleware\DispatchMiddleware"}),
     *     @Alias({"name" = "Zend\Expressive\Middleware\DispatchMiddleware"})
     * }})
     */
    public function getZendExpressiveRouterMiddlewareDispatchMiddleware() : \Zend\Expressive\Router\Middleware\DispatchMiddleware
    {
        return (new \Zend\Expressive\Router\Middleware\DispatchMiddlewareFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Router\Middleware\ImplicitHeadMiddleware"}),
     *     @Alias({"name" = "Zend\Expressive\Middleware\ImplicitHeadMiddleware"})
     * }})
     */
    public function getZendExpressiveRouterMiddlewareImplicitHeadMiddleware() : \Zend\Expressive\Router\Middleware\ImplicitHeadMiddleware
    {
        return (new \Zend\Expressive\Router\Middleware\ImplicitHeadMiddlewareFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Router\Middleware\ImplicitOptionsMiddleware"}),
     *     @Alias({"name" = "Zend\Expressive\Middleware\ImplicitOptionsMiddleware"})
     * }})
     */
    public function getZendExpressiveRouterMiddlewareImplicitOptionsMiddleware() : \Zend\Expressive\Router\Middleware\ImplicitOptionsMiddleware
    {
        return (new \Zend\Expressive\Router\Middleware\ImplicitOptionsMiddlewareFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Router\Middleware\MethodNotAllowedMiddleware"})
     * }})
     */
    public function getZendExpressiveRouterMiddlewareMethodNotAllowedMiddleware() : \Zend\Expressive\Router\Middleware\MethodNotAllowedMiddleware
    {
        return (new \Zend\Expressive\Router\Middleware\MethodNotAllowedMiddlewareFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Router\Middleware\RouteMiddleware"}),
     *     @Alias({"name" = "Zend\Expressive\Middleware\RouteMiddleware"})
     * }})
     */
    public function getZendExpressiveRouterMiddlewareRouteMiddleware() : \Zend\Expressive\Router\Middleware\RouteMiddleware
    {
        return (new \Zend\Expressive\Router\Middleware\RouteMiddlewareFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Router\RouteCollector"})
     * }})
     */
    public function getZendExpressiveRouterRouteCollector() : \Zend\Expressive\Router\RouteCollector
    {
        return (new \Zend\Expressive\Router\RouteCollectorFactory())(BeanFactoryRegistry::getInstance());
    }

    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Whoops"})
     * }})
     */
    public function getZendExpressiveWhoops() : Run
    {
        return (new \Zend\Expressive\Container\WhoopsFactory())(BeanFactoryRegistry::getInstance());
    }
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\WhoopsPageHandler"})
     * }})
     */
    public function getZendExpressiveWhoopsPageHandler() : PrettyPageHandler
    {
        return (new \Zend\Expressive\Container\WhoopsPageHandlerFactory())(BeanFactoryRegistry::getInstance());
    }

    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Helper\ServerUrlHelper"})
     * }})
     */
    public function getZendExpressiveHelperServerUrlHelper() : ServerUrlHelper
    {
        return new ServerUrlHelper();
    }
}
