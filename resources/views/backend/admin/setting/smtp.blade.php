@extends('backend.layouts.master')
@section('title', 'Create Slider')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">SMTP Setup</p>
                        <a href="{{route(Request::segment(1).'.setting.index')}}"
                            class="btn btn-primary btn-sm ms-auto">Back</a>
                    </div>
                </div>
                <div class="card-body">
                    @include('partial.formerror')
                    {!! Form::open(['route' => Request::segment(1) . '.envKeyUpdate', 'method' => 'POST', 'files' =>
                    true]) !!}

                    <div class="panel-body">

                        <div class="form-group">
                            <input type="hidden" name="types[]" value="MAIL_DRIVER">
                            <div class="col-lg-12">
                                <label class="control-label">MAIL DRIVER</label>
                            </div>
                            <div class="col-lg-12">
                                <select class="form-control" name="MAIL_DRIVER" onchange="checkMailDriver()">
                                    {{-- <option value="sendmail" @if (env('MAIL_DRIVER')=="sendmail" ) selected @endif>
                                        Sendmail</option> --}}
                                    <option value="smtp" @if (env('MAIL_DRIVER')=="smtp" ) selected @endif>SMTP</option>
                                </select>
                            </div>
                        </div>
                        <div id="smtp">
                            <div class="form-group">
                                <input type="hidden" name="types[]" value="MAIL_HOST">
                                <div class="col-lg-12">
                                    <label class="control-label">MAIL HOST</label>
                                </div>
                                <div class="col-lg-12">
                                    <input type="text" class="form-control" name="MAIL_HOST"
                                        value="{{  env('MAIL_HOST') }}" placeholder="MAIL HOST">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="types[]" value="MAIL_PORT">
                                <div class="col-lg-12">
                                    <label class="control-label">MAIL PORT</label>
                                </div>
                                <div class="col-lg-12">
                                    <input type="text" class="form-control" name="MAIL_PORT"
                                        value="{{  env('MAIL_PORT') }}" placeholder="MAIL PORT">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="types[]" value="MAIL_USERNAME">
                                <div class="col-lg-12">
                                    <label class="control-label">MAIL USERNAME</label>
                                </div>
                                <div class="col-lg-12">
                                    <input type="text" class="form-control" name="MAIL_USERNAME"
                                        value="{{  env('MAIL_USERNAME') }}" placeholder="MAIL USERNAME">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="types[]" value="MAIL_PASSWORD">
                                <div class="col-lg-12">
                                    <label class="control-label">MAIL PASSWORD</label>
                                </div>
                                <div class="col-lg-12">
                                    <input type="text" class="form-control" name="MAIL_PASSWORD"
                                        value="{{  env('MAIL_PASSWORD') }}" placeholder="MAIL PASSWORD">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="types[]" value="MAIL_ENCRYPTION">
                                <div class="col-lg-12">
                                    <label class="control-label">MAIL ENCRYPTION</label>
                                </div>
                                <div class="col-lg-12">
                                    <input type="text" class="form-control" name="MAIL_ENCRYPTION"
                                        value="{{  env('MAIL_ENCRYPTION') }}" placeholder="MAIL ENCRYPTION">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="types[]" value="MAIL_FROM_ADDRESS">
                                <div class="col-lg-12">
                                    <label class="control-label">MAIL FROM ADDRESS</label>
                                </div>
                                <div class="col-lg-12">
                                    <input type="text" class="form-control" name="MAIL_FROM_ADDRESS"
                                        value="{{  env('MAIL_FROM_ADDRESS') }}" placeholder="MAIL FROM ADDRESS">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="types[]" value="MAIL_FROM_NAME">
                                <div class="col-lg-12">
                                    <label class="control-label">MAIL FROM NAME</label>
                                </div>
                                <div class="col-lg-12">
                                    <input type="text" class="form-control" name="MAIL_FROM_NAME"
                                        value="{{  env('MAIL_FROM_NAME') }}" placeholder="MAIL FROM NAME">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-12 text-right">
                                <button class="btn btn-success" type="submit">Save</button>
                            </div>
                        </div>
                        </form>
                    </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('js')

<script type="text/javascript">
    $(document).ready(function(){
            checkMailDriver();
        });
        function checkMailDriver(){
            if($('select[name=MAIL_DRIVER]').val() == 'mailgun'){
                $('#mailgun').show();
                $('#smtp').hide();
            }
            else{
                $('#mailgun').hide();
                $('#smtp').show();
            }
        }
</script>

@endpush