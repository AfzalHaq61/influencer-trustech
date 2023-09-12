@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-xl-3 col-lg-5 col-md-5 col-sm-12">
            <div class="row gy-4">
                <div class="col-12">
                    <div class="card ">
                        <div class="card-body">
                            <img src="{{ getImage(getFilePath('service').'/'.$job->image, getFileSize('service')) }}" alt="@lang('job image')" class=" b-radius--10 w-100">
                            <h5 class="mt-3">{{__($job->name)}}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card ">
                        <div class="card-header">
                            @if($job->user_id != 0)
                                <h5>@lang('User Information')</h5>
                            @elseif($job->influencer_id != 0)
                                <h5>@lang('Influencer Information')</h5>
                            @elseif($job->freelancer_id != 0)
                                <h5>@lang('Freelancer Information')</h5>
                            @endif  
                           
                        </div>
                        <div class="card-body">
                           <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Name')
                                    @if($job->user_id != 0)
                                        <span class="fw-bold">{{$job->user->fullname}}</span>
                                    @elseif($job->influencer_id != 0)
                                        <span class="fw-bold">{{ @$job->influencer->fullname }}</span>
                                    @elseif($job->freelancer_id != 0)
                                        <span class="fw-bold">{{ @$job->freelancer->fullname }}</span>
                                    @endif  
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Username')
                                    @if($job->user_id != 0)
                                        <span class="fw-bold"><a href="{{ route('admin.users.detail', $job->user->id) }}">{{$job->user->username}}</a></span>
                                    @elseif($job->influencer_id != 0)
                                        <span class="fw-bold"><a href="{{ route('admin.users.detail', $job->influencer->id) }}">{{$job->influencer->username}}</a></span>
                                    @elseif($job->freelancer_id != 0)
                                        <span class="fw-bold"><a href="{{ route('admin.users.detail', $job->freelancer->id) }}">{{$job->freelancer->username}}</a></span>
                                    @endif
                                    
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Status')
                                    @if($job->user_id != 0)
                                        @if($job->user->status)
                                            <span class="badge badge--success">@lang('Active')</span>
                                        @else
                                            <span class="badge badge--danger">@lang('Banned')</span>
                                        @endif
                                    @elseif($job->influencer_id != 0)  
                                        @if($job->influencer->status)
                                            <span class="badge badge--success">@lang('Active')</span>
                                        @else
                                            <span class="badge badge--danger">@lang('Banned')</span>
                                        @endif
                                    @elseif($job->freelancer_id != 0)
                                        @if($job->freelancer->status)
                                            <span class="badge badge--success">@lang('Active')</span>
                                        @else
                                            <span class="badge badge--danger">@lang('Banned')</span>
                                        @endif
                                    @endif
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Balance')
                                    @if($job->user_id != 0)
                                        <span class="fw-bold">{{getAmount($job->user->balance)}}  {{__($general->cur_text)}}</span>
                                    @elseif($job->influencer_id != 0)
                                        <span class="fw-bold">{{getAmount($job->influencer->balance)}}  {{__($general->cur_text)}}</span>
                                    @elseif($job->freelancer_id != 0)
                                        <span class="fw-bold">{{getAmount($job->freelancer->balance)}}  {{__($general->cur_text)}}</span>
                                    @endif
                                    
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-9 col-lg-7 col-md-7 col-sm-12">
            <div class="row gy-4">
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5>@lang('Job Information')</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Type')
                                    <span class="fw-bold">{{__(@$job->job_type)}}</span>
                                </li>
                                @if($job->job_type == 'event')
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Event Date')
                                    <span class="fw-bold">{{date('d-m-Y',strtotime($job->event_date))}}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Event Location')
                                    <span class="fw-bold">{{$job->event_location}}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Attend Event')
                                    <span class="fw-bold">{{$job->event_attend}}</span>
                                </li>
                                @elseif($job->job_type == 'campaign')
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Start Date')
                                    <span class="fw-bold">{{date('d-m-Y',strtotime($job->campaign_start_date))}}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('End Date')
                                    <span class="fw-bold">{{date('d-m-Y',strtotime($job->campaign_end_date))}}</span>
                                </li>
                                @endif
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Category')
                                    <span class="fw-bold">{{__(@$job->jobCategory->name)}}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Subcategory')
                                    <span class="fw-bold">{{__(@$job->JobSubCategory->name)}}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Target Demo. Age From')
                                    <span class="fw-bold">{{$job->target_demographic_from}} Years</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Target Demo. Age To')
                                    <span class="fw-bold">{{$job->target_demographic_to}} Years</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Target Demo. Gender')
                                    <span class="fw-bold">{{$job->target_demographic_gender}} </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Price')
                                    <span class="fw-bold">{{showAmount($job->price)}} {{__($general->cur_text)}}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Delivery Time')
                                    <span class="fw-bold">{{$job->delivery_time}} @lang('Day(s)')</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Status')
                                    <span class="fw-bold">@php echo $job->customStatusBadge @endphp</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Last Update')
                                    <span class="fw-bold">{{diffforhumans($job->updated_at)}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5>@lang('Skills')</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @if($job->skill)
                                @foreach ($job->skill as $skill)
                                    <li class="list-group-item">
                                        <span class="fw-bold">{{__($skill)}}</span>
                                    </li>
                                @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>@lang('Description')</h5>
                        </div>
                        <div class="card-body">
                            @php echo $job->description @endphp
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="card">
                        <h5 class="card-header">@lang('Requirements')</h5>
                        <div class="card ">
                            <div class="card-body p-3">
                                @php echo $job->requirements @endphp
                            </div>
                        </div>
                    </div>
                </div>
                @if($job->job_type == 'campaign')
                <div class="col-xl-12">
                    <div class="card">
                        <h5 class="card-header">@lang('Campaign Guidelines')</h5>
                        <div class="card ">
                            <div class="card-body p-3">
                                @php echo $job->campaign_guidelines @endphp
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @if($job->job_type == 'casting')
                <div class="col-xl-12">
                    <div class="card">
                        <h5 class="card-header">@lang('Casting Ideas')</h5>
                        <div class="card ">
                            <div class="card-body p-3">
                                @php echo $job->casting_job_idea @endphp
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-modal/>
@endsection

@push('breadcrumb-plugins')
@if ($job->status == app\constants\Status::PENDING)
<button class="btn btn-sm btn-outline--success confirmationBtn" data-action="{{route('admin.job.status.change', [$job->id, 'approve'])}}" data-question="@lang('Are you sure to Approve this job')?">
    <i class="las la-check-circle"></i>@lang('Approve')
</button>
<button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{route('admin.job.status.change', [$job->id, 'cancel'])}}" data-question="@lang('Are you sure to cancel this job')?">
    <i class="lar la-times-circle"></i>@lang('Cancel')
</button>
@endif
<a href="{{ route('admin.job.all') }}" class="btn btn-sm btn-outline--primary">
    <i class="la la-undo"></i>@lang('Back')
</a>
@endpush
