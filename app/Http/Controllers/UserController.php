<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    protected function checkSuperAdmin()
    {
        abort_unless(auth()->check() && auth()->user()->isSuperAdmin(), 403, 'Acceso denegado. Se requieren permisos de Super Administrador.');
    }

    public function index(Request $request)
    {
        $this->checkSuperAdmin();

        $tab = $request->query('tab', 'users');

        $users = User::withCount('accessLogs')
            ->orderBy('name')
            ->get();

        $logs = AccessLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(30)
            ->withQueryString();

        return view('users.index', compact('users', 'logs', 'tab'));
    }

    public function create()
    {
        $this->checkSuperAdmin();
        return view('users.create');
    }

    public function store(Request $request)
    {
        $this->checkSuperAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'role' => 'required|string|in:admin,super_admin',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'email.unique' => 'Ya existe un usuario registrado con este correo electrónico.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario "' . $request->name . '" creado con éxito.');
    }

    public function edit(User $user)
    {
        $this->checkSuperAdmin();
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->checkSuperAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|string|in:admin,super_admin',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'email.unique' => 'Ya existe un usuario registrado con este correo electrónico.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Usuario "' . $user->name . '" actualizado con éxito.');
    }

    public function destroy(User $user)
    {
        $this->checkSuperAdmin();

        if (auth()->id() === $user->id) {
            return back()->with('error', 'Por seguridad, no puedes eliminar tu propia cuenta de usuario.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario "' . $userName . '" eliminado con éxito.');
    }
}
