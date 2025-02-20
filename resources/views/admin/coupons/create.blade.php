<x-dashboard title="coupons">
    <h1>Create Coupon</h1>
    <form action="{{ route('admin.coupons.store') }}" method="POST">
        @csrf
        <x-form-input type="text" name="code" label="Code"/>
        <x-form-input type="number" name="discount" label="Discount (%)" min="1" max="100"/>
        <x-form-input type="datetime-local" name="valid_from" label="Valid From" min="{{ now()->format('Y-m-d\TH:i') }}"/>
        <x-form-input type="datetime-local" name="valid_until" label="Valid Until" min="{{ now()->format('Y-m-d\TH:i') }}"/>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</x-dashboard>
