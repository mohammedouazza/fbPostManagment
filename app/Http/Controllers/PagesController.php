<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function update()
    {
        //dd(request()->all());
        $pages = array_slice(array_keys(request()->all()), 2);
        $updated = auth()->user()->activatePages($pages);
        if ($updated) {
            return back()->with('success', 'Pages updated');
        }
        return back()->with('error', 'Something went wrong');
    }
}
