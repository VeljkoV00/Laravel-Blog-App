<?php

namespace App\Http\Controllers;

use App\Models\Category;
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
        elseif($request->category){

            $posts = Category::where('name', $request->category)->firstOrFail()->post()->paginate(4)->withQueryString();
           
        }
        else{

            $posts = Post::latest()->paginate(4);
        }

        $categories = Category::all();

      
        return view('blog.index', compact('posts', 'categories'));
    }

    public function create (){

        $categories = Category::all();
        return view('blog.create', compact('categories'));
    }

    public function store(Request $request){

      $request->validate([
          'title' => 'required',
          'body' => 'required',
          'image' => 'required | image',
          'category_id' => 'required'


      ]);

      $title = $request->input('title');
      $category_id = $request->input('category_id');
     if(Post::latest()->first() !== null){
         $postId = Post::latest()->first()->id + 1;
     }
     else{
         $postId =1;
     }
      $slug = Str::slug($title, '-') . '-' . $postId;
      $body = $request->input('body');
      $user_id = Auth::user()->id;

       $imagePath = 'storage/' .  $request->file('image')->store('postImages' ,'public');

       $post = new Post();
       $post->title = $title;
       $post->category_id = $category_id;
       $post->slug = $slug;
       $post->body = $body;
       $post->user_id = $user_id;
       $post->imagePath = $imagePath;
       $post->save();

       return redirect()->back()->with('status', 'Post created successfully');
    }

    public function show(Post $post){

        $category = $post->category;
        $relatedPosts = $category->post()->where('id', '!=', $post->id )->latest()->take(3)->get();
   
        return view('blog.single-blog', compact('post', 'relatedPosts'));
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
