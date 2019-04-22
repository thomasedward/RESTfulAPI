<?php

namespace App\Transformers;

use App\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'identifier' => (int)$transaction->id,
            'transaction_quantity' => (int)$transaction->quantity,
            'buyer' => (int)$transaction->buyer_id,
            'product' => (int)$transaction->product_id,
            'creationDate_transaction' => (string)$transaction->created_at,
            'lastChange_transaction' => (string)$transaction->updated_at,
            'deletedDate_transaction' => isset($transaction->deleted_at) ? (string) $transaction->deleted_at : null,
        ];
    }
}
