<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    // Temporary empty controller - redirect to admin
    public function __call($method, $parameters)
    {
        $adminController = new \App\Http\Controllers\Admin\NewsController();
        return call_user_func_array([$adminController, $method], $parameters);
    }
}