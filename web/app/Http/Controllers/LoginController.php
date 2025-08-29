<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
	//

	public function index()
	{
		return view('auth.index');
	}

	public function authenticate(Request $request)
	{
		$credentials = $request->validate([
			'email' => ['required', 'email'],
			'password' => ['required']
		]);

		if (Auth::attempt($credentials)) {

			Log::info(auth()->user());
			session()->put('usuario', auth()->user());

			$request->session()->regenerate();

			return redirect()->intended('/jobs/dashboard');
		}

		return back()->withErrors([
			'email' => 'Email e/ou senha inválida.'
		]);
	}

	public function logout(Request $request)
	{
		Auth::logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return redirect()->route('login');
	}

	public function registerIndex()
	{
		return view('auth.form');
	}

	public function registerStore(Request $request)
	{
		$request->validate([
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'password' => ['required', 'string', 'min:8', 'confirmed'],
			'password_confirmation' => ['required','min:4']
		]);

		$user = new User;
		$user->name = $request->name;
		$user->email = $request->email;
		$user->email_comercial = $request->email_comercial;
		$user->password = Hash::make($request->password);
		$user->save();

		// User::fill($request->only('name', 'email', 'email_comercial','password'))->save();

		return redirect()->route('login');
	}
}
