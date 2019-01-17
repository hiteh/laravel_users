<!-- Add/Edit user modal -->
<div class="modal fade" id="user-modal" tabindex="-1" role="dialog" aria-labelledby="user-modal-title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="user-modal-title">{{ __( 'users.new_title' ) }}</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" id="user-form" action="">
            @csrf
            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('users.name') }}</label>

                <div class="col-md-6">
                    <input id="name" type="text" class="form-control" name="name" value="" required autofocus>
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('users.email') }}</label>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control" name="email" value="" required>
                </div>
            </div>

            <div id="role-form-component" class="form-group row">
                <label for="role" class="col-md-4 col-form-label text-md-right">{{ __('users.role') }}</label>
                <div class="col-md-6">
                    <select required name="role" id="role" class="form-control">
                        @foreach( $roles as $name )
                            <option value="{{ $name }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('users.password') }}</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control" name="password" required>
                </div>
            </div>

            <div class="form-group row">
                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('users.confirm_password') }}</label>

                <div class="col-md-6">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button id="dismis" type="button" class="btn btn-secondary" data-dismiss="modal">{{ __( 'users.back' ) }}</button>
        <button id="save" type="button" type="submit" class="btn btn-primary" onclick="">{{ __( 'users.save' ) }}</button>
      </div>
    </div>
  </div>
</div>
