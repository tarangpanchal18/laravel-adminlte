<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function profile(): View
    {
        return view('admin.profile', [
            'actionUrl' => route('admin.updateProfile'),
            'user' => auth()->user(),
        ]);
    }

    public function handleUpdateProfile(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'email' => [
                'required','email','min:4','max:40',Rule::unique('admins', 'email')->ignore(auth()->user()->id),
            ],
            'name' => ['required', 'string', 'min:3'],
            'password' => ['nullable', 'confirmed', 'min:6', 'max:12', 'regex:'. config('constants.default_password_regx'),],
            'current_password' => ['nullable', 'required_with:password'],
        ], [
            'password' => 'Please create strong password'
        ]);

        if ($validatedData['password']) {
            if (Hash::check($request->current_password, auth()->user()->password)) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            } else {
                throw ValidationException::withMessages([
                    'current_password' => "Current password is not valid",
                ]);
            }
        } else {
            unset($validatedData['password']);
        }
        unset($validatedData['current_password']);

        Admin::where('id', auth()->user()->id)
            ->update($validatedData);

        return redirect()->route('admin.profile')
            ->with('success', 'Profile updated successfully !');
    }
}
