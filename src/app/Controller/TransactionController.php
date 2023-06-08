<?php

namespace Rezabdullah\Controller;

use Rezabdullah\Config\Database;
use Rezabdullah\Repository\TransactionDetailRepository;
use Rezabdullah\Repository\TransactionRepository;
use Rezabdullah\Service\TransactionDetailService;
use Rezabdullah\Service\TransactionService;

class TransactionController
{
    private TransactionService $transactionService;
    private TransactionDetailService $transactionDetailService;

    public function __construct()
    {
        $connection = Database::getConnection();
        
        $transactionRepository = new TransactionRepository($connection);
        $this->transactionService = new TransactionService($transactionRepository);

        $transactionDetailRepository = new TransactionDetailRepository($connection);
        $this->transactionDetailService = new TransactionDetailService($transactionDetailRepository);
    }

    public function index()
    {
        try {
            $transaction = $this->transactionService->getTransaction($_GET);
            $transactionDetail = $this->transactionDetailService->getTransactionDetail($transaction);

            return [
                'references_id' => $transaction->references_id,
                'invoice_id' => $transaction->invoice_id,
                'status' => $transactionDetail->status,
            ];
        } 
        catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function store()
    {
        try {
            $transaction = $this->transactionService->storeTransaction($_POST);
            $transactionDetail = $this->transactionDetailService->storeTransactionDetail($transaction);

            return [
                'references_id' => $transaction->references_id,
                'number_va' => $transaction->number_va,
                'status' => $transactionDetail->status,
            ];
        } 
        catch (\Exception $exception) {
            throw $exception;
        }
    }
}