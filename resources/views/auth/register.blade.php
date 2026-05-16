{{-- Hillary Itlhabanyeng 230777465 | Group KN3 --}}
@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-16 bg-[#F5F5F5]">
    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <h1 class="font-heading font-black text-3xl text-[#333333] mb-2">Create Account</h1>
            <p class="text-stone-400 text-sm">Join Eben Supply and start shopping</p>
        </div>

        <div class="bg-white rounded-2xl border border-stone-100 shadow-card p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                           class="form-input @error('name') ring-2 ring-red-300 @enderror"
                           placeholder="Your full name">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                           class="form-input @error('email') ring-2 ring-red-300 @enderror"
                           placeholder="you@email.com">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Password</label>
                    <input type="password" name="password" required autocomplete="new-password"
                           class="form-input @error('password') ring-2 ring-red-300 @enderror"
                           placeholder="Min. 8 characters">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" required autocomplete="new-password"
                           class="form-input"
                           placeholder="Repeat password">
                </div>

                <button type="submit" class="btn-primary w-full py-3 text-sm">
                    Create Account
                </button>

                <p class="text-center text-sm text-stone-400">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-[#333333] hover:text-[#A3A380] transition-colors font-semibold">Sign in</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection