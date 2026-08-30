<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\House;
use App\Models\Unit;
use App\Models\Payment;
use App\Models\Expense;

class TransactionModal extends Component
{
    use WithFileUploads;

    public $isOpen = false;
    public $txType = 'payment'; // 'payment' or 'expense'
    public $houseId = '';
    public $unitId = '';
    
    // Form fields
    public $amount = 0;
    public $date = '';
    public $notes = '';
    public $paymentType = 'rent'; // 'rent' or 'utility'
    public $expenseType = ''; // e.g. 'Luz', 'Pintura', etc.
    public $receipt;

    // Lists
    public $houses = [];
    public $units = [];

    protected $listeners = ['openTransactionModal' => 'open'];

    public function mount()
    {
        $this->date = date('Y-m-d');
        $this->houses = House::orderBy('name')->get()->toArray();
        $this->units = Unit::with('house')->orderBy('name')->get()->toArray();
    }

    public function open($type = 'payment', $houseId = '', $unitId = '')
    {
        $this->txType = $type;
        $this->houseId = $houseId;
        $this->unitId = $unitId;
        $this->amount = 0;
        $this->date = date('Y-m-d');
        $this->notes = '';
        $this->paymentType = 'rent';
        $this->expenseType = '';
        $this->receipt = null;
        
        $this->isOpen = true;
    }

    public function close()
    {
        $this->receipt = null;
        $this->isOpen = false;
    }

    public function save()
    {
        $rules = [
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];

        if ($this->txType === 'payment') {
            $rules['unitId'] = 'required|exists:units,id';
            $rules['paymentType'] = 'required|string|in:rent,utility';
        } else {
            $rules['houseId'] = 'nullable|exists:houses,id';
            $rules['unitId'] = 'nullable|exists:units,id';
            $rules['expenseType'] = 'required|string|max:255';
        }

        $this->validate($rules);

        $receiptUrl = null;
        if ($this->receipt) {
            $path = $this->receipt->store('receipts', 'public');
            $receiptUrl = '/storage/' . $path;
        }

        if ($this->txType === 'payment') {
            Payment::create([
                'unit_id' => $this->unitId,
                'amount' => $this->amount,
                'payment_date' => $this->date,
                'type' => $this->paymentType,
                'status' => 'paid',
                'notes' => $this->notes,
                'receipt_url' => $receiptUrl,
            ]);
            session()->flash('success', 'Pago registrado con éxito.');
        } else {
            Expense::create([
                'house_id' => $this->houseId ?: null,
                'unit_id' => $this->unitId ?: null,
                'type' => $this->expenseType,
                'amount' => $this->amount,
                'expense_date' => $this->date,
                'paid_by_owner' => true,
                'notes' => $this->notes,
                'receipt_url' => $receiptUrl,
            ]);
            session()->flash('success', 'Gasto registrado con éxito.');
        }

        $this->close();
        
        // Refresh the page
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.transaction-modal');
    }
}
