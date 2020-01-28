@extends('layout')

@section('title', "Listado de usuarios");

@section('content')

    <h1>{{ $title }}</h1>

    <a href="{{ route('users.create') }}" class="btn btn-primary mt-2">Nuevo usuario</a>

    @if(!$users->isEmpty())
        <table class="table table-bordered table-hover table-striped mt-3">
            <thead>
            <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Correo</th>
                <th scope="col">Profesión</th>
                <th scope="col">Admin</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td><a href="{{ route('users.show', ['user' => $user]) }}">{{ $user->name }}</a></td>
                    <td>{{ $user->email }}</td>
                    @if(isset($user->profession))
                        <td>{{ $user->profession->title }}</td>
                    @else
                        <td></td>
                    @endif
                    @if ($user->is_admin == 1)
                        <td><i class="fas fa-check text-success"></i></td>
                    @else
                        <td><i class="fas fa-times text-danger"></i></td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No hay usuarios registrados</p>
    @endif

@endsection

@section('sidebar')
    @parent
@endsection