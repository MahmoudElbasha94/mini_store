<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class SearchProducts extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage(); // إعادة تعيين الصفحة عند البحث
    }

    public function render()
    {
        $products = Product::where('name', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.search-products', compact('products'));
    }
}
