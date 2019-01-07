<div id="alert" class="container d-none mt-4">
	<div class="row justify-content-center">
		<div class="col-12 col-md-8">
			@if(\Session::has('info'))
				<div class="alert alert-info" role="alert">
	  				{{ \Session::get('info') }}
				</div>
			@endif

			@if(\Session::has('warning'))
				<div class="alert alert-warning" role="alert">
	  				{{ \Session::get('warning') }}
				</div>
			@endif

			@if(\Session::has('success'))
				<div class="alert alert-success" role="alert">
	  				{{ \Session::get('success') }}
				</div>
			@endif

			@if(\Session::has('primary'))
				<div class="alert alert-primary" role="alert">
	  				{{ \Session::get('primary') }}
				</div>
			@endif

			@if(\Session::has('secondary'))
				<div class="alert alert-secondary" role="alert">
	  				{{ \Session::get('secondary') }}
				</div>
			@endif
			@if ( count( $errors ) )
				@foreach ( $errors->all() as $error )
					<div class="alert alert-danger" role="alert">
		  				{{ $error }}
					</div>
				@endforeach
			@endif
		</div>
	</div>
</div>

@if( \Session::has('info') ||
 	 \Session::has('warning') ||
 	 \Session::has('success') || 
 	 \Session::has('primary') || 
 	 \Session::has('secondary') ||
 	  count( $errors ) )
	@section('scripts')
		<script type="text/javascript">
			
			document.addEventListener("DOMContentLoaded", function() {
				
			const alert = $('#alert')
				
			  return (function($) {
			  	
			  	if( 0 !== alert.find('.alert').length ) {
			  		alert.removeClass('d-none') 
			  	}

			   })(jQuery)
			})

		</script>
	@endsection
@endif