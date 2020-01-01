@extends('layouts.dashboard')

@section('content_header')
    <h1>SMS History</h1>
@stop

@section('content')
<div class="row">
    <div class="col-sm-12"></div>
    <div class="col-sm-12">
            <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">SMS Sent In This Batch</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered table-responsive">
                            <tbody>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Batch ID</th>
                                    <th>Report Date</th>
                                    <th>Mobile Number</th>
                                    <th>Unit Charged</th>
                                </tr>
                                @foreach ($sms_history as $sms)
                                <tr>
                                <td>{{$loop->iteration}}</td>
                                    <td>{{$sms->BatchID}}</td>
                                    <td>{{ \Carbon\Carbon::parse($sms->ReportDate)}}</td>
                                    <td>{{$sms->MobileNumber}}</td>
                                    <td>{{$sms->UnitsCharged}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                <!-- /.box-body -->
                    <div class="box-footer clearfix">
                        {{$sms_history->links()}}
                    </div>
                </div>
        </div>
</div>
@stop

@section('extra-js')
<script>console.log('sms index');</script>
@stop