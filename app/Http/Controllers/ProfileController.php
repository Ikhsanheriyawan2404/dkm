<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProfileController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $profiles = Profile::latest()->get();
            return DataTables::of($profiles)
                    ->addIndexColumn()
                    ->editColumn('image', function (Profile $profile) {
                        return '<img src="'. $profile->takeImage .'" />';
                    })
                    ->addColumn('action', function($row){
                        $btn =

                        '<div class="btn-group">
                            <a class="badge badge-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0)" data-id="'.$row->id.'" id="editProfile" class="btn btn-sm btn-primary">Edit</a>
                            </div>
                        </div>';
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
                if ($profile->image != 'img/profiles/default.jpg') {
                    Storage::delete($profile->image);
                    $image = request()->file('image')->store('img/profiles');
                }
                $image = request()->file('image')->store('img/profiles');
            } else {
                $image = $profile->image;
            }
        } else {
            $image = request('image') ? request()->file('image')->store('img/profiles') : 'img/profiles/default.jpg';
        }

        Profile::updateOrCreate([
            'name' => request('name'),
            'image' => $image,
            'address' => request('address'),
        ]);
    }
}
