@extends($activeTemplate . 'layouts.master')
@section('content')
<div class="d-flex justify-content-between align-items-center position-relative mb-4 flex-wrap gap-4">
    <span class="filter-toggle btn btn--base btn--sm h-100 d-none"> <i class="i las la-bars"></i></span>
    <div class="d-flex justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-start flex-wrap gap-2">
            <a class="btn btn--outline-custom {{ menuActive('freelancer.service.order.index') }}" aria-current="page" href="{{ route('freelancer.service.order.index') }}">@lang('All')</a>
            <a class="btn btn--outline-custom {{ menuActive('freelancer.service.order.pending') }}" href="{{ route('freelancer.service.order.pending') }}">@lang('Pending') ({{ $pendingOrder??0 }})</a>
            <a class="btn btn--outline-custom {{ menuActive('freelancer.service.order.inprogress') }}" href="{{ route('freelancer.service.order.inprogress') }}">@lang('Inprogessed')</a>
            <a class="btn btn--outline-custom {{ menuActive('freelancer.service.order.jobDone') }}" href="{{ route('freelancer.service.order.jobDone') }}">@lang('Job Done')</a>
            <a class="btn btn--outline-custom {{ menuActive('freelancer.service.order.completed') }}" href="{{ route('freelancer.service.order.completed') }}">@lang('Completed')</a>
            <a class="btn btn--outline-custom {{ menuActive('freelancer.service.order.reported') }}" href="{{ route('freelancer.service.order.reported') }}">@lang('Reported')</a>
            <a class="btn btn--outline-custom {{ menuActive('freelancer.service.order.cancelled') }}" href="{{ route('freelancer.service.order.cancelled') }}">@lang('Cancelled')</a>
        </div>
        <form action="" class="ms-auto service-search-form">
            <div class="input-group">
                <input type="text" name="search" class="form-control form--control" value="{{ request()->search }}" placeholder="@lang('Search here')">
                <button class="input-group-text bg--base border-0 text-white px-4">
                    <i class="las la-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>
<div class="row mt-2">
    <div class="col-lg-12">
        <table class="table--responsive--lg table">
            <thead>
                <tr>
                    <th>@lang('Order Number')</th>
                    <th>@lang('Username')</th>
                    <th class="text-center">@lang('Amount | Delivery')</th>
                    @if (request()->routeIs('freelancer.service.order.index'))
                    <th>@lang('Status')</th>
                    @endif
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td data-label="@lang('Order Number')">
                        <span>{{ $order->order_no }}</span>
                    </td>

                    <td data-label="@lang('User')">
                        <span class="fw-bold">{{ __(@$order->user->username) }}</span>
                    </td>

                    <td data-label="@lang('Amount | Delivery')" class="text-center">
                        <div>
                            <span class="fw-bold">{{ __($general->cur_sym) }}{{ showAmount($order->amount) }}</span> <br>
                            {{ $order->delivery_date }}
                        </div>
                    </td>

                    @if (request()->routeIs('freelancer.service.order.index'))
                    <td data-label="@lang('Status')">
                        @php echo $order->statusBadge @endphp
                    </td>
                    @endif

                    <td data-label="@lang('Action')">
                        <div class="d-flex flex-wrap gap-2 justify-content-end">
                            <a href="{{ route('freelancer.service.order.detail',$order->id) }}" class="btn btn--sm btn--outline-base">
                                <i class="las la-desktop"></i> @lang('Detail')
                            </a>
                            <a href="{{ route('freelancer.service.order.conversation.view',$order->id) }}" class="btn btn--sm btn--outline-info">
                                <i class="las la-sms"></i> @lang('Chat')
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="justify-content-center text-center" colspan="100%">
                        <i class="la la-4x la-frown"></i>
                        <br>
                        {{ __($emptyMessage) }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $orders->links() }}
    </div>
</div>
@endsection