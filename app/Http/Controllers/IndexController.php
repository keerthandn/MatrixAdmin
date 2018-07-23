<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
class IndexController extends Controller
{
    public function index()
    {
    	$productsAll=Product::orderBy('id','DESC')->get();
    	return view('index')->with(compact('productsAll'));
    }
}
