<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Auth;
use Session;
use DB;
use Hash;
use Validator;
use Carbon\Carbon;


class BerandaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	 
	protected $redirectTo = '/';

	public function __construct()
    {
        //$this->middleware('guest', ['except' => ['logout']]);
    }
	
	
    public function index(Request $request)
    {
		return view('home.index');
    }
    
	protected function guard()
    {
        return Auth::guard('web');
    }
}
    