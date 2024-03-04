<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\ContactUs;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
//        $vendors = Vendor::get()->count();
        $users = User::get()->count();
        $categories = Category::get()->count();
//        $contacts_us = ContactUs::get()->count();

        return view('dashboard.dashboard',compact('users','categories'));
    }
}
