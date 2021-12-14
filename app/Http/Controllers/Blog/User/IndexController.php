<?php

namespace App\Http\Controllers\Blog\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $perms = [
            'manage-posts', 'manage-comments', 'manage-tags',
            'manage-users', 'manage-roles', 'manage-pages'
        ];
        $admin = Auth::user()->hasAnyPerms(...$perms);
        return view('user.index', compact('admin'));
    }
}
