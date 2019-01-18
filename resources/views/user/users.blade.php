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
                                <table id="users-list" class="table table-striped">
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
                                        <tr class="user-row" data-user="{{ $user->id }}" >
                                            <td class="user-id">{{ $user->id }}</td>
                                            <td class="user-name">{{ $user->name }}</td>
                                            <td class="user-email">{{ $user->email }}</td>
                                            <td class="user-role" data-role="{{ $user->roles()->first()->name }}">{{ $user->roles()->first()->name }}</td>
                                            <td>
                                                <p data-placement="top" data-toggle="tooltip" title="{{ __( 'users.edit_title' ) }}" class="m-0" >
                                                    <button class="btn btn-primary btn-xs btn-edit" data-toggle="modal" data-target="#user-modal"><i class="fas fa-edit"></i></button>
                                                </p>
     
                                            </td>
                                            <td>
                                                <p data-placement="top" data-toggle="tooltip" title="{{ __( 'users.delete_title' ) }}" class="m-0" >
                                                    <button class="btn btn-danger btn-xs btn-delete" data-toggle="modal" data-target="#user-delete-modal"><i class="fas fa-trash"></i></button>
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
                        <button class="btn btn-primary m-2 btn-new" data-toggle="modal" data-target="#user-modal">      {{ __('users.add') }}
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

@section('scripts')
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {

        (function($) {

            // DOM caching
            const modal = $('#user-delete-modal')
            const form = modal.find('#user-delete-form')
            const userName = $('#delete-user-name')

                modal.on('show.bs.modal', function (event) {
                    // Variables
                    const button = $(event.relatedTarget)
                    const userRow = button.parent().parent().parent() 
                    const userId = userRow.data('user')
                    const route = '{{ route('users.destroy', 'id') }}'.replace('id', userId)
                    

                    // Update action with route that contains current user id
                    form.attr('action', route)
                    // Update user name with current user data
                    userName.text(userRow.find('.user-name').text())
                })

        })(jQuery)
    })
</script>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {

        (function($) {
            // DOM caching
            const modal = $('#user-modal')
            const modalTitle = modal.find('#user-modal-title')
            const form = modal.find('#user-form')
            const formName = form.find('#name')
            const formEmail = form.find('#email')
            const emptyRoleComponent = $('<div id="role-form-component" style="display:none"></div>')
            const clonedRoleComponent = form.find('#role-form-component').clone()
            const saveButton = $('#save')

            modal.on('show.bs.modal', function (event) {
                // Variables
                const button = $(event.relatedTarget)
                const userRow = button.parent().parent().parent() 
                const userId = userRow.data('user')
                const userRole = userRow.find('.user-role').data('role')
                const patchInput = form.find('input[name=_method]')
                let route = ''
                    
                if ( button.hasClass('btn-edit') ) {

                    route = '{{ route('users.update', 'id') }}'.replace('id', userId)

                    // set modal title
                    modalTitle.text('{{ __( 'users.edit_title' ) }}')

                    // Remove role select when user has root role
                    if (userRole === 'root') {
                        form.find('#role-form-component').replaceWith( emptyRoleComponent )
                    } else if ( form.find('#role-form-component').attr( 'display','none' ) ) {
                        form.find('#role-form-component').replaceWith( clonedRoleComponent )
                    }

                    // Mark current user role as selected option
                    form.find('#role').children().each(function ( index, child ) {
                        let childSelected = $(child)
                        if ( childSelected.val() === userRow.find('.user-role').data('role') ) {
                            childSelected.attr('selected','selected')
                        } else {
                            childSelected.removeAttr('selected')
                        }
                     })

                    // Update action with route that contains current user id
                    form.attr('action', route)

                    // Update user name, email with current user data
                    formName.val(userRow.find('.user-name').text())
                    formEmail.val(userRow.find('.user-email').text())

                    // Add patch method field
                    if ( patchInput.length === 0 ) {
                        form.append('{!! method_field('patch') !!}')
                    }
                    
                } else if ( button.hasClass('btn-new') ) {

                    route = '{{ route('users.store') }}'
                    
                    // change modal title
                    modalTitle.text('{{ __( 'users.new_title' ) }}')

                    // Set route
                    form.attr('action', route)

                    // Reset form fields
                    formName.val('')
                    formEmail.val('')

                    // Add select role component if previously removed
                    if ( form.find('#role-form-component').attr('display','none') ) {
                        form.find('#role-form-component').replaceWith( clonedRoleComponent )
                    }

                    // Remove selected attribute
                    form.find('#role').children().each(function ( index, child ) {
                        const childSelected = $(child)
                        const attr = childSelected.attr('selected')

                        if ( typeof attr !== typeof undefined && attr !== false ) {
                            childSelected.removeAttr('selected')
                        }
                    })
                    
                    // Remove patch input
                    if ( patchInput.length !== 0 ) {
                        patchInput.remove()
                    }
                }
            })

            saveButton.on('click', function( event ) {

                event.preventDefault()

                const patchInput = form.find('input[name=_method]')
                // Remove empty inputs ( for sometimes required fields and validation see: https://laravel.com/docs/5.7/validation#conditionally-adding-rules )
                 if ( patchInput.length !== 0 ) {

                    form.find('input').each( function( index, input ) {
                        if ( '' === $(input).val() || 'string' !== typeof $(input).val() ) {
                            // Remove if input value is empty string
                            $(input).remove()
                        }
                    } )

                 }
                // Submit form after empty inputs are reoved (validation checks against a field only if that field is present in the input array)
                form.submit()
            })

        })(jQuery)

    })
</script>

@endsection

@endauth
@endsection