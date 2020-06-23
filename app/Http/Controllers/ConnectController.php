<?php

namespace App\Http\Controllers;

use App\Connectable;

class ConnectController extends Controller
{
    use Connectable;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('connect.index');
    }

    public function reload()
    {
        $this->getPages();

        return back();
    }

    public function logout()
    {
        $facebookUserDeleted = auth()->user()->facebookUser->delete();
        if ($facebookUserDeleted) {
            foreach (auth()->user()->pages as $page) {
                $page->posts()->delete();
                $page->delete();
            }
            return back()->with('success', 'Facebook account disconnected');
        }
    }
}
