<?php

namespace App\Http\Controllers;

use App\Post;
use Facebook\Facebook;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = auth()->user()->pages()->with('posts')->paginate(5)->pluck('posts')->flatten()->sortByDesc('date')->all();
        //dd($posts);
        return view('posts.index', compact('posts'));
    }

    public function store()
    {
        request()->validate([
            'description' => ['required', 'max:255'],
            'page' => ['required', 'exists:pages,id']
        ]);
        if (request()->date) {
            request()->validate([
                'date' => ['after_or_equal:tomorrow']
            ]);
        }

        $page = auth()->user()->hasPage(request()->page);
        if ($page) {
            $post = Post::create([
                'name' => request()->description,
                'status' => request()->date ? true : false,
                'date' => request()->date ? date('Y-m-d', strtotime(request()->date)) : date('Y-m-d', time()),
                'page_id' => request()->page
            ]);
            if ($post->status) {
                return back()->with('success', 'Post scheduled to ' . $post->date);
            }
            $post_published = $post->publishToPage($page->facebook_id, request()->description);
            //dd($post_published);
            $post->facebook_id = $post_published['id'];
            $post->save();
            return back()->with('success', 'Post created');
        } else {
            return back()->with('error', 'Page not found');
        }
    }

    public function destroy(Post $post)
    {
        if ($post->status) {
            $post->delete();
            return back()->with('success', 'Scheduled post canceled from page ' . $post->page->name);
        }
        $post_deleted = $post->deleteFromPage();
        if ($post_deleted['success']) {
            $post->delete();
            return back()->with('success', 'Post deleted from page ' . $post->page->name);
        }
        return back()->with('error', 'Something went wrong');
    }
}
