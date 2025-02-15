<x-register>
    <x-normal-card header="User Registration">
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <x-form-input type="text" name="name" label="Name" />
            <x-form-input type="email" name="email" label="Email" />
            <x-form-input type="password" name="password" label="Password" />
            <x-form-input type="password" name="password_confirmation" label="Confirm Password" />
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </x-normal-card>
</x-register>
