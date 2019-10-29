<?php

namespace App\Http\Controllers;

use App\Post;
use http\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class PostController extends Controller
{
    //PostController constructor.

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $user = \Auth::user();
        $post = $user->posts;
        return view('posts.index', ['posts' => $post]); //view all posts of the user
    }

    public function create() {
        return view('posts.create'); //view create post form
    }

    public function store(Request $request) {
        $this->validate($request, array(
            'title' => 'required|max:25',
            'description' => 'required'
        ));

        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;
        $post->user_id = \Auth::user()->id;

        if(Input::hasFile('image')){
            $file =Input::file('image');
            $file->move(public_path().'/images/', $file->getClientOriginalName());
            $url =Url::to("/").'/images/'. $file->getClientOriginalName();
        }
        $post->image =$url;
        $post->save();

        return redirect('response', 'Post Created successfully');
    }

    public function show($id) {
        $posts = Post::findOrFail($id);
        return view('posts.show', ['post' => $posts]);
    }


}
