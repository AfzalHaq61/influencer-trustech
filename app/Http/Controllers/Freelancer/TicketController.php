<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Traits\SupportTicketManager;

class TicketController extends Controller
{
    use SupportTicketManager;

    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
        $this->layout = 'frontend';


        $this->middleware(function ($request, $next) {
            $this->user = authFreelancer();
            if ($this->user) {
                $this->layout = 'master';
            }
            return $next($request);
        });

        $this->redirectLink = 'freelancer.ticket.view';
        $this->userType     = 'freelancer';
        $this->column       = 'freelancer_id';
    }
}
