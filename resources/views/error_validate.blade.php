@if ($errors->any())
<div class="alert alert-dismissible alert-danger fade show">
    <div class="alert-icon"><i class="far fa-star"></i></div>
    <div class="alert-content">
        <ul>
            @foreach ($errors->all() as $error)

            <li>{{ $error }}</li>

            @endforeach
        </ul>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

@endif
