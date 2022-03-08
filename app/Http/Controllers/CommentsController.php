<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use Illuminate\Support\Facades\Mail;

class CommentsController extends Controller
{
    public function postComment(Request $request)
    {
        $this->validate($request, [
            'comment' => 'required'
        ]);
        $comment = Comment::create([
            'ticket_id' => $request->input('ticket_id'),
            'user_id' => Auth::user()->id,
            'comment' => $request->input('comment')
        ]);
        // send mail if the user commenting is not the ticket owner
        if($comment->ticket->user->id !== Auth::user()->id) {
            $details = [
                'title' => $comment->comment,
                'body' => "Replied by: " . Auth::user()->name ."</p>

                <p>Title: ". $comment->ticket->title ."</p>
                <p>Ticket ID: ". $comment->ticket->id ."</p>
                <p>Status: ". $comment->ticket->status ."</p>

                <p>
                    You can view the ticket at any time at ". url('tickets/'. $comment->ticket->id)
            ];

            Mail::to($comment->ticket->user->email)->send(new \App\Mail\NotificationMail($details));
        }
        return redirect()->back()->with("status", "Your comment has be submitted.");
    }
}
