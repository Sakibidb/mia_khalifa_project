<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Http\Request;
use Str;
use Auth;
use Hash;

class StudentController extends Controller
{
    public function list()

    {
        $data['getRecord'] = User::getStudent();
        $data['header_title'] = 'Student List';
        return view('admin.student.list', $data);
    }
    public function add()

    {
        $data['getClass'] = ClassModel::getClass();
        $data['header_title'] = 'Student List';
        return view('admin.student.add', $data);
    }
    public function insert(Request $request)
    {

        request()->validate([
            'email'=> 'required|email|unique:users',
            'weight'=> 'max:10',
            'height'=> 'max:10',
            'blood_group'=> 'max:10',
            'mobile_number'=> 'max:11|min:8',
            'admission_number'=> 'max:50',
            'caste'=> 'max:50',
            'religion'=> 'max:50',

        ]);

        $student = new User;
        $student->name = trim($request->name);
        $student->last_name = trim($request->last_name);
        $student->admission_number = trim($request->admission_number);
        $student->status = trim($request->status);
        $student->roll_number = trim($request->roll_number);
        $student->class_id = trim($request->class);
        $student->gender = trim($request->gender);
        if(!empty($request->date_of_birth))
        {
            $student->date_of_birth = trim($request->date_of_birth);

        }
        $student->caste = trim($request->caste);
        $student->religion = trim($request->religion);
        $student->mobile_number = trim($request->mobile_number);
        $student->admission_date = trim($request->admission_date);
        if(!empty(($request->profile_pic)))
        {
            $ext = $request->file('profile_pic')->getClientOriginalExtension();
            $file = $request->file('profile_pic');
            $randomStr = date('ymdhis').Str::random(30);
            $filename =strtolower($randomStr).'.'.$ext;
            $file->move('upload/profile/',$filename);

            $student->profile_pic = $filename;

        }
        $student->blood_group = trim($request->blood_group);
        $student->height = trim($request->height);
        $student->weight = trim($request->weight);
        $student->email = trim($request->email);
        $student->password = Hash::make($request->password);
        $student->user_type = 3;
        $student->save();
        return redirect('admin/student/list')->with('success', "Student Successfully Added");
    }
    public function edit($id)
    {
        $data['getRecord'] = User::getSingle($id);
        if(!empty($data['getRecord']))
        {
        $data['getClass'] = ClassModel::getClass();
        $data['header_title'] = 'Student List Edit';
        return view('admin.student.edit', $data);
        }
        else
    {
        abort(404);
    }
        
    }

    public function update($id, Request $request)
    {
        request()->validate([
            'email'=> 'required|email|unique:users,email,'.$id,
            'weight'=> 'max:10',
            'height'=> 'max:10',
            'blood_group'=> 'max:10',
            'mobile_number'=> 'max:11|min:8',
            'admission_number'=> 'max:50',
            'caste'=> 'max:50',
            'religion'=> 'max:50',

        ]);

        $student = User::getSingle($id);;
        $student->name = trim($request->name);
        $student->last_name = trim($request->last_name);
        $student->admission_number = trim($request->admission_number);
        $student->status = trim($request->status);
        $student->roll_number = trim($request->roll_number);
        $student->class_id = trim($request->class);
        $student->gender = trim($request->gender);
        if(!empty($request->date_of_birth))
        {
            $student->date_of_birth = trim($request->date_of_birth);

        }
        $student->caste = trim($request->caste);
        $student->religion = trim($request->religion);
        $student->mobile_number = trim($request->mobile_number);
        $student->admission_date = trim($request->admission_date);
        if(!empty(($request->profile_pic)))
        {
            if(!empty($student->getProfile()))
            {
                unlink('upload/profile/'.$student->profile_pic);
            }
            $ext = $request->file('profile_pic')->getClientOriginalExtension();
            $file = $request->file('profile_pic');
            $randomStr = date('ymdhis').Str::random(30);
            $filename =strtolower($randomStr).'.'.$ext;
            $file->move('upload/profile/',$filename);

            $student->profile_pic = $filename;

        }
        $student->blood_group = trim($request->blood_group);
        $student->height = trim($request->height);
        $student->weight = trim($request->weight);
        $student->email = trim($request->email);

        if(!empty($request->password))
        {
            $student->password = Hash::make($request->password);
        }
        $student->save();
        return redirect('admin/student/list')->with('success', "Student Successfully Updated"); 
    }

    public function delete($id)
    {
        $getRecord = User::getSingle($id);
        if(!empty($getRecord))
        {
            $getRecord ->is_delete = 1;
            $getRecord->delete();
            return redirect('admin/student/list')->with('success', "Class Successfully Deleted");
        }
        else
        {
            abort(404);
        }
        
    }
    
}

