<?php

namespace App\Http\Controllers;

use App\Models\Line;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index()
    {
        $title = 'Products';
        $breadcrumbs = ['Master', 'Products'];
        $lines = Line::get();

        return view('product.index', compact('title', 'breadcrumbs', 'lines'));
    }

    function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::orderBy('id', 'asc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('action', function ($row) {
                    $actionBtn = '<a href="#modal-dialog" id="' . $row->id . '" class="btn btn-sm btn-success btn-edit" data-route="' . route('products.update', $row->id) . '" data-bs-toggle="modal"><i class="fas fa-edit"></i></a> <button type="button" data-route="' . route('products.destroy', $row->id) . '" class="delete btn btn-danger btn-delete btn-sm"><i class="fas fa-trash"></i></button>';
                    return $actionBtn;
                })
                ->editColumn('line', function ($row) {
                    return $row->line->name;
                })
                ->editColumn('image', function ($row) {
                    return '<img src="' . asset('/storage/' . $row->image) . '" class="img-fluid" alt="" width="50">';
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'line' => "required|numeric",
            'part_code' => "required|string",
            'product_code' => "required|string",
            'product_name' => "required|string",
            'stdspeed' => "required|numeric",
            'pcs' => "required|numeric",
            'image' => 'nullable|mimes:jpg,png,jpeg,gif',
        ]);

        try {
            DB::beginTransaction();

            if ($request->file('image')) {
                $image = $request->file('image');
                $imageUrl = $image->storeAs('/products', Str::slug($request->product_name . "-" . Str::random(8)) . "." . $image->extension());
            } else {
                $imageUrl = "/products/default.png";
            }

            Product::create([
                'line_id' => $request->line,
                'art_code' => $request->part_code,
                'product_code' => $request->product_code,
                'name' => $request->product_name,
                'stdspeed' => $request->stdspeed,
                'pcskarton' => $request->pcs,
                'image' => $imageUrl
            ]);

            DB::commit();

            return back()->with('success', "Product successfully created");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function show(Product $product)
    {
        return response()->json([
            'product' => $product
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'line' => "required|numeric",
            'part_code' => "required|string",
            'product_code' => "required|string",
            'product_name' => "required|string",
            'stdspeed' => "required|numeric",
            'pcs' => "required|numeric",
            'image' => 'nullable|mimes:jpg,png,jpeg,gif',
        ]);

        try {
            DB::beginTransaction();

            if ($request->file('image')) {
                Storage::delete($product->image);
                $image = $request->file('image');
                $imageUrl = $image->storeAs('/prod$products', Str::slug($request->name . "-" . Str::random(8)) . "." . $image->extension());
            } else {
                $imageUrl = $product->image;
            }

            $product->update([
                'line_id' => $request->line,
                'art_code' => $request->part_code,
                'product_code' => $request->product_code,
                'name' => $request->product_name,
                'stdspeed' => $request->stdspeed,
                'pcskarton' => $request->pcs,
                'image' => $imageUrl
            ]);

            DB::commit();

            return back()->with('success', "Product successfully updated");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            Storage::delete($product->image);
            $product->delete();

            DB::commit();

            return back()->with('success', "Product successfully deleted");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
