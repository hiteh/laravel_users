<!-- Change user's password modal -->
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
        <form method="POST" id="password-form" action="{{ route( 'users.update', $data['id'] ) }}">
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