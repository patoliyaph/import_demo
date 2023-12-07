<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::query();

            return DataTables::of($data)
                ->addColumn('images', function ($row) {
                    return json_decode($row->images);
                })
                ->make(true);
        }
        return view('products');
    }
}
