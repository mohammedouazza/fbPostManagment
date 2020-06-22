<?php

namespace App;

use App\Notifications\ScheduleNotification;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;

trait Publishable
{
    public function publishToPage($page_id, $body)
    {
        try {
            $fb = new Facebook();
            $fb->setDefaultAccessToken($this->page->user->facebookUser->token);
            $post = $fb->post('/' . $page_id . '/feed', array('message' => $body), $this->getPageAccessToken($fb, $page_id));
            //dd($post);
            $post = $post->getGraphNode()->asArray();
            if ($post) {
                $this->status = false;
                $this->save();
                $this->page->user->notify(new ScheduleNotification($this));
            }
            return $post;
        } catch (FacebookSDKException $e) {
            //dd($e); // handle exception
            return back()->with('error', $e->getMessage());
        }
    }

    public function getPageAccessToken(Facebook $fb, $page_id)
    {
        try {
            $response = $fb->get('/me/accounts', $this->page->user->facebookUser->token);
        } catch (FacebookResponseException $e) {
            // When Graph returns an error
            return back()->with('error', $e->getMessage());
            //echo 'Graph returned an error: ' . $e->getMessage();
            //exit;
        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            return back()->with('error', $e->getMessage());
            //echo 'Facebook SDK returned an error: ' . $e->getMessage();
            //exit;
        }

        try {
            $pages = $response->getGraphEdge()->asArray();

            foreach ($pages as $key) {
                if ($key['id'] == $page_id) {
                    return $key['access_token'];
                }
            }
        } catch (FacebookSDKException $e) {
            //dd($e); // handle exception
            return back()->with('error', $e->getMessage());
        }
    }
}
