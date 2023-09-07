<div class="form-group">
    <label for="package_name" class="form-control-label">Package Name * </label>
    {!! Form::text('package_name', null, ['id' => 'package_name','class' => 'form-control','required',
    ]) !!}
    @if ($errors->has('package_name')) <span class="text-danger alert">{{ $errors->first('package_name') }}</span>
    @endif
</div>

<div class="form-group">
    <label for="price" class="form-control-label">Product Upload and Sale Cost Price * (Per Product) </label>
    {!! Form::number('price', null, ['id' => 'price','class' => 'form-control','required','step'=>'any'
    ]) !!}
    @if ($errors->has('price')) <span class="text-danger alert">{{ $errors->first('price') }}</span> @endif
</div>
<div class="form-group">
    <label for="duration" class="form-control-label">Package Duration (days) * </label>
    {!! Form::number('duration', null, ['id' => 'duration','class' => 'form-control','required','step'=>'any'
    ]) !!}
    @if ($errors->has('duration')) <span class="text-danger alert">{{ $errors->first('duration') }}</span> @endif
</div>
<div class="form-group">
    <label for="shop" class="form-control-label">Shop(<small>Manage</small>) * </label>
    {!! Form::number('shop', null, ['id' => 'shop','class' => 'form-control','required','step'=>'any','min'=>1
    ]) !!}
    @if ($errors->has('shop')) <span class="text-danger alert">{{ $errors->first('shop') }}</span>
    @endif
</div>
<div class="form-group">
    <label for="employee_manage" class="form-control-label">Employee (<small>Manage</small>) * </label>
    {!! Form::number('employee_manage', null, ['id' => 'employee_manage','class' => 'form-control','required','step'=>'any'
    ]) !!}
    @if ($errors->has('employee_manage')) <span class="text-danger alert">{{ $errors->first('employee_manage') }}</span>
    @endif
</div>
@if (Request::segment(3)=='create')
<div class="form-group">
    <label for="features" class="form-control-label">Package Features * </label>
    {!! Form::select('features[]',[], null, ['id' => 'features','class' => 'form-control
    select2','required','multiple'=>true
    ]) !!}
    @if ($errors->has('product')) <span class="text-danger alert">{{ $errors->first('product') }}</span> @endif
</div>
@else
<div class="form-group">
    <label for="features" class="form-control-label">Package Features * </label>
    {!! Form::select('features[]',$features,json_decode($package['features'],true), ['id' => 'features','class' =>
    'form-control select2','required','multiple'=>true
    ]) !!}
    @if ($errors->has('product')) <span class="text-danger alert">{{ $errors->first('product') }}</span> @endif
</div>
@endif
<div class="form-group">
    <label for="description" class="form-control-label">Description </label>
    {!! Form::textarea('description', null, [
    'id' => 'description',
    'class' => 'form-control',
    'rows' => 3,
    ]) !!}
    @if ($errors->has('description'))
    <span class="text-danger alert">{{ $errors->first('description') }}</span>
    @endif
</div>
<div class="form-group">
    <label for="status" class="form-control-label">Status * </label>
    {!! Form::select('status',[1=>'Active',0=>'In-Active'],null, ['id' => 'status','class' => 'form-control
    select2','required'
    ]) !!}
    @if ($errors->has('status')) <span class="text-danger alert">{{ $errors->first('status') }}</span> @endif
</div>
</div>