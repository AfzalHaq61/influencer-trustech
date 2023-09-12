<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class JobCategoryController extends Controller
{
    public function index(Request $request) {
        $pageTitle  = 'All Categories';
        $categories = JobCategory::searchable(['name'])->latest()->withCount('JobSubcategories')->paginate(getPaginate());
        return view('admin.job_category.index', compact('pageTitle', 'categories'));
    }

    // function filterCategory(Request $request ,$belongs_to) {
    //     $pageTitle  = 'Categories';
    //     $categories = Category::query();

    //     if ($request->search) {
    //         $categories->where('name', 'LIKE', "%$request->search%");
    //     }

    //     $categories = $categories->where('belongs_to', $belongs_to)->paginate(getPaginate());
    //     return view('admin.category.index', compact('pageTitle', 'categories'));
    // }

    public function store(Request $request, $id = 0) {
        $validate = [
            'name'  => 'required|max: 40|unique:job_categories,name,'.$id,
        ];
        $request->validate($validate);
        if($id == 0){
            $category = new JobCategory();
            $notification = 'Category added successfully.';
            $oldImage = null;
        }else{
            $category = JobCategory::findOrFail($id);
            $notification = 'Category updated successfully';
            $oldImage = $category->image;
            
        }

        $category->name = $request->name;
        $category->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function changeStatus(Request $request,$id){
        JobCategory::where('id',$id)->update(['status'=> $request->status]);
        $notification = 'Status Updated successfully.';
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }
}
