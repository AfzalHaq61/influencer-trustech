<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\FreelancerOrder;
use App\Models\FreelancerService;
use App\Models\ServiceGallery;
use App\Models\Category;
use App\Models\FreelancerTag;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller {

    public function all() {
        $pageTitle = 'All Services';
        $services  = $this->serviceData();
        return view($this->activeTemplate . 'freelancer.service.list', compact('pageTitle', 'services'));
    }

    public function create() {
        $pageTitle = "Create New Service";
        $freelancerFategories = Category::where('belongs_to','freelancer')->get();
        return view($this->activeTemplate . 'freelancer.service.create', compact('pageTitle','freelancerFategories'));
    }

    public function edit($id) {
        $pageTitle = "Update Service";
        $service   = FreelancerService::where('freelancer_id', authFreelancerId())->with('gallery', 'tags')->findOrFail($id);

        $images = [];

        foreach ($service->gallery as $gallery) {
            $img['id']  = $gallery->id;
            $img['src'] = getImage(getFilePath('service') . '/' . $gallery->image);
            $images[]   = $img;
        }

        return view($this->activeTemplate . 'freelancer.service.create', compact('pageTitle', 'service', 'images'));
    }

    public function store(Request $request, $id = 0) {
        $this->validation($request->all(), $id)->validate();
        $freelancer = authFreelancer();
        $general = gs();

        $service = $this->insertService($general, $id);
        $this->insertTag($service, $id);

        if ($id) {
            $oldImages   = $service->gallery->pluck('id')->toArray();
            $imageRemove = array_values(array_diff($oldImages, $request->old ?? []));

            foreach ($imageRemove as $remove) {
                $singleImage = ServiceGallery::find($remove);
                $location    = getFilePath('service');
                fileManager()->removeFile($location . '/' . $singleImage->image);
                fileManager()->removeFile($location . '/thumb_' . $singleImage->image);
                $singleImage->delete();
            }

            $notification = 'Service updated successfully';
        }

        $this->serviceImages($request, $service);

        if (!$id) {
            $adminNotification                = new AdminNotification();
            $adminNotification->freelancer_id = $freelancer->id;
            $adminNotification->title         = 'New service created by ' . $freelancer->username;
            $adminNotification->click_url     = urlPath('admin.service.detail', $service->id);
            $adminNotification->save();
            $notification = 'Service created successfully';
        }

        $notify[] = ['success', $notification];
        return to_route('freelancer.service.all')->withNotify($notify);
    }

    protected function validation(array $data, $id) {

        // $imageValidation = !$id ? 'required' : 'nullable';
        $imageValidation = !$id ? ' ' : 'nullable';

        $validate = Validator::make($data, [
            'category_id'  => 'required|integer|exists:categories,id',
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'price'        => 'required|numeric|gte:0',
            'tags'         => 'required|array|min:1',
            'key_points'   => 'required|array|min:1',
            'key_points.*' => 'required|string|max:255',
            'image'        => [$imageValidation, 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'images'       => 'nullable|array',
            'images.*'     => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ], [
            'key_points.*.required' => 'Key points is required',
        ]);

        return $validate;
    }

    protected function insertService($general, $id) {

        $freelancerId = authFreelancerId();
        $request      = request();

        if ($id) {
            $service  = FreelancerService::where('freelancer_id', $freelancerId)->findOrFail($id);
            $oldImage = $service->image;
        } else {
            $service  = new FreelancerService();
            $oldImage = null;
        }

        $service->freelancer_id = $freelancerId;
        $service->category_id   = $request->category_id;
        $service->title         = $request->title;
        $service->price         = $request->price;
        $service->description   = $request->description;
        $service->key_points    = $request->key_points;
        $service->status        = $general->service_approve == 1 ?? 0;

        if ($request->hasFile('image')) {
            try {
                $service->image = fileUploader($request->image, getFilePath('service'), getFileSize('service'), $oldImage, getFileThumb('service'));
            } catch (\Exception$exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }

        }

        $service->save();
        return $service;
    }

    protected function insertTag($service, $id) {
        $request = request();

        foreach ($request->tags as $tag) {
            $tagExist = FreelancerTag::where('name', $tag)->first();

            if ($tagExist) {
                $tagId[] = $tagExist->id;
            } else {
                $newTag       = new FreelancerTag();
                $newTag->name = $tag;
                $newTag->save();
                $tagId[] = $newTag->id;
            }

        }

        if ($id) {  
            $service->tags()->sync($tagId);
        } else {
            $service->tags()->attach($tagId);
        }

    }

    protected function serviceImages($request, $service) {

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $key => $image) {

                if (isset($request->imageId[$key])) {
                    $singleImage = ServiceGallery::find($request->imageId[$key]);
                    $location    = getFilePath('service');
                    fileManager()->removeFile($location . '/' . $singleImage->image);
                    fileManager()->removeFile($location . '/thumb_' . $singleImage->image);
                    $singleImage->delete();

                    $newImage           = fileUploader($image, getFilePath('service'), getFileSize('service'), null, getFileThumb('service'));
                    $singleImage->image = $newImage;
                    $singleImage->save();
                } else {
                    try {
                        $newImage = fileUploader($image, getFilePath('service'), getFileSize('service'), null, getFileThumb('service'));
                    } catch (\Exception$exp) {
                        $notify[] = ['error', 'Couldn\'t upload your image.'];
                        return back()->withNotify($notify);
                    }

                    $gallery             = new ServiceGallery();
                    $gallery->service_id = $service->id;
                    $gallery->image      = $newImage;
                    $gallery->save();
                }

            }

        }

    }

    public function pending() {
        $pageTitle = 'Pending Services';
        $services  = $this->serviceData('pending');
        return view($this->activeTemplate . 'freelancer.service.list', compact('pageTitle', 'services'));
    }

    public function approved() {
        $pageTitle = 'Approved Services';
        $services  = $this->serviceData('approved');
        return view($this->activeTemplate . 'freelancer.service.list', compact('pageTitle', 'services'));
    }

    public function rejected() {
        $pageTitle = 'Rejected Services';
        $services  = $this->serviceData('rejected');
        return view($this->activeTemplate . 'freelancer.service.list', compact('pageTitle', 'services'));
    }

    protected function serviceData($scope = null) {

        if ($scope) {
            $services = FreelancerService::$scope();
        } else {
            $services = FreelancerService::query();
        }

        $request = request();

        $services = $services->where('freelancer_id', authFreelancerId());

        if ($request->search) {
            $search   = $request->search;
            $services = $services->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")->orWhereHas('category', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                });
            });
        }

        return $services->with('category')->withCount('totalOrder', 'completeOrder')->latest()->paginate(getPaginate());
    }

    public function orders(Request $request, $id) {
        $pageTitle = 'Service Order List';

        $service = FreelancerService::approved()->where('freelancer_id', authFreelancerId())->findOrFail($id);
        $orders  = FreelancerOrder::where('service_id', $service->id);

        $request = request();

        if ($request->search) {
            $search = request()->search;
            $orders = $orders->where(function ($q) use ($search) {
                $q->where('order_no', $search)->orWhereHas('user', function ($query) use ($search) {
                    $query->where('username', $search);
                });
            });
        }

        $orders = $orders->with('user')->latest()->paginate(getPaginate());

        return view($this->activeTemplate . 'freelancer.order.list', compact('pageTitle', 'orders'));
    }

}
