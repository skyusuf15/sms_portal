@extends('layouts.dashboard')

@section('content_header')
    <h1>All SMS</h1>
@stop

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">SMS Sent By Batches</h3>
                </div>
                <!-- /.box-header -->
                @if ($smsBatches)
                    <div class="box-body">
                        <table class="table table-bordered table-responsive">
                            <tbody>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Batch No</th>
                                    <th>Status</th>
                                    <th>Message Count</th>
                                    <th>Time Sent</th>
                                    <th>Actions</th>
                                </tr>
                                @foreach ($smsBatches as $smsBatch)
                                <tr>
                                <td>{{$loop->iteration}}</td>
                                    <td>{{$smsBatch->batch_no}}</td>
                                    <td>
                                        @if ($smsBatch->status == 1)
                                            <span class="badge bg-green">Sent</span>
                                        @else
                                            <span class="badge bg-red">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($smsBatch->status == 1)
                                            {{ $smsBatch->messagesCount->first() ? $smsBatch->messagesCount->first()->count : ''}}
                                        @else
                                            <span class="badge bg-red">Pending</span>
                                        @endif
                                        
                                    </td>
                                    <td>
                                        {{$smsBatch->created_at}}
                                    </td>
                                    <td>
                                        @if ($smsBatch->status == 1)
                                            <a class="btn btn-primary" href="{{url('/dashboard/sms/batch', $smsBatch->batch_no)}}"><i class="fa fa-eye"></i> View</a>
                                        @else
                                            <span class="badge bg-red">Pending</span>
                                        @endif
                                        
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
                        {{-- {{$smsBatches->links('components.paginator')}} --}}
                        {{$smsBatches->links()}}
                    </div>  
                @else
                    <h3 style="text-align: center; padding: 10px;">No SMS Sent So Far</h3>
                @endif
                
            </div>
    </div>
</div>
@stop

@section('extra-js')
<script>console.log('sms index');</script>
@stop