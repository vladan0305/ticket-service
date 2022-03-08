@extends('layouts.app')

@section('title', 'All Tickets')

@section('head-css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-2">
            <ul>
                <li>
                    <a href="{{ url('admin/tickets') }}">All tickets</a>
                </li>
                <li>
                    <a href="{{ route('my_tickets', [Auth::user()->id]) }}">My tickets</a>
                </li>
            </ul>
        </div>
        <div class="col-md-9 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-ticket"> Tickets</i>
                </div>

                <div class="panel-body">
                    @if ($tickets->isEmpty())
                        <p>There are currently no tickets.</p>
                    @else
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                    <th style="text-align:center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tickets as $ticket)
                                    <tr>
                                        <td>
                                            <a href="{{ url('tickets/'. $ticket->id) }}">
                                                #{{$ticket->id }} - {{ $ticket->title }}
                                            </a>
                                            <span class="px-2"> ({{ $ticket->comments->count() }}) </span>
                                        </td>
                                        <td>
                                            @if ($ticket->status === 'Pending')
                                                <span class="label label-success">{{ $ticket->status }}</span>
                                            @else
                                                <span class="label label-danger">{{ $ticket->status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $ticket->updated_at ?? $ticket->created_at }}</td>
                                        <td>
                                            <a href="{{ url('tickets/' . $ticket->id) }}" class="btn btn-primary">Comment</a>

                                            {{-- <form action="{{ url('admin/close_ticket/' . $ticket->id) }}" method="POST">
                                                {!! csrf_field() !!}
                                                <button type="submit" class="btn btn-danger">Reject</button>
                                            </form> --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                    <th style="text-align:center">Actions</th>
                                </tr>
                            </tfoot>
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
