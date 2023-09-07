<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="supplier_name" class="form-control-label">Supplier Name  *  </label>
            {!! Form::text('supplier_name', null, ['id' => 'supplier_name', 'class' => 'form-control', 'required']) !!}
            @if ($errors->has('supplier_name'))
            <span class="text-danger alert">{{ $errors->first('supplier_name') }}</span>
            @endif
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="supplier_phone" class="form-control-label">Supplier Phone * </label>
            {!! Form::tel('supplier_phone', null, ['id' => 'supplier_phone', 'class' => 'form-control', 'required']) !!}
            @if ($errors->has('supplier_phone'))
            <span class="text-danger alert">{{ $errors->first('supplier_phone') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="supplier_email" class="form-control-label">Supplier Email </label>
            {!! Form::email('supplier_email', null, ['id' => 'supplier_email', 'class' => 'form-control']) !!}
            @if ($errors->has('supplier_email'))
            <span class="text-danger alert">{{ $errors->first('supplier_email') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="address" class="form-control-label">Supplier Address *</label>
            {!! Form::textarea('address', null, ['id' => 'address', 'class' => 'form-control','required','rows'=>1]) !!}
            @if ($errors->has('address'))
            <span class="text-danger alert">{{ $errors->first('address') }}</span>
            @endif
        </div>
    </div>
    @if (Request::segment(3)=='create')
    <div class="col-md-6">
        <div class="form-group">
            <label for="due" class="form-control-label">Supplier Previous Due </label>
            {!! Form::number('due', null, ['id' => 'due', 'class' => 'form-control','step'=>'any','max'=>99999999]) !!}
            @if ($errors->has('due'))
            <span class="text-danger alert">{{ $errors->first('due') }}</span>
            @endif
        </div>
    </div>
   
    <div class="col-md-6">
        <div class="form-group">
            <label for="paid" class="form-control-label">Supplier Previous Payment </label>
            {!! Form::number('paid', null, ['id' => 'paid', 'class' => 'form-control','step'=>'any','max'=>99999999]) !!}
            @if ($errors->has('paid'))
            <span class="text-danger alert">{{ $errors->first('paid') }}</span>
            @endif
        </div>
    </div>
    @endif
    <div class="col-md-12">
        <div class="form-group">
            <label for="description" class="form-control-label">Description (About Supplier)</label>
            {!! Form::textarea('description', null, ['id' => 'description', 'class' => 'form-control','rows'=>2]) !!}
            @if ($errors->has('description'))
            <span class="text-danger alert">{{ $errors->first('description') }}</span>
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