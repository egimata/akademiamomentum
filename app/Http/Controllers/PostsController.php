<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;


class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderby('created_at', 'desc')->paginate(3);
        return view('posts.index')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'short_description' => 'required',
            'image' => 'image|max:1990',
            'png' => 'image|max:1990'
        ]);
        // File Upload
        if ($request->hasFile('image')){
            // Get Filename with extension
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            // Get just Filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just Ext
            $extension = $request->file('image')->getClientOriginalExtension();
            // Store Filename | Not overwrite image with same name
            $filenameStore = $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('image')->storeAs('public/images', $filenameStore);
        }
        if ($request->hasFile('png')){
            // Get Filename with extension
            $filenameWithExt = $request->file('png')->getClientOriginalName();
            // Get just Filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just Ext
            $extension = $request->file('png')->getClientOriginalExtension();
            // Store Filename | Not overwrite image with same name
            $filenameStorePng = $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('png')->storeAs('public/images', $filenameStorePng);
        }
        // Create Post
        $post = new Post;
        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->short_description = $request->input('short_description');
        $post->user_id = auth()->user()->id;
        $post->image =$filenameStore;
        $post->png =$filenameStorePng;
        $post->save();

        return redirect('/posts')->with('success', 'Post Created');
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

        $post = Post::find($id);
        // check for correct user id
        if(auth()->user()->id !== $post->user_id){
            return redirect ('/posts')->with('error', 'Unauthorized Page');
        }
        return view('posts.edit')->with('post', $post);
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
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'short_description' => 'required',
        ]);
        // File Upload
        if ($request->hasFile('image')){
            // Get Filename with extension
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            // Get just Filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just Ext
            $extension = $request->file('image')->getClientOriginalExtension();
            // Store Filename | Not overwrite image with same name
            $filenameStore = $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('image')->storeAs('public/images', $filenameStore);
        }
        if ($request->hasFile('png')){
            // Get Filename with extension
            $filenameWithExt = $request->file('png')->getClientOriginalName();
            // Get just Filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just Ext
            $extension = $request->file('png')->getClientOriginalExtension();
            // Store Filename | Not overwrite image with same name
            $filenameStorePng = $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('png')->storeAs('public/images', $filenameStorePng);
        }
        // Create Post
        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->short_description = $request->input('short_description');
        if ($request->hasFile('image')){
            Storage::delete('public/images/'.$post->image);
            $post->image = $filenameStore;
        }
        if ($request->hasFile('png')){
            Storage::delete('public/images/'.$post->png);
            $post->png = $filenameStorePng;
        }
        $post->save();
        return redirect('/posts')->with('success', 'Post Updated');
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
        if(auth()->user()->id !== $post->user_id){
            return redirect ('/posts')->with('error', 'Unauthorized Page');
        }
        // Delete Image
        Storage::delete('public/images/'.$post->image, $post->png);
        $post->delete();
        return redirect('/posts')->with('success', 'Post Deleted');
    }

}
