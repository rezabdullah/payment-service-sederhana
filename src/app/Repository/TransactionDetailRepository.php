<?php

namespace Rezabdullah\Repository;

use Rezabdullah\Model\TransactionDetail;
use Rezabdullah\Exception\DatabaseException;

class TransactionDetailRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(TransactionDetail $transactionDetail): TransactionDetail
    {
        $statement = $this->connection->prepare("INSERT INTO transaction_details(
        references_id,
        status,
        datetime
        ) VALUES (?, ?, ?)");

        $excecute = $statement->execute([
            $transactionDetail->references_id,
            $transactionDetail->status,
            $transactionDetail->datetime,
        ]);

        if(!$excecute) {
            $errors = $statement->errorInfo();

            throw new DatabaseException($errors[2]);
        }

        $transactionDetail->id = $this->connection->lastInsertId();

        return $transactionDetail;
    }

    public function findByReferencesId(string $referencesId): ?TransactionDetail
    {
        
        $statement = $this->connection->prepare("SELECT * FROM transaction_details WHERE references_id = ? ORDER BY datetime DESC limit 1");
        $excecute = $statement->execute([
            $referencesId
        ]);

        if(!$excecute) {
            $errors = $statement->errorInfo();

            throw new DatabaseException($errors[2]);
        }

        try {
            if ($row = $statement->fetch()) {
                $transactionDetail = new TransactionDetail();

                $transactionDetail->id = $row['id'];
                $transactionDetail->references_id = $row['references_id'];
                $transactionDetail->status = $row['status'];
                $transactionDetail->datetime = $row['datetime'];

                return $transactionDetail;
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