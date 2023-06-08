<?php

namespace Rezabdullah\Service;

use Rezabdullah\Config\Database;
use Rezabdullah\Model\Transaction;
use Rezabdullah\Model\TransactionDetail;
use Rezabdullah\Exception\ValidationException;
use Rezabdullah\Repository\TransactionDetailRepository;

class TransactionDetailService
{
    private TransactionDetailRepository $transactionDetailRepository;

    public function __construct(TransactionDetailRepository $transactionDetailRepository)
    {
        $this->transactionDetailRepository = $transactionDetailRepository;
    }

    public function storeTransactionDetail(Transaction $transaction, string $status = 'pending'): TransactionDetail
    {
        try {
            Database::beginTransaction();
            
            $transactionDetail = new TransactionDetail();
            $transactionDetail->references_id = $transaction->references_id;
            $transactionDetail->status = $status;
            $transactionDetail->datetime = date('Y-m-d H:i:s');

            $transactionDetail = $this->transactionDetailRepository->save($transactionDetail);

            Database::commitTransaction();

            return $transactionDetail;
        } 
        catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    public function getTransactionDetail(Transaction $transaction): ?TransactionDetail
    {
        try {
            Database::beginTransaction();
            

            $transactionDetail = $this->transactionDetailRepository->findByReferencesId($transaction->references_id);
            if ($transactionDetail == null) {
                throw new ValidationException("Detail transaksi dengan Reference Id = ". $transaction->references_id ." tidak ditemukan");
            }

            Database::commitTransaction();

            return $transactionDetail;
        } 
        catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }
}