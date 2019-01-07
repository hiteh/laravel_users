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
        <!-- Edit profile modal-->
        <div class="modal fade" id="profile-modal" tabindex="-1" role="dialog" aria-labelledby="profile-modal-title" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="modal-title" id="profile-modal-title">{{ __( 'profile.edit_profile' ) }}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="POST" id="profile-form" action="{{ route( 'profile.update', $user->id ) }}">
                    @csrf
                    {!!  method_field( 'patch' ) !!}
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __( 'profile.first_name' ) }}</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" required autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __( 'profile.mail' ) }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                        </div>
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                <button id="dismis" type="button" class="btn btn-secondary" data-dismiss="modal">{{ __( 'profile.back' ) }}</button>
                <button id="save" type="button" type="submit" class="btn btn-primary" onclick="event.preventDefault(); document.getElementById('profile-form').submit();">{{ __( 'profile.save' ) }}</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Change password modal -->
        <div class="modal fade" id="password-modal" tabindex="-1" role="dialog" aria-labelledby="password-modal-title" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="modal-title" id="password-modal-title">{{ __( 'profile.edit_password' ) }}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="POST" id="password-form" action="{{ route( 'profile.update', $user->id ) }}">
                    @csrf
                    {!! method_field( 'patch' ) !!}
                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __( 'profile.password' ) }}</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control" name="password" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">{{ __( 'profile.confirm_password' ) }}</label>

                        <div class="col-md-6">
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                        </div>
                    </div>
                </form>
              </div>
              <div class="modal-footer">
                <button id="dismis" type="button" class="btn btn-secondary" data-dismiss="modal">{{ __( 'profile.back' ) }}</button>
                <button id="save" type="button" type="submit" class="btn btn-primary" onclick="event.preventDefault(); document.getElementById('password-form').submit();">{{ __( 'profile.save' ) }}</button>
              </div>
            </div>
          </div>
        </div>
    @endsection
@endauth