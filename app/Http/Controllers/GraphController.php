<?php

namespace App\Http\Controllers;

use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GraphController extends Controller
{
    public function retrieveUserProfile()
    {
        try {
            //dd($fb);
            $fb = new Facebook();
            $fb->setDefaultAccessToken(Auth::user()->facebookUser->token);
            $params = "first_name,last_name,age_range,gender";

            $user = $fb->get('/me?fields=' . $params)->getGraphUser();

            return $user;
        } catch (FacebookSDKException $e) {
            dd($e);
        }
    }

    public function publishToProfile(Request $request)
    {
        try {
            $fb = new Facebook();
            $fb->setDefaultAccessToken(Auth::user()->facebookUser->token);
            $response = $fb->post('/me/feed', [
                'message' => $request->message
            ])->getGraphNode()->asArray();
            if ($response['id']) {
                // post created
            }
        } catch (FacebookSDKException $e) {
            dd($e); // handle exception
        }
    }

    public function getPages()
    {
        try {
            $fb = new Facebook();
            $fb->setDefaultAccessToken(Auth::user()->facebookUser->token);
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
            //dd($pages);
            return $pages;
        } catch (FacebookSDKException $e) {
            dd($e); // handle exception
        }
    }
    public function retrieveUserPages()
    {
        $pages = $this->getPages();
        return view('pages.index', compact('pages'));
    }

    public function getPageAccessToken($page_id)
    {
        try {
            $fb = new Facebook();
            $fb->setDefaultAccessToken(Auth::user()->facebookUser->token);
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

    public function publishToPage($page_id, $body)
    {
        try {
            $fb = new Facebook();
            $fb->setDefaultAccessToken(Auth::user()->facebookUser->token);
            $post = $fb->post('/' . $page_id . '/feed', array('message' => $body), $this->getPageAccessToken($page_id));

            $post = $post->getGraphNode()->asArray();

            return $post;
        } catch (FacebookSDKException $e) {
            dd($e); // handle exception
        }
    }

    public function getPage($page_id)
    {
        $pages = $this->getPages();
        $page = array_filter($pages, function ($page) use ($page_id) {
            return $page['id'] == $page_id;
        })[0] ?? null;

        return $page;
    }
    public function single_page($page_id)
    {
        $page = $this->getPage($page_id);

        dd($page);
        return view('pages.show', compact('page'));
    }
}
