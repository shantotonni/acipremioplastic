<?php

namespace App\Observers;

use App\Company;
use Log;

class CompanyObserver
{
    /**
     * Handle the company "created" event.
     *
     * @param  \App\Company  $company
     * @return void
     */

    public function creating(Company $company){
        Log::info("Company Creating..");

    }
    public function created(Company $company){
        Log::info("Company Created");

    }

    /**
     * Handle the company "updated" event.
     *
     * @param  \App\Company  $company
     * @return void
     */

    public function updating(Company $company){
        Log::info("Company Updating");
    }
    
    public function updated(Company $company){
        Log::info("Company Updated");
    }

    /**
     * Handle the company "deleted" event.
     *
     * @param  \App\Company  $company
     * @return void
     */
    public function deleted(Company $company)
    {
        Log::info("Company Deleted");
    }

    /**
     * Handle the company "restored" event.
     *
     * @param  \App\Company  $company
     * @return void
     */
    public function restored(Company $company)
    {
        //
    }

    /**
     * Handle the company "force deleted" event.
     *
     * @param  \App\Company  $company
     * @return void
     */
    public function forceDeleted(Company $company)
    {
        //
    }
}
