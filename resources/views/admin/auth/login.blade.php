<x-login title="Admin Login">
    <x-normal-card header="Admin Login">
        {{--        @if ($errors->has('login_error'))--}}
        {{--            <div class="text-danger">--}}
        {{--                {{ $errors->first('login_error') }}--}}
        {{--            </div>--}}
        {{--        @endif--}}
        @error('admin_login_error')
        <div class="text-danger">{{ $message }}</div>
        @enderror
        <form action="{{ route('admin.login') }}" method="POST">
            @csrf
            <x-form-input type="email" name="email" label="Email"/>
            <x-form-input type="password" name="password" label="Password"/>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </x-normal-card>
</x-login>
