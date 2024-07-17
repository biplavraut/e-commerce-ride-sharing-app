<?php

namespace App\Custom\Payment\gogo;

use App\User;

class gogoWallet
{
    protected $user;
    protected $total;

    public function __construct(User $user, $total)
    {
        $this->user = $user;
        $this->total = $total;
    }

    public function operation(): bool
    {
        $gogoCash = 0;
        try {
            $gogoCash = $this->user->gogoWallet->amount;
        } catch (\Throwable $th) {
            $gogoCash = 0;
        }

        if ($gogoCash < $this->total) {
            return false;
        }

        $updatedWallet = $this->user->gogoWallet->update(['amount' => $gogoCash - $this->total]);

        return true;
    }
}
