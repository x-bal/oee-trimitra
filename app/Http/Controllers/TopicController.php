<?php

namespace App\Http\Controllers;

use App\Models\Broker;
use App\Models\Machine;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TopicController extends Controller
{
    public function index()
    {
        $title = 'Topic';
        $breadcrumbs = ['Setting', 'Topic'];

        return view('topic.index', compact('title', 'breadcrumbs'));
    }

    function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Topic::orderBy('id', 'asc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('action', function ($row) {
                    $actionBtn = '<a href="#modal-dialog" id="' . $row->id . '" class="btn btn-sm btn-success btn-edit" data-route="' . route('topics.update', $row->id) . '" data-bs-toggle="modal"><i class="fas fa-edit"></i></a> <button type="button" data-route="' . route('topics.destroy', $row->id) . '" class="delete btn btn-danger btn-delete btn-sm"><i class="fas fa-trash"></i></button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|array',
            'topic' => 'required|array',
        ]);

        try {
            DB::beginTransaction();


            foreach ($request->name as $key => $name) {
                Topic::create([
                    "name" => $request->name[$key],
                    "topic" => $request->topic[$key],
                ]);
            }

            DB::commit();

            return back()->with('success', "Topic successfully created");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function show(Topic $topic)
    {
        return response()->json([
            'topic' => $topic
        ]);
    }

    public function update(Request $request, Topic $topic)
    {
        $request->validate([
            'name' => 'required|array',
            'topic' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            $topic->update([
                'name' => $request->name[0],
                'topic' => $request->topic[0],
            ]);

            DB::commit();

            return back()->with('success', "Topic successfully updated");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(Topic $topic)
    {
        try {
            DB::beginTransaction();

            $topic->delete();

            DB::commit();

            return back()->with('success', "Topic successfully deleted");
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    function changeStatus(Request $request, Topic $topic)
    {
        try {
            DB::beginTransaction();

            $topics = Topic::get();

            foreach ($topics as $bro) {
                $bro->update(['is_active' => 0]);
            }

            $topic->update([
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
