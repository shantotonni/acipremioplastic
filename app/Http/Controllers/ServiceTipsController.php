<?php

namespace App\Http\Controllers;

use App\CustomerToken;
use App\ServiceTips;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

class ServiceTipsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function index(){
        $tips = ServiceTips::orderBy('id','Desc')->paginate(20);
        return view("tips.list",compact("tips"));
    }

    public function create(){
        return view("tips.create");;
    }

    public function store(Request $request){

        $this->validate($request,[
            "title"=>"required|max:191",
            "type"=>"required|max:191",
            "description"=>"nullable|max:200",
            "video_link"=>"required",
            "is_active"=>"required|boolean",
        ]);

        $tips = new ServiceTips();
        $tips->title = $request->title;
        $tips->type = $request->type;
        $tips->description = $request->description;

        if ($request->hasFile('image')) {
            $image       = $request->file('image');
            $filename    = time().".". $image->getClientOriginalExtension();
            $image_resize = Image::make($image->getRealPath());
            $image_resize->save(public_path('/tips_images/' .$filename));
            $tips->image = $filename;
        }

        $tips->video_link = $request->video_link;
        $tips->is_active = $request->is_active;
        $tips->save();

//        $chunks = CustomerToken::where('firebase_token','!=',null)->pluck('firebase_token')->chunk(1000);
//        foreach ($chunks as $chunk) {
//            $tokens = array_values($chunk->toArray());
//            FirebaseNotification::push_notification($tokens,"New Promotion",$promotion->header);
//            sleep(1);
//        }

        // $tokens = CustomerToken::where('firebase_token','!=',null)->pluck('firebase_token');
        // FirebaseNotification::push_notification($tokens,"New Promotion",$tips->header);

        Session::flash("success", "Created Successfully !");
        return redirect()->route('tips.index');
    }

    private function firebase_notification($promotion=null){
        $customer_tokens = CustomerToken::where('firebase_token','!=',null)->pluck('firebase_token');
        $server_key = 'AAAApQJqMHs:APA91bF9tP2h3mf4q1jcNc_tXuR1iwf55uG9w3YiABK8ER1FQBIizo7L2SCrDHC9Rf3DB8gQCa66v-qzbaBj-Ljz5RBfwrlSjxY2P-rL9oRAr0A3fVlMkxFbgeimeMFkhH03pxsSHSLR';


        //   $msg = array
        //   (
        //         'body'  => 'body msg',
        //         'title' => 'title',
        //         'icon'  => 'myicon',/*Default Icon*/
        //         'sound' => 'mySound'/*Default sound*/
        //  );
        // $fields = array
        //     (
        //     'to'            => $customer_tokens,
        //     'notification'  => $msg
        //     );
        // $headers = array
        //     (
        //     'Authorization: key=' .$server_key,
        //     'Content-Type: application/json'
        //     );
        // #Send Reponse To FireBase Server
        // $ch = curl_init();
        // curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        // curl_setopt( $ch,CURLOPT_POST, true );
        // curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        // curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        // curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        // curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );

        // $result = curl_exec ( $ch );
        // echo "<pre>";print_r($result);exit;
        // curl_close ( $ch );



        //   $url = 'https://fcm.googleapis.com/fcm/send';
        //   //$url = 'https://iid.googleapis.com/iid/v1:batchAdd';
        //   //$url = "https://iid.googleapis.com/iid/v1:batchRemove";
        //   $fields['registration_tokens'] = $customer_tokens;
        //   //$fields['to'] = '/topics/my-app';
        //   $headers = array(
        //   'Content-Type:application/json',
        //       'Authorization:key='.$server_key
        //   );

        //   $msg = array
        // (
        //     'to'=>$device_id,
        //     'notification' => array('body'=>$message,'title'=>$title,
        //         'click_action'=>'MY_ACTIVITY_1','sound'=>'tone'),
        //     'data' => array('message'=>$message,'title'=>$title)

        // );

        //   $ch = curl_init();
        //   curl_setopt($ch, CURLOPT_URL, $url);
        //   curl_setopt($ch, CURLOPT_POST, true);
        //   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        //   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        //   $result = curl_exec($ch);
        //   curl_close($ch);

    }

    function push_notification_android($tokens,$title,$msg_body,$customParam=null) {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $api_key = 'AAAApQJqMHs:APA91bF9tP2h3mf4q1jcNc_tXuR1iwf55uG9w3YiABK8ER1FQBIizo7L2SCrDHC9Rf3DB8gQCa66v-qzbaBj-Ljz5RBfwrlSjxY2P-rL9oRAr0A3fVlMkxFbgeimeMFkhH03pxsSHSLR';
        // $messageArray = array();
        // $messageArray["notification"] = array (
        //     'title' => $title,
        //     'body' => $msg,
        //     'message' => $msg,
        //     'customParam' => $customParam,
        // );

        $msg = array
        (
            'body'  => $msg_body,
            'title' => $title,
            'icon'  => 'myicon',/*Default Icon*/
            'sound' => 'mySound'/*Default sound*/
        );

        $fields = array(
            'registration_ids' => $tokens,
            //'data' => $messageArray,
            'notification'  => $msg
        );
        $headers = array(
            'Authorization: key=' . $api_key, //GOOGLE_API_KEY
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            echo 'Android: Curl failed: ' . curl_error($ch);
        }
        // Close connection
        curl_close($ch);

        echo "<pre>";print_r($result);
        exit;

        return $result;
    }

    public function edit($id){
        $tip = ServiceTips::findOrFail($id);
        return view("tips.edit",compact("tip"));;

    }

    public function update(Request $request, $id) {

        $tip = ServiceTips::findOrFail($id);
        $tip->title = $request->title;
        $tip->type = $request->type;
        $tip->description = $request->description;

        if ($request->file('image')!=null) {

            $image       = $request->file('image');
            $filename    = time().".". $image->getClientOriginalExtension();
            $image_resize = Image::make($image->getRealPath());
            //$image_resize->resize(250, 250);
            $image_resize->save(public_path('/tips_images/' .$filename));
            $tip->image = $filename;
        }
        $tip->video_link = $request->video_link;

        $tip->is_active = $request->is_active;
        $tip->save();

        Session::flash("success", "Edited Successfully !");
        return redirect()->route('tips.index');
    }

    public function show($id){
        $tip = ServiceTips::find($id);
        return view("tips.show",compact("tip"));
    }

    public function destroy($id){
        $tip = ServiceTips::findOrFail($id);
        $tip ->delete();
        Session::flash("success", "Deleted Successfully !");
        return response()->json([
           'status' =>1,
           'msg' =>'Data Deleted Successfully'
        ]);
    }
}
