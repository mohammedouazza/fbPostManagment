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
        auth()->user()->activatePages($pages);
        return back();
        /*foreach (request()->all() as $key => $value) {
            if ($key != '_method' && $key != '_token') {
                dd(Page::findOrFail($key));
            }
        }*/
    }
}
