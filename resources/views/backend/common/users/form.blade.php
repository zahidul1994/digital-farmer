<div class="row">

    <div class="form-group col-md-6">
        <label for="name" class="form-control-label">Staff Name * </label>
        {!! Form::text('name', null, ['id' => 'name','class' => 'form-control','required',
        ]) !!}
        @if ($errors->has('name')) <span class="text-danger alert">{{ $errors->first('name') }}</span> @endif
    </div>
    
    
    <div class="form-group col-md-6">
        <label for="gender" class="form-control-label">Gender * </label>
        {!! Form::select('gender',['Male'=>'Male','Female'=>'Female','Other'=>'Other'], null, ['id' => 'gender','class'
        => 'form-control select2','required',
        ]) !!}
        @if ($errors->has('gender')) <span class="text-danger alert">{{ $errors->first('gender') }}</span> @endif
    </div>
    

    <div class="form-group col-md-6">
        <label for="phone" class="form-control-label">Phone * </label>
        {!! Form::tel('phone',null, ['id' => 'phone','class' => 'form-control','required'
        ]) !!}
        @if ($errors->has('phone')) <span class="text-danger alert">{{ $errors->first('phone') }}</span> @endif
    </div>
   
    <div class="form-group col-md-6">
        <label for="email" class="form-control-label">Email * </label>
        {!! Form::email('email', null, ['id' => 'email','class' => 'form-control','required',
        ]) !!}
        @if ($errors->has('email')) <span class="text-danger alert">{{ $errors->first('email') }}</span> @endif
    </div>
    <div class="form-group col-md-6">
        <label for="password" class="form-control-label">Password * </label>
        {!! Form::text('password','', ['id' => 'password','class' => 'form-control'
        ]) !!}
        @if ($errors->has('password')) <span class="text-danger alert">{{ $errors->first('password') }}</span> @endif
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="status" class="form-control-label">Status * </label>
            {!! Form::select('status',[1=>'Active',0=>'In-Active'],null, ['id' => 'status','class' => 'form-control
            select2','required'
            ]) !!}
            @if ($errors->has('status')) <span class="text-danger alert">{{ $errors->first('status') }}</span> @endif
        </div>
    </div>
  
    @if (Request::segment(3)=='create')
    <div class="form-group col-md-6">
        <label for="roles" class="form-control-label">Select Role * </label>
        {!! Form::select('roles',$roles,null, ['id' => 'roles','class' => 'form-control select2','placeholder'=>'Select
        Role','required'
        ]) !!}
        @if ($errors->has('roles')) <span class="text-danger alert">{{ $errors->first('roles') }}</span> @endif
    </div>
    @else
    <div class="form-group col-md-6">
       
        <label for="roles" class="form-control-label">Update Role * </label>
        {!! Form::select('roles',$roles, $userRole, ['id' => 'roles','class' => 'form-control
        select2','placeholder'=>'Select Role','required'
        ]) !!}
        @if ($errors->has('roles')) <span class="text-danger alert">{{ $errors->first('roles') }}</span> @endif
    </div>
    @endif
    <div class="col-md-8">
        <div class="form-group">
            <label for="image" class="form-control-label">Image [300X300] * </label>
            {!! Form::file('image', ['id' => 'image','class' => 'form-control','accept'=>".jpg,.jpeg,.png"
            ]) !!}
            @if ($errors->has('image')) <span class="text-danger alert">{{ $errors->first('image') }}</span> @endif
        </div>
    </div>
    <div class="col-md-4">
    <img id="imageShow" src="{{url('backend/assets/img/uploadphoto.png') }}" alt="your image" width="160px" />
    </div>
    
    
</div>