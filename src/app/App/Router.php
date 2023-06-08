<?php

namespace Rezabdullah\App;

use Rezabdullah\Exception\AuthorizationException;
use Rezabdullah\Exception\DatabaseException;
use Rezabdullah\Exception\StorageException;
use Rezabdullah\Exception\ValidationException;
use Rezabdullah\Helper\Http;
use Rezabdullah\Helper\LoggerBuilder;

class Router 
{
    private static array $routes = [];

    public static function add(
        string $httpMethod = 'GET',
        string $path = '/',
        string $controller = '',
        string $method = '',
        array $middleware = []
    ) : void 
    {
        self::$routes[] = compact('httpMethod', 'path', 'controller', 'method', 'middleware');
    }

    public static function run(): void
    {
        $path = isset($_SERVER['PATH_INFO']) ? '/'. trim($_SERVER['PATH_INFO'], '/') .'/' : '/v1/';
        $path = str_replace('//', '/', $path);
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $isInvalidHttpMethod = false;

        $logger = LoggerBuilder::run();

        $logger->info('Path: '. $path);
        $logger->info('Method: '. $httpMethod);
        
        foreach (self::$routes as $route) {
            if (preg_match("#^/" . trim($route['path'], "/") . "/$#", $path, $variables)) {
                if($httpMethod != $route['httpMethod']) {
                    $isInvalidHttpMethod = true;
                }
                else {
                    try {
                        $isInvalidHttpMethod = false;

                        foreach ($route['middleware'] as $middleware){
                            $instance = new $middleware;
                            $instance->before();
                        }

                        Http::filterRequestBody();

                        $method = $route['method'];
                        $controller = new $route['controller'];
                        
                        if(!method_exists($controller, $method)) {
                            echo Http::responseError('', 500);

                            $logger->error('Method '. $method .' not found in class '. $route['controller']);
                            $logger->error('Http response code: '. http_response_code());
                            return;
                        }
                    
                        array_shift($variables);
                        $results = call_user_func_array([$controller, $method], $variables);

                        echo Http::responseOk($results);
                        $logger->info('Response OK');

                        return;
                    }
                    catch (AuthorizationException $e) {
                        echo Http::responseError($e->getMessage(), 401);

                        $logger->error('Http response code: '. http_response_code());
                        $logger->error($e->getMessage());

                        return;
                    } 
                    catch (ValidationException $e) {
                        echo Http::responseError($e->getMessage(), 400);

                        $logger->error('Http response code: '. http_response_code());
                        $logger->error($e->getMessage());

                        return;
                    }
                    catch (StorageException | DatabaseException $e) {
                        echo Http::responseError('', 500);

                        $logger->error('Http response code: '. http_response_code());
                        $logger->error($e->getMessage());

                        return;
                    }
                }
            }
        }

        if($isInvalidHttpMethod) {
            echo "Method not allowed (405)";
            http_response_code(405);

            $logger->error('Http response code: '. http_response_code());
            return;
        }

        echo "Endpoint not found (404)";
        http_response_code(404);
        $logger->error('Http response code: '. http_response_code());
    }
}