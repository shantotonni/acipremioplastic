<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TermsAndPrivacyController extends Controller
{
    public function privacyPolicy()
    {
        return view('privacy_terms.privacy');
    }
    
    public function termsAndConditions()
    {
        return view('privacy_terms.terms');
    }
}
