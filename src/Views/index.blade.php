@extends('layouts.app')

@section('content')
<!-- move this to an include or extended page -->
    <!-- Scripts -->
<script type="text/javascript">
    var baseUrl = "{{ url('/') }}";
</script>

<script>
Dropzone.options.addImages = {
    maxFilesize: 2,
    acceptedFiles: 'image/*',
    success: function (file, response){
        if(file.status == 'success') {
            handleDropzoneFileUpload.handleSuccess(response);
        } else {
            handleDropzoneFileUpload.handleError(response);
        }
    }
};

var handleDropzoneFileUpload = {
    handleError: function(response){
        console.log(response);
    },
    handleSuccess: function(response){
        var imageList   =   $('#gallery-images ul');
        var imageSrc    =   baseUrl + '/gallery/' + response.gallery_id + '/thumbs/' + response.file_name;
        $(imageList).append('<li><a href="' + imageSrc + '"><img src="' + imageSrc + '"></a></li>');
        console.log(response);
     }
}
$(document).ready(function(){
    console.log('layouts.app Document Ready');
});
</script>

<style type="text/css">
.row {
	margin: 0 auto!important;
	max-width: 100%;
}
</style>

	<div class="row gallery-row">

		<div class="col-md-12 gallery-md-12">
			<h2>Galleries</h2>
		</div>

	</div>

<div class="row gallery-row">

	<div class="col-md-8 gallery-md-8">
		
		@if($galleries->count() > 0)
		
			<table class="table table-striped table-bordered table-responsive gallery-table">

				<thead>
					<tr class="info gallery-info">
						<th style="width:80%;">Name of Gallery</th>
						<th style="width:10%;"></th>
						<th style="width:10%;"></th>
					</tr>
				</thead>

				<tbody>
					
					@foreach($galleries as $gallery)

					<tr>
						<td>{{ $gallery->name }} 
							<span class="pull-right gallery-pull-right">
								{{ $gallery->images()->count() }}
							</span>
						</td>
						<td>
							<a href="{{ url('/galleries/' . $gallery->id) }}">View</a>
						</td>
						<td>
							<a href="{{ url('/galleries/delete/' . $gallery->id) }}">Delete</a>
						</td>
					</tr>

					@endforeach
				</tbody>
			</table>

		@endif
	</div>

	<div class="col-md-4 gallery-md-4">
		Add Gallery:
		@if (count($errors) > 0)
			<div class="alert alert-danger gallery-alert-danger">
				<ul>
					@foreach($errors->all() as $error)
					
					<li>{{ $error }}</li>
					
					@endforeach
				</ul>

			</div>
		@endif

		@if (Session::has('flash_message'))

			<div class="alert alert-success gallery-alert-success">{{ Session::get('flash_message') }}</div>

		@endif
		<form class="form gallery-form" method="post" action="/galleries/save">
			
			{{ csrf_field() }}
			
			<input type="text" name="gallery_name" 
				id="gallery_name" placeholder="Gallery Name" 
				class="form-control" value="{{ old('gallery_name') }}">


			<button class="btn btn-primary gallery-form-btn">Save</button>
			
		</form>
	</div>

</div>



@endsection