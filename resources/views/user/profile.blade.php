@extends('layouts.app')
@auth
    @section('content')
        <!-- User's profile -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                        <div class="row">
                            <div class="col-4">{{ __('profile.profile') }}</div>
                            <div class="col-8">
                                <div class="d-flex justify-content-center justify-content-sm-end flex-wrap">
                                    <p data-placement="top" data-toggle="tooltip" title="{{ __( 'profile.edit_profile' ) }}" class="m-0" >
                                        <button class="btn btn-primary m-2 btn-new" data-toggle="modal" data-target="#profile-modal">      {{ __( 'profile.edit_profile' ) }}
                                        </button>
                                    </p>
                                    <p data-placement="top" data-toggle="tooltip" title="{{ __( 'profile.edit_password' ) }}" class="m-0" >
                                        <button class="btn btn-primary m-2 btn-new" data-toggle="modal" data-target="#password-modal">      {{ __( 'profile.edit_password' ) }}
                                        </button>
                                    </p>
                                    <p data-placement="top" data-toggle="tooltip" title="{{ __( 'profile.edit_avatar' ) }}" class="m-0" >
                                        <button class="btn btn-primary m-2 btn-new" data-toggle="modal" data-target="#avatar-modal">      {{ __( 'profile.edit_avatar' ) }}
                                        </button>
                                    </p>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="card-body">
                            <div class="row flex-column-reverse flex-sm-row">
                                <div class="col-12 col-sm-9">
                                    <table class="table table-hover">
                                      <thead>
                                      </thead>
                                      <tbody>
                                        <tr>
                                          <th scope="row">{{ __( 'profile.first_name' ) }}</th>
                                          <td>{{ $data['name'] }}</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">{{ __( 'profile.mail' ) }}</th>
                                          <td>{{ $data['email'] }}</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">{{ __( 'profile.role' ) }}</th>
                                          <td colspan="2">@foreach( $data['roles'] as $role ){{ $role['name'] }}&#32;@endforeach</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                </div>
                                <div class="col-12 col-sm-3 d-flex justify-content-center align-items-center">
                                    <div class="m-1">
                                        @if ( isset( $data['avatar'] ) )
                                            <img src="{{ asset( 'storage/'. $data['avatar'] ) }}" class="img-thumbnail p-2"></img>
                                        @else
                                            <i class="fas fa-user fa-5x"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('user.modals.edit_profile')
        @include('user.modals.change_password')
        @include('user.modals.add_edit_avatar')
        
    @endsection
@endauth