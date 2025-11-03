@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg shadow-md p-8 max-w-md w-full text-center">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Bienvenido al Sistema de Asignación de Salones</h1>
            <p class="text-gray-600 mb-6">Gestiona usuarios, grupos, salones, profesores, configuraciones y asignaciones de manera eficiente.</p>
            <a href="{{ route('admin.dashboard') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                Ir al Panel Admin
            </a>
        </div>
    </div>
</div>
@endsection
