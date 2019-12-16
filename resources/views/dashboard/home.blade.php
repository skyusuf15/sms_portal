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

  <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-purple"><i class="ion ion-ios-albums"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Total Unit</span>
          <span class="info-box-number" id="total_unit">0<small></small></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
  </div>

  <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-blue"><i class="fas fa-money-check-alt"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Charged Usage</span>
          <span class="info-box-number" id="charge_usage">0<small></small></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
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

--}}

<div class="row">
  <div id="">
      <div class="col-sm-12">
          <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">SMS Prize Analysis</h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body" style="">


                  <div id="loader" style="display:flex;justify-content:center;align-items:center">
                    <i class="fas fa-spinner fa-spin"></i><span>&nbsp;Loading.....</span>
                  </div>

                  <table id="prefix" class="table table-bordered table-responsive">
                      <thead>
                          <tr>
                              <th style="width: 10px">#</th>
                              <th>Network</th>
                              <th>Price Per Unit (&#x20A6;)</th>
                              <th>Network Count</th>
                              <th>SMS Unit</th>
                              <th>Total Charged (&#x20A6;)</th>
                          </tr>
                      </thead>
                      <tbody></tbody>
                  </table>

            </div>
            <!-- /.box-body -->
          </div>
      </div>
  </div>
  
</div>




{{--

<div class="row">
  <div id="chart">
      <div class="col-sm-12">
          <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">SMS Chart</h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> 
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
  
</div>
--}}

@stop

@section('extra-js')
<script src="{{mix('js/dashboard-home.js')}}"></script>
<script>console.log('Home');</script>
 
<script>
$(function(){

  $.getJSON("{{url('/dashboard/getPrefixCount')}}"  , function(result){
    var tbody = ``;
    var total_unit=0, charge_usage=0;

    $.each(Object.keys(result), function(i,v){
      total_unit += result[v].unit_charge;
      charge_usage += result[v].unit_charge*result[v].price_per_unit;
      tbody += `<tr><td style="width: 10px">${i+1}</td><td>${v}</td><td>${result[v].price_per_unit}</td><td>${result[v].network_count}</td><td>${result[v].unit_charge}</td><td>${(result[v].unit_charge*result[v].price_per_unit).toFixed(2)}</td></tr>`; 
    });
    $("#prefix").find("tbody").html(tbody);
    $("#total_unit").text(Number(total_unit).toLocaleString());
    $("#charge_usage").html('&#x20A6; ' + Number((charge_usage).toFixed(2)).toLocaleString());
    $("#loader").hide();
  });

});


</script>


@stop