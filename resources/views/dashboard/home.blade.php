@extends('layouts.dashboard')

@section('title', 'Remta SMS Portal')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="row">
  <div id="wallet">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
         <span class="info-box-icon bg-green"><i class="fas fa-wallet"></i></span>
         <div class="info-box-content">
           <span class="info-box-text">Wallet Balance</span>
           <span class="info-box-number"><i class="fas fa-spin fa-spinner"></i> Loading......</span>
         </div>
         <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
   </div>
  </div>
</div>

{{--
<div class="row">
  <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-yellow"><i class="ion ion-ios-albums"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">SMS Batches</span>
          <span class="info-box-number">{{$smsBatches}}<small></small></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="far fa-envelope"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Messages</span>
          <span class="info-box-number">{{$messages}}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
  </div>
  <!-- /.col -->
</div>
<div class="row">
  <div id="chart">
      <div class="col-sm-12">
          <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">SMS Chart</h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> --}}
                </div>
            </div>
            <div class="box-body" style="">
                <div class="chart">
                  <div style="display:flex;justify-content:center;align-items:center">
                    <i class="fas fa-spinner fa-spin"></i><span>&nbsp;Loading.....</span>
                  </div>
                </div>
            </div>
            <!-- /.box-body -->
          </div>
      </div>
  </div>
  
</div>--}}
@stop

@section('extra-js')
    <script src="{{mix('js/dashboard-home.js')}}"></script>
<script>console.log('Home');</script>
@stop