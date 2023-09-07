<div class="col-md-12">

    <div class="form-group">
        <label for="category_name" class="form-control-label">Select Category * </label>
        {!! Form::select('category_id',Helper::categoryPluckValue(), null, ['id' => 'category_id', 'class' =>
        'form-control select2', 'required']) !!}
        @if ($errors->has('category_id'))
        <span class="text-danger alert">{{ $errors->first('category_id') }}</span>
        @endif
    </div>
    @if (Request::segment(3)=='create')
    <div class="form-group">
        <label for="sub_category_name" class="form-control-label">Type Sub Category Name and Press Enter * </label>
        {!! Form::select('sub_category_name[]',[], null, ['id' => 'sub_category_name', 'class' => 'form-control
        select2', 'required','multiple'=>true]) !!}
        @if ($errors->has('sub_category_name'))
        <span class="text-danger alert">{{ $errors->first('sub_category_name') }}</span>
        @endif
    </div>
    @else
    <div class="form-group">
        <label for="sub_category_name" class="form-control-label">Update Sub Category Name * </label>
        {!! Form::text('sub_category_name', null, ['id' => 'sub_category_name', 'class' => 'form-control',
        'required']) !!}
        @if ($errors->has('sub_category_name'))
        <span class="text-danger alert">{{ $errors->first('sub_category_name') }}</span>
        @endif
    </div>
    @endif
    <div class="form-group">
        <label for="status" class="form-control-label">Status * </label>
        {!! Form::select('status',[1=>'Active',0=>'In-Active'],null, ['id' => 'status','class' => 'form-control
        select2','required'
        ]) !!}
        @if ($errors->has('status')) <span class="text-danger alert">{{ $errors->first('status') }}</span> @endif
    </div>

</div>