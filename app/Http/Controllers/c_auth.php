<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class c_auth extends Controller
{
    private function redirectByUserRole($user)
    {
        return match ($user->nama_role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'manager' => redirect()->route('manager.dashboard'),
            'staff'   => redirect()->route('staff.dashboard'),
            default   => redirect('/dashboard'),
        };
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByUserRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login_input'    => 'required|email',
            'password_input' => 'required|string',
        ], [
            'login_input.required'    => 'Email wajib diisi.',
            'login_input.email'       => 'Format email tidak valid.',
            'password_input.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('email', $request->login_input)->first();

        if (!$user || !Hash::check($request->password_input, $user->password)) {
            throw ValidationException::withMessages([
                'login_input' => ['Email atau password yang Anda masukkan salah.'],
            ]);
        }

        Auth::login($user, $request->has('remember'));
        $request->session()->regenerate();

        return $this->redirectByUserRole($user);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
