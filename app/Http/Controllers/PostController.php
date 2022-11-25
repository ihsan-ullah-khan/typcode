<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    protected $image_path = 'public/images/';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('post.index', [
            'posts' => Post::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'image' => 'required|image',
            'user_id' => 'required',
            'title' => 'required',
            'slug' => 'required',
            'body' => 'required',
        ]);
        if ($file = $request->file('image'))
        {
            $filename = $request->id .' '.$file->getClientOriginalName();
            Storage::disk('local')->put($this->image_path . $filename, $file->getContent());
        }
        $attributes['image'] = $filename;
        Post::create($attributes);

        return redirect()
            ->route('posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('post.edit', [
            'post' => Post::find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($post = Post::find($id))
        {
            $attributes = $request->validate([
                'image' => 'image',
                'user_id' => 'required',
                'title' => 'required',
                'slug' => 'required',
                'body' => 'required',
            ]);
            if ($attributes['image'] ?? false)
            {
                if ($file = $request->file('image'))
                {
                    $filename = $request->id .' '.$file->getClientOriginalName();
                    Storage::disk('local')->put($this->image_path . $filename, $file->getContent());
                }
                $attributes['image'] = $filename;
            }

            $post->update($attributes);

            return redirect()
                ->route('posts.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();
        return redirect()
            ->route('posts.index');
    }
}
