@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>👥 Crear Usuario (HU1)</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Nombre:</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Email:</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Contraseña:</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                           required>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Confirmar Contraseña:</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Rol:</label>
                    <select name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                        <option value="">Seleccionar rol</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3 form-check">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" class="form-check-input" value="1" 
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label">Usuario Activo</label>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Crear Usuario</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection