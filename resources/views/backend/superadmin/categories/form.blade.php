<div class="col-md-12">

    <div class="form-group">
        <label for="category_name" class="form-control-label">Category Name * </label>
        {!! Form::text('category_name', null, ['id' => 'category_name', 'class' => 'form-control', 'required']) !!}
        @if ($errors->has('category_name'))
            <span class="text-danger alert">{{ $errors->first('category_name') }}</span>
        @endif
    </div>
    
   
</div>
