<?php

namespace App\Http\Controllers;

use App\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PushNotificationController extends Controller
{
    public function index()
    {
        return view('push-notification.index');
    } 

    public function sendNotification(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'message' => 'required',
            'image' => 'required',
        ]);
        
        $fileName = time().'.'.$request->image->extension();  
   
        $request->image->move(public_path('uploads'), $fileName);
        // dd(URL::to('/').'/uploads/'.$fileName);

        $pushNotification = new PushNotification;
        $pushNotification->title = $request->title;
        $pushNotification->message = $request->message;
        $pushNotification->image = $fileName;
        $pushNotification->save();

        // API access key from Google API's Console
        define( 'API_ACCESS_KEY', 'AAAAtd5hy4c:APA91bG2xc7bfpb4CaK5NveNjaqNpmBIhc-xkZwvRMlUdtMOuiW8BEVM--AOlXobwUwK2JOp1agKUl99tvyqv5rpkXlxSqLg8ameF-DjQLyTmBch8ZUcxWhYnFn8LX12dG99u-TjZNGo' );

        // prep the bundle
        $msg = array
        (
            'title'     => $request->title,
            'body'   => $request->message,
            'image'     => URL::to('/').'/uploads/'.$fileName,
            // 'image'     => 'http://116.68.205.71:9204/motor-service/product_image/1622364241.jpg',
        );

        $fields = array
        (
            'to'  => '/topics/promotions',
            'notification' => $msg
        );

        $headers = array
        (
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        echo $result;

        return back()->with('success','Notification successfully sent.');
    
    }
}
