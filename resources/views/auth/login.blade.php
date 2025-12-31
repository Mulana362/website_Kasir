@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container my-5" style="max-width: 400px;">
    <h1 class="h4 fw-semibold text-center mb-4">Login Kasir / Admin</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login.process') }}" method="POST" class="card p-4 shadow-sm">
        @csrf

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                   value="{{ old('email') }}" required autofocus>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button class="btn btn-brand text-white w-100 mb-2">Login</button>

        <small class="text-muted d-block text-center">
            Hubungi admin jika belum punya akun.
        </small>
    </form>
</div>
@endsection
