<?php

declare(strict_types=1);

namespace Org_Heigl\GetLatestAssets;

use bitExpert\Disco\Annotations\Configuration;
use bitExpert\Disco\Annotations\Bean;
use bitExpert\Disco\Annotations\Alias;
use bitExpert\Disco\BeanFactoryRegistry;
use Zend\Expressive\Middleware\WhoopsErrorResponseGenerator;

/**
 * @Configuration
 */
class DevConfigProvider extends ConfigProvider
{
    /**
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Middleware\ErrorResponseGenerator"})
     * }})
     */
    public function getZendExpressiveMiddlewareErrorResponseGenerator() : WhoopsErrorResponseGenerator
    {
        return (new \Zend\Expressive\Container\WhoopsErrorResponseGeneratorFactory())(
            BeanFactoryRegistry::getInstance()
        );
    }
}
