<?php

namespace App\Controllers;

class Legal extends BaseController
{
    public function termsOfService()
    {
        return view('legal/terms_of_service');
    }

    public function privacyPolicy()
    {
        return view('legal/privacy_policy');
    }
}

