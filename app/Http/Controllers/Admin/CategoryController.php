<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request) {
        $pageTitle  = 'Categories';
        $categories = Category::query();

        if ($request->search) {
            $categories->where('name', 'LIKE', "%$request->search%");
        }

        $categories = $categories->latest()->paginate(getPaginate());
        return view('admin.category.index', compact('pageTitle', 'categories'));
    }

    function filterCategory(Request $request ,$belongs_to) {
        $pageTitle  = 'Categories';
        $categories = Category::query();

        if ($request->search) {
            $categories->where('name', 'LIKE', "%$request->search%");
        }

        $categories = $categories->where('belongs_to', $belongs_to)->paginate(getPaginate());
        return view('admin.category.index', compact('pageTitle', 'categories'));
    }

    public function store(Request $request, $id = 0) {
        $imageValidate = $id ? 'nullable' : 'required';
        $validate = [
            'name'  => 'required|max: 40|unique:categories,name,'.$id,
            'belongs_to'  => 'required',
            'image' => [$imageValidate, 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ];
        $request->validate($validate);
        if($id == 0){
            $category = new Category();
            $notification = 'Category added successfully.';
            $oldImage = null;
        }else{
            $category = Category::findOrFail($id);
            $category->status   = $request->status ? 1 : 0;
            $notification = 'Category updated successfully';
            $oldImage = $category->image;
            
        }
        if ($request->hasFile('image')) {
            try {
                $category->image = fileUploader($request->image, getFilePath('category'), getFileSize('category'),$oldImage);
            } catch (\Exception$e) {
                $notify[] = ['error', 'Image could not be uploaded'];
                return back()->withNotify($notify);
            }

        }

        $category->name = $request->name;
        $category->belongs_to = $request->belongs_to;
        $category->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }
}
