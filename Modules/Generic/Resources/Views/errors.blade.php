@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul style="margin: 0 0 10px 0;list-style: inside;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@elseif(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

