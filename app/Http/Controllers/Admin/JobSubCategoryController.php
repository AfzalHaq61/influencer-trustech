<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use App\Models\JobSubCategory;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class JobSubCategoryController extends Controller
{
    public function index(Request $request) {
        $subcategories = JobSubCategory::latest('id')->with('jobCategory')->filter(['job_category_id'])->paginate(getPaginate());
        $pageTitle     = "Job Subcategories";
        $categories    = JobCategory::active()->get();
        // $countries     = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.job_subcategory.index', compact('pageTitle', 'categories','subcategories'));
    }

    public function store(Request $request, $id = 0) {
     
        $imageValidate = $id ? 'nullable' : 'required';
        $validate = [
            'name'  => 'required|max: 40|unique:categories,name,'.$id,
            'category_id'  => 'required',
            'image' => [$imageValidate, 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ];
        $request->validate($validate);

        if ($id) {
            $subcategory  = JobSubCategory::findOrFail($id);
            $notification = 'Subcategory updated successfully';
            $oldImage = $subcategory->image;
        } else {
            $subcategory  = new JobSubCategory();
            $notification = 'Subcategory added successfully';
            $oldImage = null;
        }
        
        if ($request->hasFile('image')) {
            try {
                $subcategory->image = fileUploader($request->image, getFilePath('category'), getFileSize('category'),$oldImage);
            } catch (\Exception$e) {
                $notify[] = ['error', 'Image could not be uploaded'];
                return back()->withNotify($notify);
            }

        }
        $subcategory->name = $request->name;
        $subcategory->job_category_id = $request->category_id;
        $subcategory->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function changeStatus(Request $request,$id)
    {
        JobSubCategory::where('id',$id)->update(['status'=> $request->status]);
        $notification = 'Status Updated successfully.';
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }
}
