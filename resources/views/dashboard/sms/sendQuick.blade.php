@extends('layouts.dashboard')

@section('content_header')
    <h1>Send SMS</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Send Quick SMS</h3>
                </div>
        <!-- /.box-header -->
        <!-- form start -->
            <form role="form" method="POST" enctype="multipart/form-data" action="{{url('/dashboard/sms/quick')}}" >
            @csrf
            <div class="box-body">
                    <div class="form-group">
                        <label for="sender">Sender ID</label>
                        <input type="text" class="form-control" name="sender"  id="sender" value="{{old('sender')}}" placeholder="Sender ID">
                    </div>
                <div class="form-group">
                    <label for="receipient">Receipient</label>
                    <input type="text" class="form-control" name="receipient"  id="receipient" placeholder="Receipient">
                    @if ($errors->has('receipient'))
                        <p class="help-block">{{$errors->first('receipient')}}</p>
                    @endif
                </div>
                
                <div class="form-group">
                    <label>Message</label>
                <textarea class="form-control" name="message" required="required" rows="3" placeholder="Message">{{old('message')}}</textarea>
                    @if ($errors->has('message'))
                        <p class="help-block">{{$errors->first('message')}}</p>
                    @endif
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Send Message</button>
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