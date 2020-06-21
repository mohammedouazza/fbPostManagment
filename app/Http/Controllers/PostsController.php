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
            $facebook_post = $this->publishToPage($page->facebook_id, request()->description);

            return back()->with('success', 'Post created');
        } else {
            return back()->with('error', 'Page not found');
        }
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return back();
    }

    public function publishToPage($page_id, $body)
    {
        try {
            $fb = new Facebook();
            $fb->setDefaultAccessToken(Auth::user()->facebookUser->token);
            $post = $fb->post('/' . $page_id . '/feed', array('message' => $body), $this->getPageAccessToken($fb, $page_id));
            //dd($post);
            $post = $post->getGraphNode()->asArray();

            return $post;
        } catch (FacebookSDKException $e) {
            dd($e); // handle exception
        }
    }

    public function getPageAccessToken(Facebook $fb, $page_id)
    {
        try {
            $response = $fb->get('/me/accounts', Auth::user()->facebookUser->token);
        } catch (FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        try {
            $pages = $response->getGraphEdge()->asArray();

            foreach ($pages as $key) {
                if ($key['id'] == $page_id) {
                    return $key['access_token'];
                }
            }
        } catch (FacebookSDKException $e) {
            dd($e); // handle exception
        }
    }
}
