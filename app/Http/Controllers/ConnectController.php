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
}
