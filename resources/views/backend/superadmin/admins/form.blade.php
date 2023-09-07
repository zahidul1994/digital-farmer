


    <div class="form-group">
        <label for="name" class="form-control-label">Full Name * </label>
        {!! Form::text('name', null, ['id' => 'name','class' => 'form-control','required',
        ]) !!}
        @if ($errors->has('name')) <span class="text-danger alert">{{ $errors->first('name') }}</span> @endif
    </div>

 
    <div class="form-group">
        <label for="phone" class="form-control-label">Phone * </label>
        {!! Form::tel('phone',null, ['id' => 'phone','class' => 'form-control','required'
        ]) !!}
        @if ($errors->has('phone')) <span class="text-danger alert">{{ $errors->first('phone') }}</span> @endif
    </div>
    <div class="input-group">
        <span class="input-group-btn">
            <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                <i class="fa fa-picture-o"></i> Image (Profile)  [300*300]
            </a>
        </span>
        {!! Form::text('image', null, ['id' => 'thumbnail', 'class' => 'form-control', 'readonly','required','style'=>'height:40px']) !!}
        @if ($errors->has('image'))
        <span class="text-danger alert">{{ $errors->first('image') }}</span>
        @endif
        <img id="holder" style="margin-top:15px;max-height:100px;">
    </div>
    <div class="form-group">
        <label for="email" class="form-control-label">Email * </label>
        {!! Form::email('email', null, ['id' => 'email','class' => 'form-control','required',
        ]) !!}
        @if ($errors->has('email')) <span class="text-danger alert">{{ $errors->first('email') }}</span> @endif
    </div>
    @if (Request::segment(3)=='create')
    <div class="form-group">
        <label for="password" class="form-control-label">Password * </label>
        {!! Form::password('password', ['id' => 'password','class' => 'form-control','required'
        ]) !!}
        @if ($errors->has('password')) <span class="text-danger alert">{{ $errors->first('password') }}</span> @endif
    </div>
@else
    <div class="form-group">
        <label for="password" class="form-control-label">Password </label>
        {!! Form::password('password', ['id' => 'password','class' => 'form-control'
        ]) !!}
        @if ($errors->has('password')) <span class="text-danger alert">{{ $errors->first('password') }}</span> @endif
    </div>
@endif
    
    <div class="form-group">
        <label for="gender" class="form-control-label">Gender * </label>
        {!! Form::select('gender',['Male'=>'Male','Female'=>'Female','Other'=>'Other'], null, ['id' => 'gender','class'
        => 'form-control select2','required',
        ]) !!}
        @if ($errors->has('gender')) <span class="text-danger alert">{{ $errors->first('gender') }}</span> @endif
    </div>  
    @if (Request::segment(3)=='create')
    <div class="form-group">
        <label for="roles" class="form-control-label">Select Role * </label>
        {!! Form::select('roles',$roles,null, ['id' => 'roles','class' => 'form-control select2','placeholder'=>'Select
        Role','required'
        ]) !!}
        @if ($errors->has('roles')) <span class="text-danger alert">{{ $errors->first('roles') }}</span> @endif
    </div>
    @else
    <div class="form-group">
        <label for="roles" class="form-control-label">Update Role * </label>
        {!! Form::select('roles',$roles, $userRole, ['id' => 'roles','class' => 'form-control
        select2','placeholder'=>'Select Role','required'
        ]) !!}
        @if ($errors->has('roles')) <span class="text-danger alert">{{ $errors->first('roles') }}</span> @endif
    </div>
    @endif
<div class="form-group">
        <label for="status" class="form-control-label">Status * </label>
        {!! Form::select('status',[1=>'Active',0=>'In-Active'],null, ['id' => 'status','class' => 'form-control select2','required'
        ]) !!}
        @if ($errors->has('status')) <span class="text-danger alert">{{ $errors->first('status') }}</span> @endif
    </div>
</div>