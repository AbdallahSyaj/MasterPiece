<!-- resources/views/admindashboard/messages.blade.php -->

@extends('admindashboard.layout')
@section('title', 'Messages')

@section('contant')
<div class="container-fluid">
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Messages</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($messages as $message)
                                <tr>
                                    <td>{{ $message->id }}</td>
                                    <td>{{ $message->name }}</td>
                                    <td>{{ $message->email }}</td>
                                    <td>{{ Str::limit($message->message, 50) }}</td>
                                    <td>{{ $message->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        @if($message->replied)
                                            <span class="badge badge-success">Replied</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#replyModal{{ $message->id }}">
                                            Reply
                                        </button>
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewModal{{ $message->id }}">
                                            View
                                        </button>
                                    </td>
                                </tr>

                                <!-- View Message Modal -->
                                <div class="modal fade" id="viewModal{{ $message->id }}" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel{{ $message->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewModalLabel{{ $message->id }}">Message from {{ $message->name }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>From:</strong> {{ $message->name }} ({{ $message->email }})</p>
                                                <p><strong>Date:</strong> {{ $message->created_at->format('M d, Y H:i') }}</p>
                                                <hr>
                                                <p>{{ $message->message }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#replyModal{{ $message->id }}" data-dismiss="modal">Reply</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reply Modal -->
                                <div class="modal fade" id="replyModal{{ $message->id }}" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel{{ $message->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="replyModalLabel{{ $message->id }}">Reply to {{ $message->name }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('admin.messages.reply', $message->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="original-message">Original Message:</label>
                                                        <textarea class="form-control" id="original-message" rows="3" readonly>{{ $message->message }}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="reply-message">Your Reply:</label>
                                                        <textarea class="form-control" id="reply-message" name="reply" rows="5" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Send Reply</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No messages found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $messages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection