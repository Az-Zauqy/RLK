{{ Form::label('name', 'Name') }}
{{ Form::text('name', null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Nama Kategori']) }}

{{ Form::label('slug', 'Slug') }}
{{ Form::text('slug', null, ['class' => 'form-control form-control-sm', 'placeholder' => 'slug (optional)']) }}
