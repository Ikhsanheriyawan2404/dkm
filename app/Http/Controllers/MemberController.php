<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Http\Requests\MemberRequest;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:member-module', ['only' => ['index', 'store', 'edit', 'deleteSelected', 'destroy']]);
    }

    public function index()
    {
        if (request()->ajax()) {
            $members = Member::latest()->get();
            return DataTables::of($members)
                    ->addIndexColumn()
                    ->editColumn('image', function (Member $member) {
                        return '<img src="'. $member->takeImage .'" width="100px" class="img-fluid" />';
                    })
                    ->addColumn('checkbox', function ($row) {
                        return '<input type="checkbox" name="checkbox" id="check" class="checkbox" data-id="' . $row->id . '">';
                    })
                    ->addColumn('action', function($row){
                        $btn =

                        '<div class="btn-group">
                            <a class="badge bg-navy dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-primary btn-sm" id="editMember">Edit</a>
                                <form action=" ' . route('members.destroy', $row->id) . '" method="POST">
                                    <button type="submit" class="dropdown-item" onclick="return confirm(\'Apakah yakin ingin menghapus ini?\')">Hapus</button>
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                </form>
                            </div>
                        </div>';
                        return $btn;
                    })
                    ->rawColumns(['checkbox', 'image', 'action'])
                    ->make(true);
        }
        return view('members.index',[
            'title' => 'Data Pengurus',
        ]);
    }

    public function store(MemberRequest $request)
    {
        $request->validated();

        $memberId = request('member_id');
        if ($memberId) {
            $member = member::find($memberId);
            if (request('image')) {
                if ($member->image != 'default.jpg') {
                    Storage::delete($member->image);
                    $image = request()->file('image')->store('img/members');
                }
                $image = request()->file('image')->store('img/members');
            } else {
                $image = $member->image;
            }
        } else {
            $image = request('image') ? request()->file('image')->store('img/members') : 'default.jpg';
        }

        $member = Member::updateOrCreate(
            ['id' => request('member_id')],[
                'name' => request('name'),
                'position' => request('position'),
                'image' => $image,
            ]);
    }

    public function edit(Member $member)
    {
        return response()->json($member);
    }

    public function destroy(Member $member)
    {
        $member->delete();
        toast('Data pengurus berhasil dihapus!','success');
        return back();
    }

    public function deleteSelected()
    {
        $id = request('id');
        Member::whereIn('id', $id)->delete();
        return response()->json(['code'=> 1, 'msg' => 'Data pengurus berhasil dihapus']);
    }
}
