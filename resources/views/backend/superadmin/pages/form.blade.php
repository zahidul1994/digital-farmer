<div class="col-md-12">
    <div class="form-group">
      {!!Form::label('language','Select Language * ')!!}
    {!!Form::select('language',Helper::Language(),null, array('id'=>'language','required','class'=>'form-control select2'))!!}
    
    </div>
    <div class="form-group">
        <label for="page_name" class="form-control-label">Page Name * </label>
        {!! Form::text('page_name', null, ['id' => 'page_name', 'class' => 'form-control', 'required']) !!}
        @if ($errors->has('page_name'))
            <span class="text-danger alert">{{ $errors->first('page_name') }}</span>
        @endif
    </div>
    <div class="form-group">
        <label for="slug" class="form-control-label">Slug * </label>
        {!! Form::text('slug', null, ['id' => 'slug', 'class' => 'form-control', 'required']) !!}
        @if ($errors->has('slug'))
            <span class="text-danger alert">{{ $errors->first('slug') }}</span>
        @endif
    </div>
    <div class="form-group">
        <label for="meta_title" class="form-control-label">Meta Title  </label>
        {!! Form::text('meta_title', null, ['id' => 'meta_title', 'class' => 'form-control']) !!}
        @if ($errors->has('meta_title'))
            <span class="text-danger alert">{{ $errors->first('meta_title') }}</span>
        @endif
    </div>
    <div class="form-group">
        <label for="meta_description" class="form-control-label">Meta Description  </label>
        {!! Form::text('meta_description', null, ['id' => 'meta_description', 'class' => 'form-control']) !!}
        @if ($errors->has('meta_description'))
            <span class="text-danger alert">{{ $errors->first('meta_description') }}</span>
        @endif
    </div>
    <div class="form-group">
        <label for="json_screma" class="form-control-label">Json Screma </label>
        {!! Form::textarea('json_screma', null, ['id' => 'json_screma', 'class' => 'form-control','rows'=>3]) !!}
        @if ($errors->has('json_screma'))
            <span class="text-danger alert">{{ $errors->first('json_screma') }}</span>
        @endif
    </div>
    <div class="form-group">
        <label for="keyword" class="form-control-label">Keyword </label>
        {!! Form::text('keyword', null, ['id' => 'keyword', 'class' => 'form-control']) !!}
        @if ($errors->has('keyword'))
            <span class="text-danger alert">{{ $errors->first('keyword') }}</span>
        @endif
    </div>
   
    
    <div class="input-group">
        <span class="input-group-btn">
          <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
            <i class="fa fa-picture-o"></i> Image *
          </a>
        </span>
        {!! Form::text('image', null, ['id' => 'thumbnail', 'class' => 'form-control', 'required','readonly']) !!}
       
    <img id="holder" style="margin-top:15px;max-height:100px;">
    @if ($errors->has('image'))
    <span class="text-danger alert">{{ $errors->first('image') }}</span>
  @endif
    </div>
    <div class="form-group">
        <label for="header_description" class="form-control-label">Header Description * </label>
        {!! Form::textarea('header_description', null, [
            'id' => 'header_description',
            'class' => 'form-control',
            'required',
            'rows' => 2,
        ]) !!}
        @if ($errors->has('header_description'))
            <span class="text-danger alert">{{ $errors->first('header_description') }}</span>
        @endif
    </div>
    
    

    <div class="form-group">
        <label for="footer_description" class="form-control-label">Footer Description * </label>
        {!! Form::textarea('footer_description', null, [
            'id' => 'footer_description',
            'class' => 'form-control',
            'required',
            'rows' => 2,
        ]) !!}
        @if ($errors->has('footer_description'))
            <span class="text-danger alert">{{ $errors->first('footer_description') }}</span>
        @endif
    </div>
</div>
