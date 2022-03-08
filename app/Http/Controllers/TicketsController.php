<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TicketsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::orderBy('updated_at', 'desc')->orderBy('created_at', 'desc')->get();
        return view('tickets.index', compact('tickets'));
    }

    public function myTickets($id)
    {
        $tickets = Ticket::where('support_id', Auth::user()->id)->orderBy('updated_at', 'desc')->orderBy('created_at', 'desc')->get();
        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'message' => 'required'
        ]);
        $ticket = new Ticket([
            'title' => $request->input('title'),
            'user_id' => Auth::user()->id,
            'message' => $request->input('message'),
            'status' => "Pending"
        ]);
        $ticket->save();

        $details = [
            'title' => 'Thank you '. Auth::user()->name .' for contacting our support team. A support ticket has been opened for you. You will be notified when a response is made by email. The details of your ticket are shown below:',
            'body' => 'Title: '. $ticket->title .'</p>
            <p>Status: '.$ticket->status .'</p>
            <p>
            You can view the ticket at any time at '. url("tickets/". $ticket->id)
        ];

        Mail::to($ticket->user->email)->send(new \App\Mail\NotificationMail($details));

        return redirect()->back()->with("status", "A ticket with ID: #$ticket->id has been opened.");
    }

    public function userTickets()
    {
        $tickets = Ticket::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return view('tickets.user_tickets', compact('tickets'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = Ticket::where('id', $id)->firstOrFail();
        return view('tickets.show', compact('ticket'));
    }

    public function changeStatus($ticket_id, Request $request)
    {

        $this->validate($request, [
            'status' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $ticket = Ticket::where('id', $ticket_id)->first();
        if(!$ticket){
            return redirect()->back()->with("error", "Something went wrong.");
        }
        $ticket->status = $request->status;

        if(isset($request->image)) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            if(isset($ticket->image) && !empty($ticket->image)) {
                unlink(public_path('images/'.$ticket->image));
            }
            $ticket->image = $imageName;
        }

        $ticket->save();

        $details = [
            'title' => 'Hello ' .$ticket->user->name . ',',
            'body' => 'Your support ticket with ID #'.$ticket->id .' has been marked as: ' .$ticket->status . '.'
        ];

        Mail::to($ticket->user->email)->send(new \App\Mail\NotificationMail($details));

        return redirect()->back()->with("status", "You changed status for ticket with id: ". $ticket->id .".");

    }

    public function claimTicket($id)
    {
        $ticket = Ticket::where('id', $id)->firstOrFail();
        $ticket->support_id = Auth::user()->id;
        $ticket->save();

        return redirect()->back()->with("status", "You claimed ticket with id: ". $id .".");
    }

    public function close($id)
    {
        $ticket = Ticket::where('id', $id)->firstOrFail();
        $ticket->status = "Rejected";
        $ticket->save();

        $details = [
            'title' => 'Hello ' .$ticket->user->name . ',',
            'body' => 'Your support ticket with ID #'.$ticket->id .' has been marked as: ' .$ticket->status . '.'
        ];

        Mail::to($ticket->user->email)->send(new \App\Mail\NotificationMail($details));

        return redirect()->back()->with("status", "The ticket has been closed.");
    }
}
