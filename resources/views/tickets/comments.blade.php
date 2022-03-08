<div class="comments">
    @foreach($ticket->comments as $comment)
        <div class="panel panel-@if($ticket->user->id === $comment->user_id){{"default"}}@else{{"success"}}@endif">
            <div class="panel panel-heading">
                {{ $comment->user->name }}

                <span class="pull-right">{{ $comment->created_at->format('d.m.Y') }}</span>
            </div>

            <div class="panel panel-body">
                {{ $comment->comment }}
            </div>
        </div>
        <hr>
    @endforeach
</div>
