<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class PostDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest()->where('author_id', Auth::User()->id);

        if (request('keyword')) {
            $posts->where('title', 'like', '%' . request('keyword') . '%');
        }

        return view('dashboard.index', ['posts' => $posts->paginate(7)->withQueryString()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        //validation

        // $request->validate([
        //     'title' => 'required|unique:posts|min:4|max:255',
        //     'category_id' => 'required',
        //     'body' => 'required'
        // ]);

        Validator::make($request->all(), [
            'title' => 'required|unique:posts|min:4|max:255',
            'category_id' => 'required',
            'body' => 'required|min:50'
        ], [
            'title.required' => 'Field :attribute harus diisi!',
            'category_id.required' => 'Pilih salah satu :attribute',
            'body.required' => ':attribute gak boleh kosong',
            'body.min' => ':attribute gak boleh kurang dari :min karakter'

        ], [
            'title' => 'judul',
            'category_id' => 'kategori',
            'body' => 'tullisan'
        ])->validate();


        Post::create([
            'title' => $request->title,
            'author_id' => Auth::user()->id,
            'category_id' => $request->category_id,
            'slug' => Str::slug($request->title),
            'body' => $request->body
        ]);

        return redirect('/dashboard')->with(['success' => 'Postingan kamu telah di tambahkan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('dashboard.show', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('dashboard.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|min:4|max:255|unique:posts,title' . $post->id,
            'category_id' => 'required',
            'body' => 'required'
        ]);


        $post->update([
            'title' => $request->title,
            'author_id' => Auth::user()->id,
            'category_id' => $request->category_id,
            'slug' => Str::slug($request->title),
            'body' => $request->body
        ]);

        return redirect('/dashboard')->with(['success' => 'Postingan kamu telah di ubah']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect('/dashboard')->with(['success' => 'Postingan kamu telah di hapus']);
    }
}
