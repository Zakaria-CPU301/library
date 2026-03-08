<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManageBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::all();
        return view('pages.books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'qty' => 'required|integer|min:0',
            'lang' => 'required|string|in:indonesian,english',
            'year' => 'required|string|',
            'category' => 'required|exists:categories,id',
        ]);

        try {
            DB::beginTransaction();
            Book::create([
                'title' => $validatedData['title'],
                'author' => $validatedData['author'],
                'qty' => $validatedData['qty'],
                'lang' => $validatedData['lang'],
                'year_published' => $validatedData['year'],
                'category_id' => $validatedData['category'],
            ]);
            DB::commit();
            return redirect()->route('books.index');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('err', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
