<?php

namespace Rezabdullah\Middleware;

use Rezabdullah\Exception\AuthorizationException;
use Rezabdullah\Helper\Http;
use Rezabdullah\Helper\LoggerBuilder;

class AuthorizedMiddleware implements Middleware
{
    public function before(): void
    {
        include_once __DIR__ .'/../../config/app.php';

        $config = getAppConfig();
        $apiKey = $config['apiKey'];
        
        $logger = LoggerBuilder::run();
        $logger->info('Validating Bearer token request');

        if(Http::getBearerToken() !== $apiKey) {
            $logger->error('Invalid authorization token');

            throw new AuthorizationException('Invalid authorization token');
        }
    }
}