@extends('layouts.app')

@section('title', 'My Tickets')

@section('head-css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-ticket"> My Tickets</i>
                </div>

                <div class="panel-body">
                    @if($tickets->isEmpty())
                        <p>You have not created any tickets.</p>
                    @else
                        <table class="table" id="example">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $ticket)
                                    <tr>
                                        <td>
                                            <a href="{{ url('tickets/' . $ticket->id) }}">
                                                #{{ $ticket->id }} - {{ $ticket->title }}
                                            </a>
                                            <span class="px-2">({{ $ticket->comments->count()}})</span>
                                        </td>
                                        <td>
                                            @if($ticket->status == "Open")
                                                <span class="label label-success">{{ $ticket->status }}</span>
                                            @else
                                                <span class="label label-danger">{{ $ticket->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $ticket->updated_at ?? $ticket->created_at}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="//cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable( {
                "ordering": false
            } );
        } );
    </script>
@endsection
