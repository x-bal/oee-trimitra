<?php

namespace App\Http\Controllers;

use App\Models\Broker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class BrokerController extends Controller
{
    public function index()
    {
        $title = 'Broker';
        $breadcrumbs = ['Setting', 'Broker'];

        return view('broker.index', compact('title', 'breadcrumbs'));
    }

    function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Broker::orderBy('id', 'asc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('action', function ($row) {
                    $actionBtn = '<a href="#modal-dialog" id="' . $row->id . '" class="btn btn-sm btn-success btn-edit" data-route="' . route('brokers.update', $row->id) . '" data-bs-toggle="modal"><i class="fas fa-edit"></i></a> <button type="button" data-route="' . route('brokers.destroy', $row->id) . '" class="delete btn btn-danger btn-delete btn-sm"><i class="fas fa-trash"></i></button>';
                    return $actionBtn;
                })
                ->editColumn('status', function ($row) {
                    if ($row->is_active == 1) {
                        $checked = "checked";
                    } else {
                        $checked = "";
                    }

                    return '<div class="form-check form-switch">
                    <input class="form-check-input check-status" type="checkbox" id="' . $row->id . '" ' . $checked . '/>
                    </div>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'host' => "required|string",
            'port' => 'required|numeric',
            'wsport' => 'required|numeric',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            Broker::create([
                "host" => $request->host,
                "port" => $request->port,
                "wsport" => $request->wsport,
                "username" => $request->username,
                "password" => $request->password,
            ]);

            DB::commit();

            return back()->with('success', "Broker successfully created");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function show(Broker $broker)
    {
        return response()->json([
            'broker' => $broker
        ]);
    }

    public function update(Request $request, Broker $broker)
    {
        $request->validate([
            'host' => "required|string",
            'port' => 'required|numeric',
            'wsport' => 'required|numeric',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $broker->update([
                "host" => $request->host,
                "port" => $request->port,
                "wsport" => $request->wsport,
                "username" => $request->username,
                "password" => $request->password,
            ]);

            DB::commit();

            return back()->with('success', "Broker successfully updated");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(Broker $broker)
    {
        try {
            DB::beginTransaction();

            $broker->delete();

            DB::commit();

            return back()->with('success', "Broker successfully deleted");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    function changeStatus(Request $request, Broker $broker)
    {
        try {
            DB::beginTransaction();

            $brokers = Broker::get();

            foreach ($brokers as $bro) {
                $bro->update(['is_active' => 0]);
            }

            $broker->update([
                'is_active' => $request->is_active
            ]);

            DB::commit();

            return response()->json([
                'status' => "success"
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => "error",
                'message' => $th->getMessage()
            ]);
        }
    }
}
