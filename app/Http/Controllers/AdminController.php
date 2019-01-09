<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class AdminController extends Controller
{
    /**
     * Конструктор.
     */
    function __construct()
    {
        \Debugbar::disable();
    }
    /**
     * Главная админки.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        return view('admin.index', ['title' => trans('app.home')]);
    }

}
