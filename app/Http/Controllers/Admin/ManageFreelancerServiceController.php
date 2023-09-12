<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FreelancerService;
use Illuminate\Http\Request;

class ManageFreelancerServiceController extends Controller {
    public function index() {
        $pageTitle = 'All Services';
        $services  = $this->serviceData();
        return view('admin.service.freelancer_list', compact('pageTitle', 'services'));
    }

    public function pending() {
        $pageTitle = 'Pending Services';
        $services  = $this->serviceData('pending');
        return view('admin.service.freelancer_list', compact('pageTitle', 'services'));
    }

    public function approved() {
        $pageTitle = 'Approved Services';
        $services  = $this->serviceData('approved');
        return view('admin.service.freelancer_list', compact('pageTitle', 'services'));
    }

    public function rejected() {
        $pageTitle = 'Rejected Services';
        $services  = $this->serviceData('rejected');
        return view('admin.service.freelancer_list', compact('pageTitle', 'services'));
    }

    public function expired() {
        $pageTitle = 'Expired Services';
        $services  = $this->serviceData('expired');
        return view('admin.service.freelancer_list', compact('pageTitle', 'services'));
    }

    protected function serviceData($scope = null) {

        if ($scope) {
            $services = FreelancerService::$scope();
        } else {
            $services = FreelancerService::query();
        }

        $request = request();

        if ($request->search) {

            $search   = $request->search;
            $services = $services->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")->orWhereHas('freelancer', function ($freelancer) use ($search) {
                    $freelancer->where('username', 'like', "%$search%");
                })->orWhereHas('category', function ($category) use ($search) {
                    $category->where('name', 'like', "%$search%");
                });
            });

        }

        return $services->with('freelancer', 'category')->withCount('totalOrder', 'completeOrder')->orderBy('id', 'desc')->paginate(getPaginate());
    }

    public function detail($id) {
        $pageTitle = 'Service Detail';
        $service   = FreelancerService::with('freelancer', 'category', 'tags', 'gallery')->findOrFail($id);
        return view('admin.service.freelancer_detail', compact('pageTitle', 'service'));
    }

    public function status(Request $request, $id) {
        $request->validate([
            'status'         => 'required|integer|in:1,2',
            'admin_feedback' => 'nullable|string',
        ]);

        $service                 = FreelancerService::with('freelancer')->findOrFail($id);
        $service->status         = $request->status;
        $service->admin_feedback = $request->admin_feedback;
        $service->save();

        $freelancer = $service->freelancer;
        $general    = gs();

        if ($request->status == 1) {
            notify($freelancer, 'SERVICE_APPROVE', [
                'username'     => $freelancer->username,
                'title'        => $service->title,
                'currency'     => $general->cur_text,
                'created_at'   => showDateTime($service->created_at),
                'post_balance' => showAmount($freelancer->balance),
            ]);
            $notification = 'Service approved successfully';
        } else {
            notify($freelancer, 'SERVICE_REJECT', [
                'username'       => $freelancer->username,
                'title'          => $service->title,
                'currency'       => $general->cur_text,
                'created_at'     => showDateTime($service->created_at),
                'post_balance'   => showAmount($freelancer->balance),
                'admin_feedback' => $request->admin_feedback,
            ]);
            $notification = 'Service rejected successfully';
        }

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

}
