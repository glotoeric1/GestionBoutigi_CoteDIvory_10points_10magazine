<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'nom',
        'contact',
        'adresse',
        'email',
        'types',

        'credit_limit',
        'credit_used',
        'wallet_balance',

        'status',
        'id_setting',
    ];

    /*
    |-----------------------------------
    | MULTI-TENANT SCOPE (IMPORTANT)
    |-----------------------------------
    */
    public function scopeTenant($query)
    {
        return $query->where('id_setting', auth()->user()->id_setting);
    }

    /*
    |-----------------------------------
    | AVAILABLE CREDIT
    |-----------------------------------
    */
    public function getAvailableCreditAttribute()
    {
        return $this->credit_limit - $this->credit_used;
    }

    /*
    |-----------------------------------
    | STATUS CHECK
    |-----------------------------------
    */
    public function isBlocked()
    {
        return $this->status === 'blocked' || $this->available_credit < 0;
    }

    /*
    |-----------------------------------
    | WALLET OPERATIONS
    |-----------------------------------
    */
    public function addDeposit_old($amount, $type = 'depot')
    {
        //Doing depot or paiement_credit, Check if the credit_used > 0 deducted the credit_used automatically and add the balance to the account "wallet_balance"
        if ($amount <= 0) {
            return false;
        }

        $type = strtolower(trim($type));

        if ($type == 'paiement_credit') {
            $this->credit_used = max(0, $this->credit_used - $amount);
        } else {
            $this->wallet_balance += $amount;
        }

        return $this->save();
    }

    public function addDeposit($amount, $type = 'depot')
    {
        if ($amount <= 0) {
            return false;
        }

        $type = strtolower(trim($type));

        if (in_array($type, ['depot', 'paiement_credit'])) {

            // First pay outstanding credit
            if ($this->credit_used > 0) {

                if ($amount >= $this->credit_used) {

                    $amount -= $this->credit_used;
                    $this->credit_used = 0;

                    // Add the remaining amount to wallet
                    $this->wallet_balance += $amount;

                } else {

                    // Amount is not enough to clear the credit
                    $this->credit_used -= $amount;
                }

            } else {

                // No credit to pay, everything goes to wallet
                $this->wallet_balance += $amount;
            }
        }

        return $this->save();
    }

    public function useWallet($amount)
    {
        $this->wallet_balance -= $amount;
        $this->save();
    }

    /*
    |-----------------------------------
    | CREDIT OPERATIONS
    |-----------------------------------
    */
    public function addCredit($amount)
    {
        $this->credit_used += $amount;

        if ($this->available_credit < 0) {
            $this->status = 'blocked';
        }

        $this->save();
    }

    public function canUseCredit($amount)
    {
        return ($this->credit_used + $amount) <= $this->credit_limit;
    }

    //Create a getAll using scopeTenant
    public function getAll()
    {
        return Client::tenant()->latest()->get();
    }

    //Get the balance using tenant scope and client id
    public function getBalance($clientId)
    {
        $client = Client::tenant()->find($clientId);
        if ($client) {
            return $client->wallet_balance;
        }
        return null;
    }

    //Add all CRUD operations using tenant scope
    public function StoreClient($data)
    {
        $data['id_setting'] = auth()->user()->id_setting;
        return Client::create($data);
    }

    public function deleteClient($id)
    {
        return Client::tenant()->find($id)->delete();
    }

    public function updateClient($id, $data)
    {
        return Client::tenant()->find($id)->update($data);
    }


}