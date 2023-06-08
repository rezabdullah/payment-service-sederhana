<?php

namespace Rezabdullah\Model;

class Transaction
{
    public int $references_id;
    public string $item_name;
    public float $amount;
    public string $payment_type;
    public string $customer_name;
    public string $invoice_id;
    public string $merchant_id;
    public int $number_va;
}