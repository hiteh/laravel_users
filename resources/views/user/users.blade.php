@extends('layouts.app')
@auth
    @section('content')
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-4 d-flex align-items-center">
                                    {{ __('users.title') }}
                                </div>
                                <div class="col-8">
                                    <div class="d-flex justify-content-end">
                                        <p data-placement="top" data-toggle="tooltip" title="{{ __( 'users.new_title' ) }}" class="m-0" >
                                            <button class="btn btn-primary m-2 btn-new" data-toggle="modal" data-target="#user-modal">
                                            {{ __('users.add') }}
                                            </button>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
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
                                        @foreach ( $data as $user )
                                        <tr>
                                            <td>{{ $user['id'] }}</td>
                                            <td>{{ $user['name'] }}</td>
                                            <td>{{ $user['email'] }}</td>
                                            <td>@foreach( $user['roles'] as $role ){{ $role['name'] }}&#32;@endforeach</td>
                                            <td>
                                                <p data-placement="top" data-toggle="tooltip" title="{{ __( 'users.edit_title' ) }}" class="m-0" >
                                                    <button class="btn btn-primary btn-xs btn-edit" data-toggle="modal" data-target="#user-modal" data-user-id="{{ $user['id'] }}" data-user-name="{{ $user['name'] }}" data-user-email="{{ $user['email'] }}" data-user-role="@foreach( $user['roles'] as $role ){{ $role['name'] }}&#32;@endforeach"><i class="fas fa-edit"></i></button>
                                                </p>
     
                                            </td>
                                            <td>
                                                <p data-placement="top" data-toggle="tooltip" title="{{ __( 'users.delete_title' ) }}" class="m-0" >
                                                    <button class="btn btn-danger btn-xs btn-delete" data-toggle="modal" data-target="#user-delete-modal" data-user-id="{{ $user['id'] }}" data-user-name="{{ $user['name']}}"><i class="fas fa-trash"></i></button>
                                                </p>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center m-3">
                    {{ $data->appends( Request::except( 'page') )->links() }}
                    </div>
                </div>
            </div>
        </div>

        @include('user.modals.delete_user')
        @include('user.modals.add_edit_user')
        @include('user.scripts')
    @endsection
@endauth
