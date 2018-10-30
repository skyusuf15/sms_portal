@extends('layouts.dashboard')

@section('content_header')
    <h1>SMS Batch {{$smsBatch->batch_no}}</h1>
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
                                    <th>Message ID</th>
                                    <th>Receipient</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                @foreach ($messages as $message)
                                <tr>
                                <td>{{$loop->iteration}}</td>
                                    <td>{{$message->messageId}}</td>
                                     {{--<td>
                                        {{'234'.ltrim($message->receipient, '0')}}
                                    </td>--}}
                                    <td>
                                        {{$message->receipient}}
                                    </td>
                                    <td>
                                        @if ($message->status == 1)
                                            <span class="badge bg-green">Sent</span>
                                        @else
                                            <span class="badge bg-red">Not Sent</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-primary" href="{{url('/dashboard/sms/message', $message->messageId)}}"><i class="fa fa-eye"></i> View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                <!-- /.box-body -->
                    <div class="box-footer clearfix">
                        {{-- <ul class="pagination pagination-sm no-margin pull-right">
                            <li><a href="#">«</a></li>
                            <li><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">»</a></li>
                        </ul> --}}
                        {{-- {{$messages->links('components.paginator')}} --}}
                        {{$messages->links()}}
                    </div>
                </div>
        </div>
</div>
@stop

@section('extra-js')
<script>console.log('sms index');</script>
@stop