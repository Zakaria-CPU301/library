<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\Collection;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

use function Pest\Laravel\session;
session_start();
class UserController extends Controller
{
    public function index()
    {
        $collections = Collection::all();
        $users = User::latest('id')->get();
        return view('admin.users.index', compact('collections', 'users'));
    }

    public function data(Request $request)
    {
        if (!$request->expectsJson()) {
            abort(404);
        }
        $cId = $request['c-id'];
        $users = $cId !== null ? User::with('collection')->where('collection_id', $cId)->latest()->get() : User::with('collection')->latest()->get();

        return response()->json([
            'users' => $users,
        ]);
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $collections = Collection::all();
        return view('auth.register', compact('collections'));
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
            'username' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:' . User::class], //! dev fix: unique for username
            'email' => ['required', 'string', 'lowercase', 'email:dns', 'max:255', 'unique:' . User::class],
            'password' => ['required', Rules\Password::defaults()],
            'role' => ['required'],
            'collection' => ['required'],
        ]);

        try {
            DB::beginTransaction();

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
                'role' => $request->role,
                'collection_id' => $idCollection
            ]);

            event(new Registered($user));

            // Auth::login($user);

            DB::commit();

            return redirect()->route('users.index')->with('success', 'Users register successfully');
        } catch (Exception $eSingle) {
            DB::rollBack();
            // if ($request->ajax()) {
            //     return response()->json(['success' => false, 'message' => $e->getMessage()]);
            // }
            return redirect()->back()->withErrors(['err' => $eSingle->getMessage()]);
        }
    }

    public function importStore(Request $request): RedirectResponse
    {
        $request->validate([
            'import' => ['required', 'file', 'mimes:xlsx,xls,csv'],
            'collection' => ['required'],
        ]);

        try {
            DB::beginTransaction();
            if (!ctype_digit($request->collection)) {
                Collection::create(['collection_name' => $request->collection]);
                $idCollection = Collection::max('id');
            } else {
                $idCollection = $request->collection; // id_collection max + 1
            }

            Excel::import(new UsersImport($idCollection), $request->file('import'));
            DB::commit();
            return redirect()->route('users.index')->with('success', 'Users imported successfully.');
        } catch (\Throwable $eImport) {
            DB::rollBack();
            return redirect()->back()->withErrors(['err' => $eImport->getMessage()]);
        }
    }

    public function edit(string $userId)
    {
        $users = User::where('id', $userId)->get();
        $collections = Collection::all();
        foreach ($users as $user) {
            return view('auth.register', compact("user", "collections", "userId"));
        }
    }

    public function update(string $userId, Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'], //! dev fix: unique for username
            'email' => ['required', 'string', 'lowercase', 'max:255'],
            'password' => ['required', Rules\Password::defaults()],
            'collection' => ['required'],
        ]);

        try {
            if (!ctype_digit($request->collection)) {
                Collection::create(['collection_name' => $request->collection]);
                $idCollection = Collection::max('id');
            } else {
                $idCollection = $request->collection;
            }

            User::findOrFail($userId)->update([
                'fullname' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'collection_id' => $idCollection
            ]);

            return redirect()->route('users.index')->with('success', 'User updated successfully');
        } catch (Exception $eSingle) {
            DB::rollBack();
            // if ($request->ajax()) {
            //     return response()->json(['success' => false, 'message' => $e->getMessage()]);
            // }
            return redirect()->back()->withErrors(['err' => $eSingle->getMessage()]);
        }
    }

    public function destroy(string $userId, User $user)
    {
        $user::findOrFail($userId)->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }
}
