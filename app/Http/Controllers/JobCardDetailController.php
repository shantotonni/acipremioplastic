<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\JobCardDetail;
use App\JobCard;
use App\User;
use App\Http\Requests\JobCardDetailStoreRequest;
use App\Http\Requests\JobCardDetailUpdateRequest;


class JobCardDetailController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $job_card_details = JobCardDetail::orderBy('id','Desc')->paginate(20);
        return view("job_card_detail.job_card_detail_list",compact("job_card_details"));
    }


    public function create(){
        
        $job_cards=JobCard::all();
        $users=User::all();
        return view("job_card_detail.job_card_detail_create")
             ->with("job_cards" ,$job_cards)
             ->with("users" ,$users);;
    }


    public function store(JobCardDetailStoreRequest $request){

        $job_card_detail= new JobCardDetail;
        $job_card_detail->job_card_id=$request->job_card_id;
        $job_card_detail->user_id=$request->user_id;
        $job_card_detail->save();

        Session::flash("success", "Created Succcessfully !");
        return redirect("/job_card_detail");
    }



    public function edit($id){
        
        $job_cards=JobCard::all();
        $users=User::all();
        $job_card_detail = JobCardDetail::findOrFail($id);
        return view("job_card_detail.job_card_detail_edit",compact("job_card_detail"))
             ->with("job_cards" ,$job_cards)
             ->with("users" ,$users);;

    }

    public function update(JobCardDetailUpdateRequest $request, $id) {

        $job_card_detail=JobCardDetail::findOrFail($id);
        $job_card_detail->job_card_id=$request->job_card_id;
        $job_card_detail->user_id=$request->user_id;
        $job_card_detail->save();

        Session::flash("success", "Edited Succcessfully !");
        return redirect("/job_card_detail");
    }

    public function show($id){
        $job_card_detail = JobCardDetail::find($id);
        return view("job_card_detail.job_card_detail_show",compact("job_card_detail"));
    }


    public function destroy($id){
        $job_card_detail = JobCardDetail::findOrFail($id);
        $job_card_detail ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/job_card_detail");
    }

}
