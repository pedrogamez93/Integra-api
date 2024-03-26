<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Nova\Tests\Fixtures\Post;

class NewController extends Controller
{
    public function index()
    {
        $home = Post::all();
        return $home;
    }
}
