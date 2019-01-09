@extends('layouts.app')
@auth
    @section('content')
        <!-- User's profile -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('profile.profile') }}</div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item d-flex p-0">
                                    <div class="list-group-item-label p-2">{{ __( 'profile.first_name' ) }}</div>
                                    <div class="list-group-item-value p-2">{{ $user->name }}</div>
                                </li>
                                <li class="list-group-item d-flex p-0">
                                    <div class="list-group-item-label p-2">{{ __( 'profile.mail' ) }}</div>
                                    <div class="list-group-item-value p-2">{{ $user->email }}</div>
                                </li>
                                <li class="list-group-item d-flex p-0">
                                    <div class="list-group-item-label p-2">{{ __( 'profile.role' ) }}</div>
                                    <div class="list-group-item-value p-2">{{ $user->roles()->first()->name }}</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <p data-placement="top" data-toggle="tooltip" title="{{ __( 'profile.edit_profile' ) }}" class="m-0" >
                            <button class="btn btn-primary m-2 btn-new" data-toggle="modal" data-target="#profile-modal">      {{ __( 'profile.edit_profile' ) }}
                            </button>
                        </p>
                        <p data-placement="top" data-toggle="tooltip" title="{{ __( 'profile.edit_password' ) }}" class="m-0" >
                            <button class="btn btn-primary m-2 btn-new" data-toggle="modal" data-target="#password-modal">      {{ __( 'profile.edit_password' ) }}
                            </button>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @include('user.modals.edit_profile')
        @include('user.modals.change_password')
        
    @endsection
@endauth