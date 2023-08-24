<?php

namespace App\Http\Controllers;

use App\Models\Line;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class LineController extends Controller
{
    public function index()
    {
        $title = 'Line Process';
        $breadcrumbs = ['Master', 'Line Process'];

        return view('line.index', compact('title', 'breadcrumbs'));
    }

    function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Line::orderBy('id', 'asc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="#modal-dialog" id="' . $row->id . '" class="btn btn-sm btn-success btn-edit" data-route="' . route('lines.update', $row->id) . '" data-bs-toggle="modal"><i class="fas fa-edit"></i></a> <button type="button" data-route="' . route('lines.destroy', $row->id) . '" class="delete btn btn-danger btn-delete btn-sm"><i class="fas fa-trash"></i></button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => "required|string"
        ]);

        try {
            DB::beginTransaction();

            $line = Line::create(["name" => $request->name]);

            DB::commit();

            return back()->with('success', "Line process successfully created");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function show(Line $line)
    {
        return response()->json([
            'line' => $line
        ]);
    }

    public function update(Request $request, Line $line)
    {
        $request->validate([
            'name' => "required|string"
        ]);

        try {
            DB::beginTransaction();

            $line->update(["name" => $request->name]);

            DB::commit();

            return back()->with('success', "Line process successfully updated");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(Line $line)
    {
        try {
            DB::beginTransaction();

            $line->delete();

            DB::commit();

            return back()->with('success', "Line process successfully deleted");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
