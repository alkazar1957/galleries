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

<div class="row gallery-row">
	<div class="col-md-12 gallery-md12">

	<h2>{{ $gallery->name }}</h2>

	</div>
</div>


<div class="row gallery-row">

	<div class="col-md-12 gallery-md-12">

		<div id="gallery-images">
		<ul>
			@foreach($gallery->images as $image)

			<li>
				<a href="{{ url($image->file_path) }}" data-lightbox="gallery-{{ $gallery->id }}">
					<img src="{{ url('/gallery/'.$gallery->id.'/thumbs/'.$image->file_name) }}">
				</a>
				<br>
				<a href="{{ url('/galleries/image/delete/' . $gallery->id . '/' . $image->id) }}">Delete</a> /
				<a href="{{ url('/galleries/image/edit/' . $gallery->id . '/' . $image->id . '/-90') }}">Rotate Right</a> /
				<a href="{{ url('/galleries/image/edit/' . $gallery->id . '/' . $image->id . '/90') }}">Rotate Left</a>
			</li>

			@endforeach
		</ul>

		</div>

	</div>

</div>

<div class="row gallery-row">
	<div class="col-md-12 gallery-md-12">
		<form action="{{ url('/galleries/image/upload/') }}" class="dropzone  gallery-dropzone" id="addImages">

			{{ csrf_field() }}

			<input type="hidden" name="gallery_id" value="{{ $gallery->id }}">

		</form>
	</div>
</div>

<div class="row gallery-row">
	<div class="col-md-12 gallery-md12">

	<a href="{{ url('/galleries/') }}" class="btn btn-primary gallery-btn-primary">Back</a>

	</div>
</div>


@endsection