<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobSkill;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class JobSkillController extends Controller
{
    public function index(Request $request) {
        $skills = JobSkill::latest('id')->paginate(getPaginate());
        $pageTitle     = "Job Skills";
        return view('admin.job_skill.index', compact('pageTitle', 'skills'));
    }

    public function store(Request $request, $id = 0) {

        $validate = [
            'name'  => 'required|max: 40|unique:job_categories,name,'.$id,
        ];
        $request->validate($validate);
        if($id == 0){
            $skill = new JobSkill();
            $notification = 'Skill added successfully.';
            $oldImage = null;
        }else{
            $skill = JobSkill::findOrFail($id);
            $notification = 'Skill updated successfully';
        }

        $skill->name = $request->name;
        $skill->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function changeStatus(Request $request,$id){
        JobSkill::where('id',$id)->update(['status'=> $request->status]);
        $notification = 'Status Updated successfully.';
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }
}
