<?php

namespace App\Http\Controllers;

use App\Section;
use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TopicController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        $topics = Topic::with('section')->orderBy('created_at','desc')->paginate(10);
        return view('topic.list',compact('topics'));
    }

    public function create()
    {
        $sections = Section::all();
        return view('topic.create',compact('sections'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'section_id' => 'required',
            'code' => 'required'
        ]);

        $topic = new Topic();
        $topic->name = $request->name;
        $topic->code = $request->code;
        $topic->section_id = $request->section_id;
        $topic->save();

        Session::flash("success", "Topic Created Successfully !");
        return redirect()->route('topics.index');
    }

    public function show(Topic $topic)
    {
        //
    }

    public function edit(Topic $topic)
    {
        $sections = Section::all();
        return view('topic.edit',compact('topic','sections'));
    }

    public function update(Request $request, Topic $topic)
    {
        $this->validate($request,[
            'name' => 'required',
            'section_id' => 'required',
            'code' => 'required'
        ]);

        $topic->name = $request->name;
        $topic->code = $request->code;
        $topic->section_id = $request->section_id;
        $topic->save();

        Session::flash("success", "Topic Updated Successfully !");
        return redirect()->route('topics.index');
    }


    public function destroy(Topic $topic)
    {
        $topic->delete();
        return response()->json(['msg' => 'Data deleted successfully'], 200);
    }
}
