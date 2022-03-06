<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class BlogController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
    }

    public function index(Request $request){
        if($request->search){
            $posts = Post::where('title', 'like', '%' . $request->search . '%')
            ->orWhere('body', 'like', '%' .  $request->search . '%')->latest()->paginate(4);
        }
        else{

            $posts = Post::latest()->paginate(4);
        }

      
        return view('blog.index', compact('posts'));
    }

    public function create (){

        return view('blog.create');
    }

    public function store(Request $request){

      $request->validate([
          'title' => 'required',
          'body' => 'required',
          'image' => 'required | image' 


      ]);

      $title = $request->input('title');
      $postId = Post::latest()->take(1)->first()->id + 1;
      $slug = Str::slug($title, '-') . '-' . $postId;
      $body = $request->input('body');
      $user_id = Auth::user()->id;

       $imagePath = 'storage/' .  $request->file('image')->store('postImages' ,'public');

       $post = new Post();
       $post->title = $title;
       $post->slug = $slug;
       $post->body = $body;
       $post->user_id = $user_id;
       $post->imagePath = $imagePath;
       $post->save();

       return redirect()->back()->with('status', 'Post created successfully');
    }

    public function show(Post $post){
   
        return view('blog.single-blog', compact('post'));
    }

    public function edit(Post $post){
        if(auth()->user()->id !== $post->user->id){
            abort(403);
        }

        return view('blog.edit', compact('post'));
    }
  
    public function update(Request $request, Post $post){

        if(auth()->user()->id !== $post->user->id){
            abort(403);
        }

        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'image' => 'required | image' 
  
  
        ]);
  
        $title = $request->input('title');
        $postId = $post->id;
        $slug = Str::slug($title, '-') . '-' . $postId;
        $body = $request->input('body');
       
  
         $imagePath = 'storage/' .  $request->file('image')->store('postImages' ,'public');
  
         
         $post->title = $title;
         $post->slug = $slug;
         $post->body = $body;
         $post->imagePath = $imagePath;
         $post->save();
  
         return redirect()->back()->with('status', 'Post updated successfully');
      }

      public function delete(Post $post){

        $post->delete();
        return redirect()->back()->with('status', 'Post deleted successfully');
      }
    
}
