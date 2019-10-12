@extends('layouts.dashboard')

@section('content_header')
    <h1>SMS {{$message->messageId}}</h1>
@stop

@section('extra-css')
    <style>
        .message-details {
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
            border-radius: 3px;
            margin-top: 0;
            background: #fff;
            color: #444;
            /* margin-left: 60px;
            margin-right: 15px; */
            padding: 20px;
            position: relative;
        }

        .message-details >.time {
            color: #999;
            float: right;
            padding: 10px;
            font-size: 12px;
        }

        .message-details > .timeline-header {
            margin: 0;
            color: #555;
            border-bottom: 1px solid #f4f4f4;
            padding: 10px;
            font-size: 16px;
            line-height: 1.1;
        }
        .message-details > .timeline-body {
            padding: 10px;
        }

        .message-details > .timeline-footer {
            padding: 10px;
        }
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="message-details">
            <span class="time"><i class="fa fa-clock-o"></i> {{$message->created_at ? $message->created_at : 'N/A'}}</span>
            <h3 class="timeline-header"><i class="fa fa-user"></i> {{'234'.ltrim($message->receipient, '0')}}</h3>
            <div class="timeline-body">
                {{$message->message}}
            </div>
            <div class="timeline-footer">
                {{-- <a class="btn btn-primary btn-xs">Read more</a>
                <a class="btn btn-danger btn-xs">Delete</a> --}}
                @if ($message->status == 1)
                    <span class="badge bg-green">Sent</span>
                @else
                    <span class="badge bg-red">Not Sent</span>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('extra-js')
<script>console.log('sms message');</script>
@stop