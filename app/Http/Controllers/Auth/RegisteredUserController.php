<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\Collection;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

use function PHPUnit\Framework\isNumeric;

class RegisteredUserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $collections = Collection::all();
        return view('admin.users.register', compact('collections'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function singleStore(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', Rules\Password::defaults()],
            'collection' => ['required'],
        ]);

        if (!ctype_digit($request->collection)) {
            Collection::create(['collection_name' => $request->collection]);
            $idCollection = Collection::max('id');
        } else {
            $idCollection = $request->collection;
        }

        $user = User::create([
            'fullname' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'collection_id' => $idCollection
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('users.index')->with('success', 'Users register successfully');
    }

    public function importStore(Request $request): RedirectResponse
    {
        $request->validate([
            'import' => ['required', 'file', 'mimes:xlsx,xls,csv'],
            'collection' => ['required'],
        ]);

        if (!ctype_digit($request->collection)) {
            Collection::create(['collection_name' => $request->collection]);
            $idCollection = Collection::max('id');
        } else {
            $idCollection = $request->collection;
        }

        Excel::import(new UsersImport($idCollection), $request->file('import'));

        return redirect()->route('users.index')->with('success', 'Users imported successfully.');
    }
}
