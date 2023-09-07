<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="brand_name" class="form-control-label">Brand Name [Ex: Bata] * </label>
            {!! Form::text('brand_name', null, ['id' => 'brand_name', 'class' => 'form-control', 'required']) !!}
            @if ($errors->has('brand_name'))
            <span class="text-danger alert">{{ $errors->first('brand_name') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="discount" class="form-control-label">Brand Product Discount % [Ex: 0] * </label>
            {!! Form::select('discount',Helper::discoutPluckValue(), null, ['id' => 'discount', 'class' => 'form-control
            select2', 'required','min'=>1]) !!}
            @if ($errors->has('discount'))
            <span class="text-danger alert">{{ $errors->first('discount') }}</span>
            @endif
        </div>
    </div>


    <div class="col-md-12">
        <div class="form-group">
            <label for="status" class="form-control-label">Status * </label>
            {!! Form::select('status',[1=>'Active',0=>'In-Active'],null, ['id' => 'status','class' => 'form-control
            select2','required'
            ]) !!}
            @if ($errors->has('status')) <span class="text-danger alert">{{ $errors->first('status') }}</span> @endif
        </div>
    </div>
</div>