<div class="col-xl-3">
    <div class="dash-sidebar">
        <button class="btn-close sidebar-close d-xl-none shadow-none"></button>
        <ul class="sidebar-menu">

            <li>
                <a href="{{ route('freelancer.home') }}" class="{{ menuActive('freelancer.home') }}"><i class="las la-home"></i> @lang('Dashboard')</a>
            </li>

            @php
                $pendingOrders = App\Models\FreelancerOrder::pending()->where('freelancer_id', authFreelancerId())->count();
                $pendingHires = App\Models\Hiring::pending()->where('freelancer_id', authFreelancerId())->count();
            @endphp

            <li class="{{ menuActive('freelancer.service.*',2) }}">
                <a href="javascript:void(0)"><i class="las la-wallet"></i> @lang('Service') @if($pendingOrders) <span class="text--danger"><i class="la la-exclamation-circle" aria-hidden="true"></i></span> @endif</a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('freelancer.service.create') }}" class="{{ menuActive('freelancer.service.create') }}"><i class="la la-dot-circle"></i> @lang('Create New')</a>
                    </li>

                    <li>
                        <a href="{{ route('freelancer.service.all') }}" class="{{ menuActive('freelancer.service.all') }}"><i class="la la-dot-circle"></i> @lang('All Services')</a>
                    </li>

                    <li>
                        <a href="{{ route('freelancer.service.order.index') }}" class="{{ menuActive('freelancer.service.order.index') }}"><i class="la la-dot-circle"></i> @lang('Orders') @if($pendingOrders) <span class="text--danger"><i class="fas la-exclamation-circle" aria-hidden="true"></i></span>@endif</a>
                    </li>
                    
                </ul>
            </li>
           
            <li class="{{ menuActive('freelancer.jobs.*',2) }}">
                <a href="javascript:void(0)"><i class="las la-user"></i> @lang('Jobs') </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('freelancer.jobs.create') }}" class="{{ menuActive('freelancer.jobs.create') }}"><i class="las la-plus"></i> @lang('Create job')</a>
                    </li>

                    <li>
                        <a href="{{ route('freelancer.jobs.all') }}" class="{{ menuActive('freelancer.jobs.all') }}"><i class="la la-dot-circle"></i> @lang('All Jobs')</a>
                    </li>

                    <li>
                        <a href="{{ route('freelancer.jobs.applications') }}" class="{{ menuActive('freelancer.jobs.applications') }}"><i class="la la-dot-circle"></i> @lang('Job Applications')</a>
                    </li>
                    <li>
                        <a href="{{ route('freelancer.jobs.assign-to-you') }}" class="{{ menuActive('freelancer.jobs.assign-to-you') }}"><i class="la la-dot-circle"></i> @lang('Assign to you')</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('freelancer.hiring.index') }}" class="{{ menuActive('freelancer.hiring*') }}">
                    <i class="las la-list-ol"></i> @lang('Hirings')
                    @if($pendingHires) <span class="text--danger"><i class="fas la-exclamation-circle" aria-hidden="true"></i></span>@endif
                </a>
            </li>

            <li class="{{ menuActive('freelancer.withdraw*',2) }}">
                <a href="javascript:void(0)"><i class="las la-wallet"></i> @lang('Withdraw')</a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('freelancer.withdraw') }}" class="{{ menuActive('freelancer.withdraw') }}"><i class="las la-dot-circle"></i> @lang('Withdraw Money')</a></li>
                    <li><a href="{{ route('freelancer.withdraw.history') }}" class="{{ menuActive('freelancer.withdraw.history') }}"><i class="las la-dot-circle"></i> @lang('Withdrawal History')</a></li>
                </ul>
            </li>
            <li class="{{ menuActive('freelancer.ticket.*',2) }}">
                <a href="javascript:void(0)"><i class="las la-ticket-alt"></i> @lang('Support Ticket')</a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('freelancer.ticket.open') }}" class="{{ menuActive('freelancer.ticket.open') }}"><i class="las la-dot-circle"></i> @lang('Open New Ticket')</a></li>
                    <li><a href="{{ route('freelancer.ticket') }}" class="{{ menuActive('freelancer.ticket') }}"><i class="las la-dot-circle"></i> @lang('My Tickets')</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ route('freelancer.conversation.index', ['', '']) }}" class="{{ menuActive('freelancer.conversation*') }}"><i class="las la-sms"></i> @lang('Conversations')</a>
            </li>
            <li>
                <a href="{{ route('freelancer.transactions') }}" class="{{ menuActive('freelancer.transactions') }}"><i class="las la-exchange-alt"></i> @lang('Transactions')</a>
            </li>
            <li>
                <a href="{{ route('freelancer.profile.setting') }}" class="{{ menuActive('freelancer.profile.setting') }}"><i class="las la-user-alt"></i> @lang('Profile Setting')</a>
            </li>
            <li>
                <a href="{{ route('freelancer.change.password') }}" class="{{ menuActive('freelancer.change.password') }}"><i class="las la-lock-open"></i> @lang('Change Password')</a>
            </li>
            <li>
                <a href="{{ route('freelancer.twofactor') }}" class="{{ menuActive('freelancer.twofactor') }}"><i class="las la-shield-alt"></i> @lang('2FA Security')</a>
            </li>
            <li>
                <a href="{{ route('freelancer.logout') }}"><i class="las la-sign-in-alt"></i> @lang('Logout')</a>
            </li>
        </ul>
    </div>
</div>
