<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Languages;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit(){
        $user = Auth::user();
        return view('dashboard.profile.edit' , [
            'user' => $user,
            'countries' => Countries::getNames(),
            'locales' => Languages::getNames(),
        ]);
    }

    public function update(Request $request){
        $request->validate([
            'first_name' => ['required' , 'string' , 'max:255'],
            'last_name' => ['required' , 'string' , 'max:255'],
            'birthday' => ['nullable' , 'date' , 'before:today'],
            'gender'  => ['in:male,female'],
            'country' => ['required' , 'string' ,'size:2'],
        ]);

        $user = $request->user();
        $user->profile->fill($request->all())->save();

        return redirect()->route('dashboard.profile.edit')
                ->with('success' , 'Profile Updated!');

        // $profile = $user->profile;
        // if($profile->user_id){
        //     $profile->update($request->all());
        // }else{
        //     // $request->merge([
        //         // 'user_id' => $user->id,
        //     // ]);
        //     // Profile::create($request->all());
        //         $user->profile()->create($request->all());
        // }

    }
}
