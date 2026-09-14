<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Payment;
use App\Models\Unit;
use Illuminate\Support\Facades\Storage;

class EditPaymentModal extends Component
{
    use WithFileUploads;

    public $isOpen = false;
    public $paymentId = null;
    public $unitId = '';
    public $amount = 0;
    public $date = '';
    public $paymentType = 'rent';
    public $notes = '';
    public $currentReceiptUrl = null;
    public $newReceipt = null;
    public $deleteReceipt = false;
    public $units = [];

    protected $listeners = ['openEditPaymentModal' => 'open'];

    public function open($paymentId)
    {
        // Enforce Super Admin authorization
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            abort(403, 'Acceso denegado. Se requieren permisos de Super Administrador.');
        }

        $payment = Payment::with('unit.house', 'unit.tenant')->findOrFail($paymentId);

        $this->paymentId = $payment->id;
        $this->unitId = $payment->unit_id;
        $this->amount = $payment->amount;
        $this->date = $payment->payment_date;
        $this->paymentType = $payment->type;
        $this->notes = $payment->notes ?? '';
        $this->currentReceiptUrl = $payment->receipt_url;
        $this->newReceipt = null;
        $this->deleteReceipt = false;

        $this->units = Unit::with(['house', 'tenant'])->orderBy('name')->get()->map(function ($u) {
            $tenantName = $u->tenant ? $u->tenant->full_name : 'Sin inquilino asignado';
            $houseName = $u->house ? $u->house->name : 'Casa';
            return [
                'id' => $u->id,
                'name' => $u->name,
                'house_name' => $houseName,
                'tenant_name' => $tenantName,
                'label' => "{$houseName} - {$u->name} | Inquilino: {$tenantName}",
            ];
        })->toArray();

        $this->isOpen = true;
    }

    public function close()
    {
        $this->newReceipt = null;
        $this->isOpen = false;
    }

    public function removeReceipt()
    {
        $this->deleteReceipt = true;
        $this->currentReceiptUrl = null;
    }

    public function save()
    {
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            abort(403, 'Acceso denegado. Se requieren permisos de Super Administrador.');
        }

        $this->validate([
            'unitId' => 'required|exists:units,id',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'paymentType' => 'required|in:rent,utility',
            'notes' => 'nullable|string',
            'newReceipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $payment = Payment::findOrFail($this->paymentId);
        $receiptUrl = $this->currentReceiptUrl;

        if ($this->newReceipt) {
            $path = $this->newReceipt->store('receipts', 'public');
            $receiptUrl = '/storage/' . $path;
        } elseif ($this->deleteReceipt) {
            $receiptUrl = null;
        }

        $payment->update([
            'unit_id' => $this->unitId,
            'amount' => $this->amount,
            'payment_date' => $this->date,
            'type' => $this->paymentType,
            'notes' => $this->notes,
            'receipt_url' => $receiptUrl,
        ]);

        session()->flash('success', 'Pago corregido y actualizado con éxito por Super Admin.');
        $this->close();

        return redirect(request()->header('Referer') ?: route('transactions.index'));
    }

    public function render()
    {
        return view('livewire.edit-payment-modal');
    }
}
