<!-- Delete Modal -->
<div class="modal fade" id="user-delete-modal" tabindex="-1" role="dialog" aria-labelledby="user-delete-modal-title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="user-delete-modal-title">{{ __( 'users.delete_title' ) }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="user-delete-form" action="" method="POST" style="display: none;">
            @csrf
            {!! method_field('delete') !!}
             <input type="hidden" id="delete-id" name="delete-id" value="">
        </form>
        <p class="lead">
            {{ __( 'users.delete_msg' ) }}
        </p>
        <p id="delete-user-name"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __( 'users.back' ) }}</button>
        <button type="button" type="submit" class="btn btn-danger" onclick="event.preventDefault();
            document.getElementById('user-delete-form').submit();">{{ __( 'users.delete' ) }}</button>
      </div>
    </div>
  </div>
</div>
