<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        App::setLocale('id');

        $request->validate([
            'nama_user' => ['required', 'string', 'max:100', 'unique:'.User::class.',nama_user'],
            'age' => ['required', 'numeric'],
            'gender' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok dengan kata sandi di atas.',
        ]);

        // Kategori Umur
        $ageInput = $request->age;
        $ageCategory = 3; // Default > 40
        if ($ageInput < 30) {
            $ageCategory = 1;
        } elseif ($ageInput <= 40) {
            $ageCategory = 2;
        }

        $user = User::create([
            'nama_user' => $request->nama_user,
            'age' => $ageCategory,
            'gender' => $request->gender,
            'password' => Hash::make($request->password),
            'role' => 'owner', // Default role for registration
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
