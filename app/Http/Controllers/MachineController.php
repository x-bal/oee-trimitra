<?php

namespace App\Http\Controllers;

use App\Models\Line;
use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class MachineController extends Controller
{
    public function index()
    {
        $title = 'Machine';
        $breadcrumbs = ['Master', 'Machine'];
        $lines = Line::get();

        return view('machine.index', compact('title', 'breadcrumbs', "lines"));
    }

    function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Machine::orderBy('id', 'asc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('action', function ($row) {
                    $actionBtn = '<a href="#modal-dialog" id="' . $row->id . '" class="btn btn-sm btn-success btn-edit" data-route="' . route('machines.update', $row->id) . '" data-bs-toggle="modal"><i class="fas fa-edit"></i></a> <button type="button" data-route="' . route('machines.destroy', $row->id) . '" class="delete btn btn-danger btn-delete btn-sm"><i class="fas fa-trash"></i></button>';
                    return $actionBtn;
                })
                ->editColumn('image', function ($row) {
                    return '<img src="' . asset('/storage/' . $row->image) . '" class="img-fluid" alt="" width="50">';
                })
                ->editColumn('line', function ($row) {
                    return $row->line->name;
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => "required|string",
            'line' => 'required|numeric',
            'image' => 'nullable|mimes:jpg,png,jpeg,gif',
            'bottleneck' => "nullable"
        ]);

        try {
            DB::beginTransaction();

            if ($request->file('image')) {
                $image = $request->file('image');
                $imageUrl = $image->storeAs('/machines', Str::slug($request->name . "-" . Str::random(8)) . "." . $image->extension());
            } else {
                $imageUrl = "/machines/default.png";
            }

            Machine::create([
                "name" => $request->name,
                "line_id" => $request->line,
                "image" => $imageUrl,
                "bottleneck" => $request->bottleneck && $request->bottleneck == "on" ? 1 : 0
            ]);

            DB::commit();

            return back()->with('success', "Machine successfully created");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function show(Machine $machine)
    {
        return response()->json([
            'machine' => $machine
        ]);
    }

    public function update(Request $request, Machine $machine)
    {
        $request->validate([
            'name' => "required|string",
            'line' => 'required|numeric',
            'image' => 'nullable|mimes:jpg,png,jpeg,gif',
            'bottleneck' => "nullable"
        ]);

        try {
            DB::beginTransaction();

            if ($request->file('image')) {
                Storage::delete($machine->image);
                $image = $request->file('image');
                $imageUrl = $image->storeAs('/machines', Str::slug($request->name . "-" . Str::random(8)) . "." . $image->extension());
            } else {
                $imageUrl = $machine->image;
            }

            $machine->update([
                "name" => $request->name,
                "line_id" => $request->line,
                "image" => $imageUrl,
                "bottleneck" => $request->bottleneck && $request->bottleneck == "on" ? 1 : 0
            ]);

            DB::commit();

            return back()->with('success', "Machine successfully updated");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(Machine $machine)
    {
        try {
            DB::beginTransaction();

            Storage::delete($machine->image);
            $machine->delete();

            DB::commit();

            return back()->with('success', "Machine successfully deleted");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
