<?php

namespace App\Http\Controllers;

use App\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('setup.menu.index');
    }

    public function getAllMenu(){
        $menus = Menu::query();
        return Datatables::eloquent($menus)
            ->addColumn('status', function ($data) {
                $sta = '';
                if ($data->status == 1) {
                    $sta .='<span class="badge badge-success">Active</span>';
                }else{
                    $sta .='<span class="badge badge-danger">InActive</span>';
                }
                return $sta;
            })
            ->addColumn('action', function ($data) {
                $buttons='';
                $buttons .= '<a class="btn btn-info btn-xs" href="'.url('/menus/'.$data->MenuID.'/edit').'"><i class="fa fa-edit"></i></a>';
//                $buttons .= '<button type="button" class="btn btn-xs btn-danger" id="'.$data->MenuID.'" onclick="destroy(this.id)"><i class="fa fa-trash"></i></button>';
                return $buttons;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('setup.menu.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
//            'NavHeader' => 'required',
            'NavItem' => 'required',
            'NavItemIcon' => 'required',
            'NavItemDetails' => 'required',
            'NavItemDetailsIcon' => 'required',
            'Link' => 'required',
            'ReportOrder' => 'required',
        ]);

        $menu = new Menu();
        $menu->NavHeader            = $request->NavHeader;
        $menu->NavItem              = $request->NavItem;
        $menu->NavItemIcon          = $request->NavItemIcon;
        $menu->NavItemDetails       = $request->NavItemDetails;
        $menu->NavItemDetailsIcon   = $request->NavItemDetailsIcon;
        $menu->Link                 = $request->Link;
        $menu->ReportOrder          = $request->ReportOrder;
        $menu->status               = 1;
        $menu->save();

        Session::flash("success", "Menu Created Successfully !");
        return redirect()->route('menus.index');
    }


    public function edit(Menu $menu)
    {
        return view('setup.menu.edit',compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $this->validate($request,[
//            'NavHeader' => 'required',
            'NavItem' => 'required',
            'NavItemIcon' => 'required',
            'NavItemDetails' => 'required',
            'NavItemDetailsIcon' => 'required',
            'Link' => 'required',
            'ReportOrder' => 'required',
        ]);

        $menu->NavHeader          = $request->NavHeader;
        $menu->NavItem            = $request->NavItem;
        $menu->NavItemIcon        = $request->NavItemIcon;
        $menu->NavItemDetails     = $request->NavItemDetails;
        $menu->NavItemDetailsIcon = $request->NavItemDetailsIcon;
        $menu->Link               = $request->Link;
        $menu->ReportOrder        = $request->ReportOrder;
        $menu->status             = 1;
        $menu->save();

        Session::flash("success", "Menu Created Successfully !");
        return redirect()->route('menus.index');
    }

    public function destroy(Menu $menu)
    {
        //
    }
}
