@extends('layouts.app')
@section('page-title')
    {{ __('View Ticket') }}
@endsection
@section('breadcrumb')
   <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('ticket-support') }}">{{ __('Ticket List') }}</a></li>
    <li class="breadcrumb-item" aria-current="page"> {{ __('View') }}</li>
    
@endsection

@section('content')
<div class="card border bg-custom w-100">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div class="d-flex justify-content-between" style="width: 100%;">
                <div>
                    <h4 class="mb-0">acx <small class="text-muted">{{$ticketalldata->random_id ?? ''}}</small></h4>
                    <div class="small text-muted mt-1">{{$useralldata->first_name ?? ''}} {{$useralldata->last_name ?? ''}} &nbsp; &nbsp; | &nbsp; {{ \Carbon\Carbon::parse($ticketalldata->created_at)->format('m-d-Y') }}</div>
                </div>
                <div>
                    <h6 class="mb-0">Ticket Category : <small class="text-muted">{{$ticketalldata->category ?? ''}}</small></h6>
                </div>
            </div>
        </div>
    @if($ticketalldata->status == '3')

    @else
        <!-- Editor Form -->
        <form action="{{ url('ownerviewticket-store') }}" method="POST" id="statusForm" enctype="multipart/form-data">
         @csrf
            <input type="hidden" name="subject" value="{{$ticketalldata->subject ?? ''}}">
            <input type="hidden" name="category" value="{{$ticketalldata->category ?? ''}}">
            <input type="hidden" name="ticket_id" value="{{$ticketalldata->id ?? ''}}">
            <input type="hidden" name="id" value="{{$ticketalldata->id ?? ''}}">
            <input type="hidden" name="tenant_id" value="{{$ticketalldata->tenant_id ?? ''}}">
            <input type="hidden" name="property_id" value="{{$ticketalldata->property_id ?? ''}}">
            <div class="mb-3">
                <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                <textarea id="editor" name="description" required>
                    
                </textarea>
            </div>

            <!-- Attachment + Submit row -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <label for="company_name" class="form-label">Attachment <span class="text-danger">*</span></label>
                    <input type="file" id="company_name" name="photo" class="form-control" required="" />
                </div>

                <div class="col-lg-12 text-end mt-2">
                    <!-- Status dropdown + Submit -->
                    <div class="btn-group">
                    <button type="submit" id="submitStatusBtn" class="btn btn-dark action-btn mx-1">
                        Submit as <span id="statusLabel1">
                            <b>
                                @if($ticketalldata->status == '1')
                                    Pending
                                @elseif($ticketalldata->status == '2')
                                    Resolved
                                @else
                                    Closed
                                @endif
                            </b>
                        </span>
                    </button>

                    <button type="button" class="btn btn-dark dropdown-toggle dropdown-toggle-split"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="visually-hidden">Toggle</span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <input type="hidden" name="status" id="statusInput" value="{{ $ticketalldata->status }}">

                        <li><a class="dropdown-item status-option {{ $ticketalldata->status == '1' ? 'active' : '' }}" href="#" data-status="1">Submit as Pending</a></li>
                        <li><a class="dropdown-item status-option {{ $ticketalldata->status == '2' ? 'active' : '' }}" href="#" data-status="2">Submit as Resolved</a></li>
                        <li><a class="dropdown-item status-option {{ $ticketalldata->status == '3' ? 'active' : '' }}" href="#" data-status="3">Submit as Closed</a></li>
                    </ul>
                </div>

                </div>
            </div>
        </form>
    @endif

        <!-- Example previous reply -->
    @foreach($ticketsupportall as $ticketsupportval)
       @php
          $userall = DB::table('users')->where('id',$ticketsupportval->tenant_id)->first();
       @endphp
        <div class="reply-card d-flex gap-3">
         @if(!empty($userall->profile))
            <img src="{{asset('storage/upload/profile/'.$userall->profile ?? '')}}" class="rounded-circle" alt="avatar" style="width: 40px;height: 42px;" />
         @else
            <img src="https://placehold.co/50x50" class="rounded-circle" alt="avatar" />
         @endif
            <div>
                <div class="d-flex align-items-center mb-1">
                    <strong class="me-2">{{$userall->first_name ?? ''}} {{$userall->last_name ?? ''}}</strong>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($ticketsupportval->created_at)->format('m-d-Y h:i A') }}</small>
                    @if(!empty($ticketsupportval->photo))
                        <a href="{{ asset('storage/upload/tickets/' . $ticketsupportval->photo) }}" target="_blank">
                            <i class="ti ti-eye me-2 editRow fs-4" data-bs-toggle="tooltip" title="View"></i>
                        </a>
                    @endif
                </div>
                <div>Status 
                @if($ticketsupportval->status == '1')
                    <span class="badge bg-warning text-dark">Open</span>
                @elseif($ticketsupportval->status == '2')
                    <span class="badge bg-success">Resolved</span>
                @else
                    <span class="badge bg-danger">Closed</span>
                @endif
                </div>
                <div><?php echo $ticketsupportval->description ?? '';?></div>
            </div>
        </div>
    @endforeach
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    $(document).on('click', '.status-option', function(e) {
        e.preventDefault();

        let status = $(this).data('status');
        let label = '';

        if(status == 1) label = 'Pending';
        else if(status == 2) label = 'Resolved';
        else label = 'Closed';

        // Update hidden input
        $('#statusInput').val(status);

        // Update button text
        $('#statusLabel1').html('<b>' + label + '</b>');

        // Highlight selected dropdown item
        $('.status-option').removeClass('active');
        $(this).addClass('active');

        // 🔥 Auto-submit the form
        /*$('#statusForm').submit();*/
    });
</script>

<!-- CKEditor 5 Classic Build -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
  ClassicEditor
    .create(document.querySelector('#editor'))
    .catch(error => {
        console.error(error);
    });
</script>

@endsection
