@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Admin Dashboard') }}</div>

                <div class="card-body">
                    @foreach ($users as $user)
                        <div class="d-flex justify-content-between">
                            <div>{{ $user->name }} ({{ $user->email }})</div>
                            <div>
                                <form action="{{ route('admin.updateRole', $user) }}" method="POST">
                                    @csrf
                                    <select name="role" onchange="this.form.submit()">
                                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
