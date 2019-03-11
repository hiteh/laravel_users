@extends('layouts.app')
@auth
    @section('content')
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('users.title') }}</div>

                        <div class="card-body">
                            @if ( $users->first() )

                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <th>{{ __( 'users.id' ) }}</th>
                                            <th>{{ __( 'users.name' ) }}</th>
                                            <th>{{ __( 'users.email' ) }}</th>
                                            <th>{{ __( 'users.role' ) }}</th>
                                            <th>{{ __( 'users.edit' ) }}</th>
                                            <th>{{ __( 'users.delete' ) }}</th>
                                        </thead>
                                        <tbody>
                                            @foreach ( $users as $user )
                                            <tr>
                                                <td>{{ $user->id }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->roles()->first()->name }}</td>
                                                <td>
                                                    <p data-placement="top" data-toggle="tooltip" title="{{ __( 'users.edit_title' ) }}" class="m-0" >
                                                        <button class="btn btn-primary btn-xs btn-edit" data-toggle="modal" data-target="#user-modal" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}" data-user-email="{{ $user->email }}" data-user-role="{{ $user->roles()->first()->name }}"><i class="fas fa-edit"></i></button>
                                                    </p>
         
                                                </td>
                                                <td>
                                                    <p data-placement="top" data-toggle="tooltip" title="{{ __( 'users.delete_title' ) }}" class="m-0" >
                                                        <button class="btn btn-danger btn-xs btn-delete" data-toggle="modal" data-target="#user-delete-modal" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}"><i class="fas fa-trash"></i></button>
                                                    </p>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <p data-placement="top" data-toggle="tooltip" title="{{ __( 'users.new_title' ) }}" class="m-0" >
                            <button class="btn btn-primary m-2 btn-new" data-toggle="modal" data-target="#user-modal">
                                {{ __('users.add') }}
                            </button>
                        </p>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>

        @include('user.modals.delete_user')
        @include('user.modals.add_edit_user')
        @include('user.scripts')
    @endsection
@endauth
