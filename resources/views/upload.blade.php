<form action="/" method="post" enctype="multipart/form-data">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">

    Select file to upload:
    {{ Form::file('file') }}
    <input type="submit" value="Upload" name="submit">
</form>

@if (count($errors) > 0)    
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif