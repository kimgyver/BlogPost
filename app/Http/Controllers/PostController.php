<?php

namespace App\Http\Controllers;

use App\BlogPost;
use Illuminate\Http\Request;
use App\Http\Requests\StorePost;
// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\User;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')
            ->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // difference b/w lazy loading and eager loading
        // DB::connection()->enableQueryLog();
        // $posts = BlogPost::with('comments')->get();
        // foreach ($posts as $post) {
        //     foreach ($post->comments as $comment) {
        //         echo $comment->content;
        //     }
        // }
        // dd(DB::getQueryLog());

        $mostCommented = Cache::remember('mostCommented', now()->addSeconds(10), function() {
            return BlogPost::mostCommented()->take(5)->get();
        });

        $mostActive = Cache::remember('mostActive', now()->addSeconds(10), function() {
            return User::mostBlogPosts()->take(5)->get();
        });

        $mostActiveLastMonth = Cache::remember('mostActiveLastMonth', now()->addSeconds(10), function() {
            return User::mostBlogPostsLastMonth()->take(5)->get();
        });

        // comments_count
        return view('posts.index', 
                [
                    'posts' => BlogPost::latest()->withCount('comments')->with('user')->with('tags')->get(),
                    'mostCommentedPosts' => $mostCommented,
                    'mostActive' => $mostActive,
                    'mostActiveLastMonth' => $mostActiveLastMonth,
                ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //dd(BlogPost::find($id));
        // $request->session()->reflash();
        
        // return view('posts.show', ['post' => BlogPost::with(['comments' => function($builder) {
        //     return $builder->latest();
        // }])->findOrFail($id)]);

        $counter = 0;

        return view('posts.show', 
            [
                'post' => BlogPost::with('comments')->with('tags')->with('user')->findOrFail($id),
                'counter' => $counter,
            ]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(StorePost $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = $request->user()->id;

        $blogPost = BlogPost::create($validatedData);
        // $blogPost = new BlogPost();
        // $blogPost->title = $request->input('title');
        // $blogPost->content = $request->input('content');
        // $blogPost->save();

        $request->session()->flash('status', 'Blog post was created!');

        // return redirect()->route('posts.index');
        return redirect()->route('posts.show', ['post' => $blogPost->id]);
    }

    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);

        // if (Gate::denies('update-post', $post)) 
        // {
        //     abort(403, 'You cannot edit this blog post!');
        // }
        $this->authorize('update', $post);
        
        return view('posts.edit', ['post' => $post]);
    }

    public function update(StorePost $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        // if (Gate::denies('update-post', $post)) 
        // {
        //     abort(403, 'You cannot edit this blog post!');
        // }
        $this->authorize('update', $post);

        $validatedData = $request->validated();
        
        $post->fill($validatedData);
        $post->save();
        $request->session()->flash('status', 'Blog post was updated!');
        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    public function destroy(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        // if (Gate::denies('delete-post', $post)) 
        // {
        //     abort(403, 'You cannot delete this blog post!');
        // }
        $this->authorize('delete', $post);

        $post->delete();

        //BlogPost::destroy($id);

        $request->session()->flash('status', 'Blog post was deleted!');
        return redirect()->route('posts.index');
    }
}
