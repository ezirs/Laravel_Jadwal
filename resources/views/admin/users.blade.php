@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Users') }}</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    @foreach ($users as $user)
                                    <tr>
                                        <th class="col-8">
                                            <div>{{ $user->name }}</div>
                                            <div>{{ $user->email }}</div>
                                        </th>
                                        <td class="col-sm-4 col-lg-2">
                                            <form action="{{ route('admin.updateRole', $user->id) }}" method="POST">
                                                @csrf
                                                <select class="form-select" name="role" onchange="confirm('Anda yakin ingin mengubah role?') ? this.form.submit() : this.form.reset()">
                                                    <option value="user" {{ $user->role == 'user' ? 'selected disabled' : '' }}>
                                                        User
                                                    </option>
                                                    <option value="admin" {{ $user->role == 'admin' ? 'selected disabled' : '' }}>
                                                        Admin
                                                    </option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if (!$users->count())
                            <div class="text-center">
                                <div>Tidak ada data</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
