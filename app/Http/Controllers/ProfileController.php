<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:profile-module', ['only' => ['index', 'store', 'edit']]);
    }

    public function index()
    {
        if (request()->ajax()) {
            $profiles = Profile::latest()->get();
            return DataTables::of($profiles)
                    ->addIndexColumn()
                    ->editColumn('image', function (Profile $profile) {
                        return '<img src="'. $profile->takeImage .'" width="100px" class="img-fluid">';
                    })
                    ->addColumn('action', function($row){
                        $btn =

                        '<a class="badge badge-sm bg-navy" href="javascript:void(0)" data-id="'.$row->id.'" id="editProfile" data-toggle="tooltip" data-placement="top" title="Edit Data"><i class="fa fa-pencil-alt"></i></a>';
                        return $btn;
                    })
                    ->rawColumns(['checkbox', 'image', 'action'])
                    ->make(true);
        }
        return view('profiles.index', [
            'title' => 'Data Profil',
        ]);
    }

    public function edit(Profile $profile)
    {
        return response()->json($profile);
    }

    public function store(ProfileRequest $request)
    {
        $request->validated();

        $profileId = request('profile_id');
        if ($profileId) {
            $profile = Profile::find($profileId);
            if (request('image')) {
                if ($profile->image != 'default.jpg') {
                    Storage::delete($profile->image);
                    $image = request()->file('image')->store('img/profiles');
                }
                $image = request()->file('image')->store('img/profiles');
            } else {
                $image = $profile->image;
            }
        } else {
            $image = request('image') ? request()->file('image')->store('img/profiles') : 'default.jpg';
        }

        Profile::updateOrCreate(['id' => request('profile_id')], [
            'name' => request('name'),
            'image' => $image,
            'address' => request('address'),
        ]);
    }
}
