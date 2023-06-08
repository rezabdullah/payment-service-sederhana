<?php

namespace Rezabdullah\Service;

use Rezabdullah\Config\Database;
use Rezabdullah\Model\Transaction;
use Rezabdullah\Exception\ValidationException;
use Rezabdullah\Repository\TransactionRepository;

class TransactionService
{
    private TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function getTransaction(array $request): ?Transaction
    {
        $this->validateGetTransactionRequest($request);

        try {
            Database::beginTransaction();

            $transaction = $this->transactionRepository->findByReferenceIdAndMerchantId($request['references_id'], $request['merchant_id']);

            if ($transaction == null) {
                throw new ValidationException("References Id and Merchant Id does not exist");
            }

            Database::commitTransaction();

            return $transaction;
        } 
        catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function validateGetTransactionRequest(array $request)
    {
        if (empty($request['references_id']) || empty($request['merchant_id'])) {
            throw new ValidationException("References Id, Merchant Id can not empty");
        }
    }

    public function storeTransaction(array $request): Transaction
    {
        $this->validateStoreTransactionRequest($request);

        try {
            Database::beginTransaction();

            $transaction = $this->transactionRepository->findByInvoiceIdAndMerchantId($request['invoice_id'], $request['merchant_id']);
            if ($transaction != null) {
                throw new ValidationException("Invoice Id with Merchant Id already exists");
            }

            $virtualAccount = $request['payment_type'] === 'virtual_account' ? mt_rand(111111111, 999999999) : null;

            $transaction = new Transaction();
            $transaction->item_name = $request['item_name'];
            $transaction->amount = $request['amount'];
            $transaction->payment_type = $request['payment_type'];
            $transaction->customer_name = $request['customer_name'];
            $transaction->invoice_id = $request['invoice_id'];
            $transaction->merchant_id = $request['merchant_id'];
            $transaction->number_va = $virtualAccount;

            $transaction = $this->transactionRepository->save($transaction);

            Database::commitTransaction();

            return $transaction;
        } 
        catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }
    
    private function validateStoreTransactionRequest(array $request)
    {
        if (empty($request['invoice_id']) || empty($request['item_name']) || empty($request['amount']) || 
            empty($request['payment_type']) || empty($request['customer_name']) || empty($request['merchant_id'])) {
            throw new ValidationException("Invoice Id, Item Name, Amount, Payment Type, Customer Name, Merchant Id cannot empty");
        }

        if (!in_array($request['payment_type'], ['virtual_account', 'credit_card'])) {
            throw new ValidationException("Payment Type not allowed");
        }
    }

    public function showTransaction(array $request): Transaction
    {
        $this->validateUpdateTransactionRequest($request);

        try {
            Database::beginTransaction();

            $transaction = $this->transactionRepository->findByReferenceId($request['references_id']);
            if ($transaction == null) {
                throw new ValidationException("Reference Id does not exists");
            }

            Database::commitTransaction();

            return $transaction;
        } 
        catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }
    
    private function validateUpdateTransactionRequest(array $request)
    {
        if (empty($request['references_id']) || empty($request['status'])) {
            throw new ValidationException("References Id, Status can not empty");
        }

        if (!in_array($request['status'], ['pending', 'paid', 'failed', 'expired'])) {
            throw new ValidationException("Status not allowed");
        }
    }
}