<?php

namespace App\Http\Controllers;

use App\Company;
use App\Imports\UsersImport;
use App\Menu;
use App\Role;
use App\UserMenu;
use Illuminate\Http\Request;
use App\User;
use App\Feature;
use App\UserFeatures;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\KpiType;
use DB;
use Gate;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller{ 

    public function __construct(){
       $this->middleware('auth');
    }

    public function index(Request $request){
        return View('admin.list');
    }

    public function getAllUser(){
        $admins = User::query()->where('id','!=',1)->with('role');
        return Datatables::eloquent($admins)
            ->addColumn('role', function ($data) {
                return isset($data->role) ? $data->role->name : '';
            })
            ->addColumn('action', function ($data) {
                $buttons='';
                $buttons .= '<a class="btn btn-info btn-xs" href="'.url('/admin/'.$data->id.'/edit').'"><i class="fa fa-edit"></i></a>';
                $buttons .= '<button type="button" style="margin-left:5px" class="btn btn-xs btn-danger" id="'.$data->id.'" onclick="destroy(this.id)"><i class="fa fa-trash"></i></button>';
                $buttons .= '<a class="btn btn-success btn-xs" style="margin-left:5px" href="'.url('/admin/password-change',$data->id).'">Change Password</a>';
                return $buttons;
            })
            ->make(true);
    }

    public function create(){
        $depots = [
            "N" => "Jashore", "L" => "Barishal", "B" => "Cumilla", "D" => "Sylhet", "M" => "Gazipur", "G" => "Rangpur", "S" => "Dinajpur", "T" => "Thakurgaon", "I" => "Rajshahi", "F" => "Bogura", "E" => "Mymensingh",
        ];
        //dd($depots);
        $roles = Role::all();
        $companies = Company::all();
        $kpi_types = KpiType::all();
        return view('admin.create',compact('roles','companies','kpi_types','depots'));
    }

    public function edit($id){
        $admin = User::find($id);
        $kpi_types = KpiType::all();
        $roles = Role::all();
        $depots = [
            "N" => "Jashore", "L" => "Barishal", "B" => "Cumilla", "D" => "Sylhet", "M" => "Gazipur", "G" => "Rangpur", "S" => "Dinajpur", "T" => "Thakurgaon", "I" => "Rajshahi", "F" => "Bogura", "E" => "Mymensingh",
        ];
        return view('admin.edit',compact('admin','kpi_types','roles','depots'));
    }

    public function update(Request $request, $id){
        $this->validate($request, [
            'name' => 'required|max:255',
            'username' => 'required|max:20|unique:users,username,'.$id,
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            'kpi_type_id' => 'required',
        ]);

        $admin = User::find($id);

        if (request()->hasFile('image')) {
            $destinationPath = public_path('/signature');
            if($admin->signature != ''  && $admin->signature != null){
                $file_old = $destinationPath.$admin->signature;
                if (file_exists($file_old)){
                    unlink($file_old);
                }
            }
            $file = request()->file('image');
            $fileName = time().'.'.$file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);
        }else{
            $fileName = $admin->signature;
        }


        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->mobile = $request->mobile;
        $admin->designation = $request->designation;
        $admin->username = $request->username;
        $admin->is_active = $request->is_active;
        $admin->kpi_type_id = $request->kpi_type_id;
        $admin->self_bike = $request->self_bike;
        $admin->account_no = $request->account_no;
        $admin->Depot = $request->Depot;
        $admin->spare_parts_permission = $request->spare_parts_permission;
        $admin->signature = $fileName;
        $admin->save();

        Session::flash('success','Successfully Edited');  
        return redirect()->route('admin.index');
    }

    public function changePassword($id){
       return view('admin.change_password');
    }

    public function changePasswordStore(Request $request){
        Validator::make($request->all(), [
            'password' => 'required|min:4|confirmed',
        ]);

        $user = User::find($request->UserId);
        $user->password = bcrypt($request->password);
        $user->save();
        Session::flash('success','Password Change successfully :)');
        return redirect()->route('admin.index');
    }

    public function reset_password_view($id){
          return view('admin.reset_password',compact('id'));
     }

    public function reset_password(Request $request){
          $this->validate($request,[
            'password' => 'required|min:5|confirmed',
          ]);

          if(Auth::check()){
            $obj_user = User::find($request->id);
            $obj_user->password = Hash::make($request->password);
            $obj_user->save();
            Session::flash('success','Successfully Password Reset');
          }else{
             Session::flash('danger','Password Reset Unsuccessfull -- Authentication Failed');
          }

         return redirect('/admin');
    }

     public function check_logged_admin_password_match($admin_password){
          $current_password = Auth::User()->password;           
          if(Hash::check($admin_password, $current_password)){    
            return 1;
          }else{
            return 0;
          }
     }

     public function access_edit($id){
         $features = Feature::all();
         $feature_accessed = UserFeatures::where('user_id',$id)->get();
         $user=User::find($id);

         return view('admin.access_edit',compact('features'))
                    ->with('feature_accessed',$feature_accessed)
                    ->with('id',$user->id)
                    ->with('user_name',$user->name);
     }

     public function access_set(Request $request){
         if($request->status == '1'){
             $result= $this->assign_feature_to_the_user($request);
         }else{
             $result= $this->remove_feature_from_user($request);
         }       
         return $result;
     }

     private function assign_feature_to_the_user($request){
        $feature_exist = UserFeatures::where('user_id',$request->user_id)->where('feature_id',$request->feature_id)->first();
        if($feature_exist){
             return 'Already Exist';
        }else{
          $admin_id  = Auth::User()->id;  
          $feature_new = new UserFeatures;
          $feature_new->user_id = $request->user_id;
          $feature_new->feature_id =$request->feature_id;
          $feature_new->admin_id = $admin_id;
          $feature_new->save();
          return 'Feature Added';
        }
     }

     private function remove_feature_from_user($request){

        $feature_exist =  UserFeatures::where('user_id',$request->user_id)->where('feature_id',$request->feature_id)->first();  
        if($feature_exist){
    
           UserFeatures::destroy($feature_exist->id);
             
           $findUser = User::find($request->user_id);
           \Log::info($findUser->session_id);
           \Session::getHandler()->destroy($findUser->session_id);

           return "Feature Removed";
        }else{
            return "Already Removed";
        }
    }

    public static function isFeatureCheckedMarked($feature_id,$user_id){
        $feature_accessable = UserFeatures::where('user_id',$user_id)->where('feature_id',$feature_id)->first();
        return $feature_accessable ? 1 :  0;
     }

     public static function isAccessable($feature_id){
        if(Auth::User()){
            $admin_id  = Auth::User()->id;
            $feature_accessable = UserFeatures::where('user_id',$admin_id)->where('feature_id',$feature_id)->get();
            ($feature_accessable->first()) ? $result = 1 : $result = 0;
        }else{
            $result = 0;
        }
        return $result;
     }

     public static function getAccessableFeatures(){
        if(Auth::User()){
            $result = UserFeatures::where('user_id',Auth::User()->id)->pluck('feature_id')->toArray();
        }else{
            $result = [];
        }
        return $result;
     }

     public function profile(){
        $admin_id = Auth::user()->id; 
        $user = User::find($admin_id);
        return view('admin.profile',compact('user'));
     }

      public function reset_profile_password_view(){
          return view('admin.reset_profile_password');
     }

     public function reset_profile_password_update(Request $request){
          $this->validate($request,[
                 'password' => 'required|min:5|confirmed',
                ]);
          
           if (Auth::check()){
              $admin_id = Auth::user()->id;
                 if($this->check_logged_admin_password_match($request->admin_password)){                                  
                    $obj_user = User::find($admin_id);
                    $obj_user->password = Hash::make($request->password);
                    $obj_user->save(); 
                    Session::flash('success','Successfully Password Reset'); 
                 }else{
                    Session::flash('danger','Password Reset Unsuccessfull authentication Failed'); 
                 }
            }

           return redirect('/profile');
     }

     public function userImport(Request $request){

         $result = Excel::import(new UsersImport, $request->file);

         Session::flash('success','Successfully Edited');
         return redirect('/admin');
     }

     public function usermanager(){
         $userMenus = \App\Http\Controllers\AdminController::getUserMenu();
         //dd($userMenus);
        $users = User::whereIn('role_id',[1,2])->get();
        return view('setup.user.usermanager',compact('users'));
     }

     public function userMenuPermission($id) {
         $user_menu = UserMenu::where('UserID',$id)->pluck('MenuID')->toArray();
         $all_menu = Menu::orderBy('MenuID','desc')->get();

         return view('setup.user.manage_menu_permission',compact('user_menu','all_menu','id'));
     }

     public function userMenuPermissionStore(Request $request) {
         $userId = $request->UserId;
         $permission = $request->MenuID;

         $sortedPerm = [];
         foreach ($permission as $key => $value) {
             if ($value ) array_push($sortedPerm, $value);
         }
         $current = UserMenu::where('UserID', $userId)->pluck('MenuID')->toArray();
         $inserted = array_diff($sortedPerm, $current);

         foreach ($inserted as $item) {
             UserMenu::create(['UserID' => $userId, 'MenuID' => $item]);
         }

         $remove = array_diff($current, $sortedPerm);
         UserMenu::where('UserID', $userId)->whereIn('MenuID', $remove)->delete();

         Session::flash("success", "Permission Created Successfully !");
         return redirect()->back();
     }

    public static function getUserMenu(){
        if ($data = Session::get('userMenu')) {
            return $data;
        }
        $userId = Auth::user()->id;
        $userMenu = UserMenu::where('UserID', $userId)
            ->with(['menu' => function ($query) {
                $query->orderBy('ReportOrder');
            }])
            ->get()->groupBy('Menu.NavItem')->toArray();
        Session::put('userMenu', $userMenu);
        Session::save();
        return $userMenu;
    }


}