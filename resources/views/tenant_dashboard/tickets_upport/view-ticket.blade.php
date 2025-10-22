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
                    <h4 class="mb-0">acx <small class="text-muted">{{$ticketsdata->random_id ?? ''}}</small></h4>
                    <div class="small text-muted mt-1">{{$userdata->first_name ?? ''}} {{$userdata->last_name ?? ''}} &nbsp; &nbsp; | &nbsp; {{ \Carbon\Carbon::parse($ticketsdata->created_at)->format('m-d-Y') }}</div>
                </div>
                <div>
                    <h6 class="mb-0">Ticket Category : <small class="text-muted">{{$ticketsdata->category ?? ''}}</small></h6>
                </div>
            </div>
        </div>

    @if($ticketsdata->status == '3')

    @else
        <!-- Editor Form -->
        <form action="{{ url('viewticket-store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="subject" value="{{$ticketsdata->subject ?? ''}}">
            <input type="hidden" name="status" value="1">
            <input type="hidden" name="category" value="{{$ticketsdata->category ?? ''}}">
            <input type="hidden" name="ticket_id" value="{{$ticketsdata->id ?? ''}}">
            <input type="hidden" name="id" value="{{$ticketsdata->id ?? ''}}">

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
                    <div class="btn-group">
                        <button type="Submit" id="submitStatusBtn" class="btn btn-dark action-btn mx-1">
                            Submit</b></span>
                        </button>
                    </div>
                </div>
            </div>
        </form>

    @endif

        <!-- Example previous reply -->
    @foreach($ticketsupportdata as $ticketsupportval)
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
