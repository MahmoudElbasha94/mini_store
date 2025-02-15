@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<x-login>
    <x-normal-card header="User Login">
{{--        @if ($errors->has('login_error'))--}}
{{--            <div class="text-danger">--}}
{{--                {{ $errors->first('login_error') }}--}}
{{--            </div>--}}
{{--        @endif--}}
        @error('login_error')
        <div class="text-danger">{{ $message }}</div>
        @enderror
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <x-form-input type="email" name="email" label="Email"/>
            <x-form-input type="password" name="password" label="Password"/>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </x-normal-card>
</x-login>
