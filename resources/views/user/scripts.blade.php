@section('scripts')
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        // Delete user
        (function($) {
            // DOM caching
            const modal = $('#user-delete-modal')
            const deleteButtons = $('.btn-delete')
            // Form data
            const data = {
                id: '',
                name: '',
                route: ''
            }
            // Updates form data
            const dataUpdate = function ( data ) {
                
                return function ( event ) {
                    data.id = $(this).data('user-id')
                    data.name = $(this).data('user-name')
                    data.route = '{{ route('users.destroy', 'id') }}'.replace('id', data.id)
                }
             }
             // Updates form action attribute and user's displayed name 
             const modalUpdate = function ( data ) {

                return function ( event ) {
                    $(this).find("#user-delete-form").attr('action', data.route )
                    $(this).find("#delete-user-name").text( data.name )
                } 
             }
             // Events handling
            deleteButtons.on('click.delete', dataUpdate( data ) )
            modal.on('show.bs.modal', modalUpdate( data ) )

        })(jQuery)
    })
</script>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        // Edit od add user
        (function($) {
            // DOM caching
            const modal = $('#user-modal')
            const editButton = $('.btn-edit')
            const newButton = $('.btn-new')
            const emptyRolesSelect = $('<div id="roles" style="display:none"></div>')
            const clonedRolesSelect = modal.find('#roles').clone()
            const saveButton = $('#save')
            // Form data
            const form = {
                title: '',
                action: '',
                method: '',
                user: {
                    id: '',
                    name: '',
                    email: '',
                    role: '',
                }
            }
            // Updates form data
            const dataUpdate = function ( form, title, action, method ) {
                
                return function ( event ) {
                    form.user.id = $(this).data('user-id') || ''
                    form.user.name = $(this).data('user-name') || ''
                    form.user.email = $(this).data('user-email') || ''
                    form.user.role = $(this).data('user-role') || ''
                    form.title = title
                    form.action = action.replace('id', form.user.id)
                    form.method = method  || ''
                }
             }
             // Updates user's data and form elements
             const modalUpdate = function ( form, methodFieldUpdater, rolesSelectUpdater ) {

                return function ( event ) {
                    $(this).find( '#user-modal-title' ).text( form.title )
                    $(this).find('#user-form').attr( 'action', form.action )
                    $(this).find('#name').val( form.user.name )
                    $(this).find('#email').val( form.user.email )
                    methodFieldUpdater( $(this).find("form"), form.method )
                    rolesSelectUpdater( $(this).find("form"), form.user.role )
                }
             }
             // Updates method field
             const updateMethodField = function ( form, method ) {                                  
                    
                    const methodField = form.find('input[name=_method]')

                    if ( method && methodField.length === 0 ) {
                        form.append( $( '<input type="hidden" name="_method" value="'+ method +'">' ) )
                    } else if ( !method && methodField.length !== 0 ) {
                        methodField.remove()
                    }
             }
             // Updates roles select
             const updateSelectOptions = function ( emptyRolesOptions, rolesOptions ) {
                
                return function ( form, role ) {

                    const roles = form.find('#roles')
                    // Remove roles input if user is root
                    if (role === 'root') {
                        roles.replaceWith( emptyRolesOptions )
                    } else if ( roles.attr( 'display','none' ) ) {
                        roles.replaceWith( rolesOptions )
                    }
                    // Set users role as selected
                    form.find('#role').children().each(function ( index, child ) {
                        let childSelected = $(child)
                        if ( childSelected.val() === role ) {
                            childSelected.attr('selected','selected')
                        } else {
                            childSelected.removeAttr('selected')
                        }
                     })    
                }

             }
             // Removes empty inputs and submits form
             const removeEmptyInputFields = function ( form ) {

                return function ( event ) {
                    const patchInput = form.find('input[name=_method]')
                    event.preventDefault()
                    // Remove empty inputs ( for 'sometimes required 'fields and validation see: https://laravel.com/docs/5.7/validation#conditionally-adding-rules )
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
                }   
             }

             // Event handling
            modal.on('show.bs.modal', modalUpdate( form, updateMethodField, updateSelectOptions( emptyRolesSelect, clonedRolesSelect ) ))

            editButton.on( 'click.edit', dataUpdate( form, '{{ __( 'users.edit_title' ) }}', '{{ route('users.update', 'id') }}', 'patch' ))

            newButton.on('click.new', dataUpdate( form, '{{ __( 'users.new_title' ) }}', '{{ route('users.store') }}' ))

            saveButton.on('click', removeEmptyInputFields( modal.find("form") ))

        })(jQuery)

    })
</script>

@endsection