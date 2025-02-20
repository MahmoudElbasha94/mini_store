<x-dashboard title="coupons">
    <div class="text-center mb-4">
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">Create New Coupon</a>
    </div>
    @if($coupons->isEmpty())
        <div class="alert alert-info">No coupons found.</div>
    @else
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Coupon code</th>
                <th>Discount value</th>
                <th>Valid from</th>
                <th>Valid until</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($coupons as $coupon)
                <tr>
                    <td>{{ $coupon->code }}</td>
                    <td>{{ $coupon->discount }}</td>
                    <td>{{ $coupon->valid_from->timezone('Africa/Cairo')->format('Y-m-d H:i') }}</td>
                    <td>{{ $coupon->valid_until->timezone('Africa/Cairo')->format('Y-m-d H:i') }}</td>
                    <td>
                        @if($coupon->isValid())
                            <span style="color: green;">Active</span>
                        @else
                            <span style="color: red;">Expired</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST"
                              style="display:none;" id="delete-form-{{ $coupon->id }}">
                            @csrf
                            @method('DELETE')
                        </form>
                        <button type="submit" class="btn btn-sm btn-danger"
                                onclick="confirmDelete({{ $coupon->id }})">
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $coupons->links() }}
    @endif
</x-dashboard>
