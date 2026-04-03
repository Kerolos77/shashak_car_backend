<?php

namespace App\Http\Livewire\WalletTransaction;

use App\Models\User;
use App\Models\WalletTransaction;
use Livewire\Component;

class Edit extends Component
{
    public array $listsForFields = [];

    public WalletTransaction $walletTransaction;

    public function mount(WalletTransaction $walletTransaction)
    {
        $this->walletTransaction = $walletTransaction;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.wallet-transaction.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->walletTransaction->save();

        return redirect()->route('admin.wallet-transactions.index');
    }

    protected function rules(): array
    {
        return [
            'walletTransaction.amount' => [
                'string',
                'nullable',
            ],
            'walletTransaction.note' => [
                'string',
                'nullable',
            ],
            'walletTransaction.order_type' => [
                'string',
                'nullable',
            ],
            'walletTransaction.payment_type' => [
                'string',
                'nullable',
            ],
            'walletTransaction.transaction' => [
                'string',
                'nullable',
            ],
            'walletTransaction.user_id' => [
                'integer',
                'exists:users,id',
                'nullable',
            ],
            'walletTransaction.user_type' => [
                'string',
                'nullable',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['user'] = User::pluck('name', 'id')->toArray();
    }
}
