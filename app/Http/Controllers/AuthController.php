<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Controller untuk menangani autentikasi pengguna (login, register, logout).
 * Mengelola sesi pengguna dan validasi kredensial.
 */
class AuthController extends Controller
{
    /**
     * Menampilkan halaman form login.
     *
     * @param Request $request Objek request HTTP yang berisi parameter redirect opsional
     * @return View View halaman login dengan variabel redirectTo
     * @description Menampilkan form login. Parameter 'redirect' dari query string digunakan
     *              untuk mengarahkan pengguna setelah login berhasil (default: /dashboard).
     */
    public function showLogin(Request $request): View
    {
        return view('auth.login', ['redirectTo' => $request->query('redirect', '/dashboard')]);
    }

    /**
     * Memproses login pengguna.
     *
     * @param Request $request Objek request HTTP berisi email, password, remember, dan redirect
     * @return \Illuminate\Http\RedirectResponse Redirect ke halaman yang dimaksud setelah login
     * @throws ValidationException Jika kredensial tidak valid (email/password salah)
     * @description Memvalidasi email dan password. Jika valid, membuat sesi baru dan
     *              login pengguna. Parameter 'remember' untuk remember token. Redirect
     *              ke URL dari input 'redirect' atau intended URL atau default /dashboard.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau kata sandi salah.',
            ]);
        }

        $request->session()->regenerate();

        $redirectTo = $request->input('redirect', '/dashboard');
        return redirect()->intended($redirectTo);
    }

    /**
     * Menampilkan halaman form registrasi.
     *
     * @param Request $request Objek request HTTP yang berisi parameter redirect opsional
     * @return View View halaman registrasi dengan variabel redirectTo
     * @description Menampilkan form registrasi. Parameter 'redirect' dari query string
     *              digunakan untuk mengarahkan pengguna setelah registrasi berhasil.
     */
    public function showRegister(Request $request): View
    {
        return view('auth.register', ['redirectTo' => $request->query('redirect', '/dashboard')]);
    }

    /**
     * Memproses registrasi pengguna baru.
     *
     * @param Request $request Objek request HTTP berisi name, email, password, password_confirmation, redirect
     * @return \Illuminate\Http\RedirectResponse Redirect ke halaman yang dimaksud setelah registrasi
     * @throws ValidationException Jika validasi gagal (email sudah terdaftar, password tidak cocok, dll)
     * @description Memvalidasi data registrasi: nama (max 100 char), email unik, password min 8 char
     *              dengan konfirmasi. Membuat user baru dengan role 'USER', hash password,
     *              login otomatis, dan regenerasi session. Redirect ke URL dari input 'redirect'.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'USER',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $redirectTo = $request->input('redirect', '/dashboard');
        return redirect()->intended($redirectTo);
    }

    /**
     * Memproses logout pengguna.
     *
     * @param Request $request Objek request HTTP untuk mengakses session
     * @return \Illuminate\Http\RedirectResponse Redirect ke route 'home'
     * @description Logout pengguna, invalidate session, dan regenerasi CSRF token.
     *              Mengarahkan ke halaman beranda (home).
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Mengembalikan data pengguna yang sedang login (API).
     *
     * @param Request $request Objek request HTTP dengan user terautentikasi
     * @return \Illuminate\Http\JsonResponse JSON berisi data user
     * @description Endpoint API untuk mendapatkan data user yang sedang login.
     *              Digunakan oleh frontend untuk memeriksa status autentikasi.
     */
    public function me(Request $request)
    {
        return response()->json(['user' => $request->user()]);
    }
}