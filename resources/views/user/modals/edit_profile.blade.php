<!-- Edit user's profile modal-->
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
        <form method="POST" id="profile-form" action="{{ route( 'profile.update' ) }}">
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