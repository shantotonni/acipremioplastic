<?php

namespace App\Providers;

use App\Model\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $project_id = config('app.project_id');

        $categories = Category::with(['subcategory','products'])->where('CategoryStatus','Y')->orderBy('Order', 'asc')->where('ProjectID',$project_id)->get();
        View::share('categories',$categories);
    }
}
