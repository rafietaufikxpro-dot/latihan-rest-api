<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::query()
            ->select(['id', 'title', 'author', 'published_year'])
            ->orderBy('id')
            ->get();

        return response()->json(['data' => $books]);
    }

    public function store(Request $request)
    {
        // TODO: Validasi title, author, dan published_year sesuai kontrak latihan.
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'published_year' => 'required|integer|min:1000|max:' . (date('Y') + 1),
        ]);
        // TODO: Simpan data yang lolos validasi dengan model Book.
        if ($book = Book::create([
            'title' => $validated['title'],
            'author' => $validated['author'],
            'published_year' => $validated['published_year'],
        ])) {
            // TODO: Kembalikan JSON data buku baru dengan status HTTP 201.
            return response()->json([
                'data' => $book,
            ], 201);
        } else {
            return response()->json([
                'message' => 'Gagal menyimpan buku.',
            ], 422);
        }
    }

    public function show($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json([
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }
        return response()->json(['data' => $book]);
    }

    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json([
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'published_year' => 'required|integer|min:1000|max:' . (date('Y') + 1),
        ]);
        $book->update($validated);

        return response()->json(['data' => $book]);
    }

    public function destroy($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json([
                'message' => 'Buku tidak ditemukan.',
            ], 404);
        }
        $book->delete();
        return response()->json(['message' => 'Buku berhasil dihapus.']);
    }
}
