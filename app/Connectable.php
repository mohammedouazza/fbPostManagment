<?php

namespace App;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Laravel\Socialite\Facades\Socialite;

trait Connectable
{
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')
            ->scopes([
                'pages_show_list',
                'pages_manage_posts',
                'pages_read_engagement'
            ])
            ->redirect();
    }

    /**
     * Obtain the user information from facebook.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $auth_user = Socialite::driver('facebook')->stateless()->user();
        //dd($auth_user);
        $user = User::updateOrCreate(
            [
                'email' => $auth_user->email
            ],
            [
                'token' => $auth_user->token,
                'name'  =>  $auth_user->name,
                'facebook_id'  =>  auth()->user()->id
            ]
        );

        $this->getPages();
        return redirect(route('connect.index'));
        // $user->token;
        //dd($user);
    }

    public function getPages()
    {


        try {
            $fb = new Facebook();
            $fb->setDefaultAccessToken(auth()->user()->facebookUser ? auth()->user()->facebookUser->token : '');
            $response = $fb->get('/me/accounts', auth()->user()->token);
        } catch (FacebookResponseException $e) {
            //echo 'Graph returned an error: ' . $e->getMessage();
            //exit;
            return back()->with('error', $e->getMessage());
        } catch (FacebookSDKException $e) {
            //echo 'Facebook SDK returned an error: ' . $e->getMessage();
            //exit;
            return back()->with('error', $e->getMessage());
        }

        try {
            $pages = $response->getGraphEdge()->asArray();
            //dd($pages);
            foreach ($pages as $page) {
                $page = Page::updateOrCreate([
                    'facebook_id' => $page['id'],
                    'active' => false,
                    'name' => $page['name']
                ], [
                    'user_id' => auth()->user()->id
                ]);

                if ($page) {
                    $posts = $this->getPosts($page->facebook_id);
                    //dd($posts[0]['id']);
                    foreach ($posts as $post) {
                        Post::updateOrCreate([
                            'facebook_id' => $post['id'],
                            'page_id' => $page->id,
                            'status' => false
                        ], [
                            'name' => $post['message'],
                            'date' => $post['created_time']->format('Y-m-d')
                        ]);
                    }
                }
            }
        } catch (FacebookSDKException $e) {
            //dd($e); // handle exception
            return back()->with('error', $e->getMessage());
        }
    }

    public function getPosts($page_id)
    {
        try {
            $fb = new Facebook();
            $fb->setDefaultAccessToken(auth()->user()->facebookUser ? auth()->user()->facebookUser->token : '');
            $response = $fb->get("/$page_id/feed", auth()->user()->token);
        } catch (FacebookResponseException $e) {
            return back()->with('error', $e->getMessage());
        } catch (FacebookSDKException $e) {
            return back()->with('error', $e->getMessage());
        }
        try {
            $posts = $response->getGraphEdge()->asArray();

            return $posts;
        } catch (FacebookSDKException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
