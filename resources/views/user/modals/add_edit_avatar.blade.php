    <!-- Files Modal -->
    <div class="modal fade" id="avatar-modal" tabindex="-1" role="dialog" aria-labelledby="avatar-modal-title" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="avatar-modal-title">{{ __( 'profile.edit_avatar' ) }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="avatar-form" action="{{ route( 'profile.update', $user->id ) }}" method="POST" enctype="multipart/form-data">
                @csrf
                {!!  method_field( 'patch' ) !!}
                <div class="form-group row">
                    <label for="avatar" class="col-md-4 col-form-label text-md-right">{{ __('profile.users_avatar') }}</label>
                    <div class="col-md-6">
                      <input id="avatar" type="file" name="avatar" required />
                    </div>
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __( 'profile.back' ) }}</button>
            <button type="button" type="submit" class="btn btn-primary" onclick="event.preventDefault();
                document.getElementById('avatar-form').submit();">{{ __( 'profile.save' ) }}</button>
          </div>
        </div>
      </div>
    </div>