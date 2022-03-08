@extends('layouts.app')

@section('title', $ticket->title)

@section('content')

<div class="container">


    <div class="row">
        <div class="col-md-2">
            <ul>
                @if(Auth::user()->is_admin == 1)
                    <li>
                        <a href="{{ url('admin/tickets') }}">All tickets</a>
                    </li>
                    <li>
                        <a href="{{ route('my_tickets', [Auth::user()->id]) }}">My tickets</a>
                    </li>
                @else
                    <li>
                        <a href="{{ url('my_tickets') }}">My tickets</a>
                    </li>
                @endif
            </ul>
        </div>
        <div class="col-md-10 col-md-offset-1">
            @if(auth()->user()->is_admin == 1)
                @if($ticket->support_id == 0)
                    <div class="row">
                        <form action="{{ url('claim_ticket/' . $ticket->id) }}" method="POST">
                            {!! csrf_field() !!}
                            <button type="submit" class="btn btn-danger">Claim ticket</button>
                        </form>
                    </div>
                @endif

                <form action="{{ route('change_status', ['ticket_id' => $ticket->id]) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <label for="status" class="col-md-4 control-label">Set status</label>
                    <div class="row my-4">
                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }} col-6">
                            <select id="status" type="" class="form-control" name="status">
                                <option value="Pending" @if($ticket->status == "Pending") selected @endif>Pending</option>
                                <option value="In progress" @if($ticket->status == "In progress") selected @endif>In progress</option>
                                <option value="Completed" @if($ticket->status == "Completed") selected @endif>Completed</option>
                                <option value="Rejected" @if($ticket->status == "Rejected") selected @endif>Rejected</option>
                            </select>
                            @if ($errors->has('status'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('status') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-4">
                            <button id="but_upload" type="submit" class="btn btn-danger">Set status</button>
                        </div>

                        <div class="col-md-6 " id="upload-image">
                            <input id="file" type="file" name="image" class="form-control">
                        </div>
                    </div>
                </form>
                {{-- <form action="{{ route('upload_image', ['ticket_id' => $ticket->id]) }}" method="post" id="upload-image" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 ">
                            <input type="file" name="image" class="form-control">
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-danger">Upload image</button>
                        </div>
                    </div>

                </form> --}}
            @endif

            @if (isset($ticket->image) && !empty($ticket->image))
                <img src="{{ url('images/'.$ticket->image) }}" alt="{{ $ticket->title }}" class="img-fluid my-4">
            @endif

            <div class="panel panel-default">
                <div class="panel-heading">
                    #{{ $ticket->id }} - {{ $ticket->title }}
                </div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="ticket-info">
                        <p>{{ $ticket->message }}</p>
                        <p class="ticket-info-status">
                            @if ($ticket->status === 'Pending')
                                Status: <span class="label label-success">{{ $ticket->status }}</span>
                            @else
                                Status: <span class="label label-danger">{{ $ticket->status }}</span>
                            @endif
                        </p>
                        <p>Created on: {{ $ticket->created_at->diffForHumans() }}</p>
                    </div>

                </div>
            </div>

            <hr>

            @include('tickets.comments')

            <hr>

            @include('tickets.reply')

        </div>
    </div>
</div>

@endsection

@section('footer-js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script>
        $(document).ready(function () {
            var stat = $("#status").val();
            if(stat == 'Rejected' || stat == 'Completed') {
                $('#upload-image').show();
            } else {
                $('#upload-image').hide();
            }

            $('#status').change(function(){
                if($("#status").val() == 'Rejected' || $("#status").val() == 'Completed') {
                    $('#upload-image').show();
                } else {
                    $('#upload-image').hide();
                }
            });
        });
    </script>
@endsection
