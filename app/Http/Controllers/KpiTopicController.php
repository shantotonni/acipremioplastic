<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\KpiTopic;
use App\KpiType;
use App\KpiGroup;
use App\Http\Requests\KpiTopicStoreRequest;
use App\Http\Requests\KpiTopicUpdateRequest;


class KpiTopicController extends Controller{


    public function __construct(){
       $this->middleware('auth');
    }


    public function index(){
        $kpi_topics = KpiTopic::orderBy('id','Desc')->paginate(20);
        return view("kpi_topic.kpi_topic_list",compact("kpi_topics"));
    }


    public function create(){
        
        $kpi_types=KpiType::all();
        $kpi_groups=KpiGroup::all();
        return view("kpi_topic.kpi_topic_create")
             ->with("kpi_types" ,$kpi_types)
             ->with("kpi_groups" ,$kpi_groups);;
    }


    public function store(KpiTopicStoreRequest $request){

        $kpi_topic=KpiTopic::create($request->all());
        Session::flash("success", "Created Succcessfully !");
        return redirect("/kpi_topic");
    }



    public function edit($id){
        
        $kpi_types=KpiType::all();
        $kpi_groups=KpiGroup::all();
        $kpi_topic = KpiTopic::findOrFail($id);
        return view("kpi_topic.kpi_topic_edit",compact("kpi_topic"))
             ->with("kpi_types" ,$kpi_types)
             ->with("kpi_groups" ,$kpi_groups);;

    }

    public function update(KpiTopicUpdateRequest $request, $id) {

        $kpi_topic=KpiTopic::find($id)->update($request->all());
        Session::flash("success", "Edited Succcessfully !");
        return redirect("/kpi_topic");
    }

    public function show($id){
        $kpi_topic = KpiTopic::find($id);
        return view("kpi_topic.kpi_topic_show",compact("kpi_topic"));
    }


    public function destroy($id){
        $kpi_topic = KpiTopic::findOrFail($id);
        $kpi_topic ->delete();
        Session::flash("success", "Deleted Succcessfully !");
        return redirect("/kpi_topic");
    }

}
