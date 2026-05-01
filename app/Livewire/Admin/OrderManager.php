<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderManager extends Component
{
    use WithPagination;

    public string $search        = '';
    public string $filterStatus  = '';
    public string $modal         = '';
    public ?int   $viewOrderId   = null;

    // Edit fields
    public string $status         = '';
    public string $payment_status = '';

    public function updatedSearch(): void       { $this->resetPage(); }
    public function updatedFilterStatus(): void { $this->resetPage(); }

    public function openView(int $id): void
    {
        $this->viewOrderId = $id;
        $order = Order::findOrFail($id);
        $this->status         = $order->status;
        $this->payment_status = $order->payment_status;
        $this->modal          = 'view';
    }

    public function updateOrder(): void
    {
        $this->validate([
            'status'         => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:unpaid,paid',
        ]);

        Order::findOrFail($this->viewOrderId)->update([
            'status'         => $this->status,
            'payment_status' => $this->payment_status,
        ]);

        session()->flash('success', 'Order updated.');
        $this->modal = '';
    }

    public function closeModal(): void { $this->modal = ''; }

    public function render()
    {
        $query = Order::with('user')->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhere('shipping_name', 'like', '%' . $this->search . '%')
                  ->orWhere('shipping_email', 'like', '%' . $this->search . '%');
            });
        }
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $viewOrder = $this->viewOrderId ? Order::with('items.product', 'user')->find($this->viewOrderId) : null;

        return view('livewire.admin.order-manager', [
            'orders'    => $query->paginate(20),
            'viewOrder' => $viewOrder,
        ]);
    }
}
