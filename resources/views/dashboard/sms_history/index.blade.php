@extends('layouts.dashboard')

@section('content_header')
    <h1>Upload SMS History</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                </div>
        <!-- /.box-header -->
        <!-- form start -->
            <form role="form" method="POST" enctype="multipart/form-data" action="{{url('/dashboard/sms_history/upload')}}" >
            @csrf
            <div class="box-body">
                
                <div class="form-group">
                    <label for="contacts">Upload CSV file</label>
                    <input type="file" class="form-control" name="file"  id="file" placeholder="Upload sms history">
                    @if ($errors->has('file'))
                        <p class="help-block">{{$errors->first('file')}}</p>
                    @endif
                </div>
                
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Upload History</button>
            </div>
        </form>
        </div>
    </div>
</div>
@stop

@section('extra-js')
@if (session('alert'))
@component('components.alert',
 [
     'title' => session('alert')['title'],
     'message' => session('alert')['message'],
     'status' => session('alert')['status']
 ])
@endcomponent
@endif
@stop