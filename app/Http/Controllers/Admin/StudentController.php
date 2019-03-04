<?php

namespace App\Http\Controllers\Admin;

use App\Batch;
use App\Course;
use App\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::latest()->paginate(15);
        return view('admin.student.index',compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courses = Course::pluck("course_name","id");
        return view('admin.student.create',compact('courses'));
    }

    public function getStateList(Request $request)
    {
        $batch = Batch::where("course_id",$request->course_id)
            ->pluck("batch_name","id");
        return response()->json($batch);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'student_name'         => 'required',
            'father_name'        => 'required',
            'mother_name'        => 'required',
            'email'             => 'required|unique:students',
            'present_address'    => 'required',
            'permanent_address'  => 'required',
            'mobile'             => 'required|unique:students|regex:/(01)[0-9]{9}/',
            'gender'            => 'required',
            'nationality'       => 'required',
            'date_of_birth'     => 'required',
            'blood_group'       => 'required',
            'religion'       => 'required',
            'institute'       => 'required',
            'national_id'       => 'required',
            'guardians_phone'       => 'required|regex:/(01)[0-9]{9}/',
            'payment'       => 'required|integer',
            'course'    => 'required',
            'batch'    => 'required'

        ]);
        if ($request->hasFile('image')) {
            //file name with extension
            $fileNameWithExt = $request->file('image')->getClientOriginalName();
            //get just file name
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            //GET JUST EXT
            $extension = $request->file('image')->getClientOriginalExtension();
            //file name to store
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;

            //Upload Image
            $path = $request->file('image')->storeAs('public/student/', $fileNameToStore);

        } else {
            $fileNameToStore = "default.png";
        }
        $student = new Student();
        $student->student_name = $request->student_name;
        $student->course_id = $request->course;
        $student->batch_id = $request->batch;
        $student->father_name = $request->father_name;
        $student->mother_name = $request->mother_name;
        $student->email = $request->email;
        $student->present_address = $request->present_address;
        $student->permanent_address = $request->permanent_address;
        $student->mobile = $request->mobile;
        $student->gender = $request->gender;
        $student->nationality = $request->nationality;
        $student->date_of_birth = $request->date_of_birth;
        $student->blood_group = $request->blood_group;
        $student->religion = $request->religion;
        $student->institute = $request->institute;
        $student->national_id = $request->national_id;
        $student->guardians_phone = $request->guardians_phone;
        $student->payment = $request->payment;
        $student->image = $fileNameToStore;
        $student->save();
        $notification = array(
            'ttitle' => 'Student Successfully Added',
            'tmsg' => 'You have added a Student',
            'ticon' => 'success',
        );
        return back()->with($notification);



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function delete(Request $request)
    {
        Student::find ( $request->id )->delete ();
        $picture=$request->picture;
        $url="storage/student/";
        $image_path=$url.$picture;
        if(file_exists( $image_path)){
            unlink($image_path);
        }
        return response ()->json ();
    }
}