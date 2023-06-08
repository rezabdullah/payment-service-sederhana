<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Rezabdullah\Config\Database;
use Rezabdullah\Helper\Cli;
use Rezabdullah\Repository\TransactionDetailRepository;
use Rezabdullah\Repository\TransactionRepository;
use Rezabdullah\Service\TransactionDetailService;
use Rezabdullah\Service\TransactionService;


try {
    $parsedArgv = Cli::parsingArgv();
    $connection = Database::getConnection();

    $transactionRepository = new TransactionRepository($connection);
    $transactionService = new TransactionService($transactionRepository);

    $transactionDetailRepository = new TransactionDetailRepository($connection);
    $transactionDetailService = new TransactionDetailService($transactionDetailRepository);
    
    $transaction = $transactionService->showTransaction($parsedArgv);
    $transactionDetail = $transactionDetailService->storeTransactionDetail($transaction, $parsedArgv['status']);

    echo json_encode([
        'references_id' => $transaction->references_id,
        'invoice_id' => $transaction->invoice_id,
        'status' => $transactionDetail->status,
    ]);
    
    echo PHP_EOL;
} 
catch (\Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
}