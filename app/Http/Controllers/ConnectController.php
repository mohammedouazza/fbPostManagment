<?php

namespace App\Http\Controllers;

use App\User;
use App\Page;
use Facebook\Facebook;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class ConnectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('connect.index');
    }

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
            $fb->setDefaultAccessToken(Auth::user()->facebookUser ? Auth::user()->facebookUser->token : '');
            //dd($fb);
            // Get the \Facebook\GraphNodes\GraphUser object for the current user.
            // If you provided a 'default_access_token', the '{access-token}' is optional.
            $response = $fb->get('/me/accounts', Auth::user()->token);
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
            foreach ($pages as $key => $page) {
                Page::updateOrCreate([
                    'facebook_id' => $page['id'],
                    'active' => false
                ], [
                    'name' => $page['name'],
                    'user_id' => auth()->user()->id
                ]);
            }
        } catch (FacebookSDKException $e) {
            dd($e); // handle exception
        }
    }
}
