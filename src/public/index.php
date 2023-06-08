<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Rezabdullah\App\Router;
use Rezabdullah\Controller\TransactionController;
use Rezabdullah\Middleware\AuthorizedMiddleware;

Router::add('GET', 'v1/transactions', TransactionController::class, 'index', [AuthorizedMiddleware::class]);
Router::add('POST', 'v1/transactions', TransactionController::class, 'store', [AuthorizedMiddleware::class]);

Router::run();