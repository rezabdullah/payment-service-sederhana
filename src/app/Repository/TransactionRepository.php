<?php

namespace Rezabdullah\Repository;

use Rezabdullah\Model\Transaction;
use Rezabdullah\Exception\DatabaseException;

class TransactionRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Transaction $transaction): Transaction
    {
        $statement = $this->connection->prepare("INSERT INTO transactions(
        item_name,
        amount,
        payment_type,
        customer_name,
        invoice_id,
        merchant_id,
        number_va
        ) VALUES (?, ?, ?, ?, ?, ?, ?)");

        $excecute = $statement->execute([
            $transaction->item_name,
            $transaction->amount,
            $transaction->payment_type,
            $transaction->customer_name,
            $transaction->invoice_id,
            $transaction->merchant_id,
            $transaction->number_va,
        ]);

        if(!$excecute) {
            $errors = $statement->errorInfo();

            throw new DatabaseException($errors[2]);
        }

        $transaction->references_id = $this->connection->lastInsertId();

        return $transaction;
    }

    public function findByInvoiceIdAndMerchantId(string $invoiceId, string $merchantId): ?Transaction
    {
        $statement = $this->connection->prepare("SELECT * FROM transactions WHERE invoice_id = ? AND merchant_id = ?");
        $excecute = $statement->execute([
            $invoiceId,
            $merchantId
        ]);

        if(!$excecute) {
            $errors = $statement->errorInfo();

            throw new DatabaseException($errors[2]);
        }

        try {
            if ($row = $statement->fetch()) {
                $transaction = new Transaction();
                $transaction->references_id = $row['references_id'];
                $transaction->item_name = $row['item_name'];
                $transaction->amount = $row['amount'];
                $transaction->payment_type = $row['payment_type'];
                $transaction->customer_name = $row['customer_name'];
                $transaction->invoice_id = $row['invoice_id'];
                $transaction->merchant_id = $row['merchant_id'];
                $transaction->number_va = $row['number_va'];

                return $transaction;
            } 
            else {
                return null;
            }
        } 
        finally {
            $statement->closeCursor();
        }
    }

    public function findByReferenceId(string $referenceId): ?Transaction
    {
        $statement = $this->connection->prepare("SELECT * FROM transactions WHERE references_id = ?");
        $excecute = $statement->execute([
            $referenceId,
        ]);

        if(!$excecute) {
            $errors = $statement->errorInfo();

            throw new DatabaseException($errors[2]);
        }

        try {
            if ($row = $statement->fetch()) {
                $transaction = new Transaction();
                $transaction->references_id = $row['references_id'];
                $transaction->item_name = $row['item_name'];
                $transaction->amount = $row['amount'];
                $transaction->payment_type = $row['payment_type'];
                $transaction->customer_name = $row['customer_name'];
                $transaction->invoice_id = $row['invoice_id'];
                $transaction->merchant_id = $row['merchant_id'];
                $transaction->number_va = $row['number_va'];

                return $transaction;
            } 
            else {
                return null;
            }
        } 
        finally {
            $statement->closeCursor();
        }
    }

    public function findByReferenceIdAndMerchantId(string $referenceId, string $merchantId): ?Transaction
    {
        $statement = $this->connection->prepare("SELECT * FROM transactions WHERE references_id = ? AND merchant_id = ?");
        $excecute = $statement->execute([
            $referenceId,
            $merchantId
        ]);

        if(!$excecute) {
            $errors = $statement->errorInfo();

            throw new DatabaseException($errors[2]);
        }

        try {
            if ($row = $statement->fetch()) {
                $transaction = new Transaction();
                $transaction->references_id = $row['references_id'];
                $transaction->item_name = $row['item_name'];
                $transaction->amount = $row['amount'];
                $transaction->payment_type = $row['payment_type'];
                $transaction->customer_name = $row['customer_name'];
                $transaction->invoice_id = $row['invoice_id'];
                $transaction->merchant_id = $row['merchant_id'];
                $transaction->number_va = $row['number_va'];

                return $transaction;
            } 
            else {
                return null;
            }
        } 
        finally {
            $statement->closeCursor();
        }
    }
}