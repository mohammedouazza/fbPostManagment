<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'token', 'facebook_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function facebookUser()
    {
        //dd($this->belongsTo(User::class, 'facebook_id', 'id'));
        return $this->belongsTo(User::class, 'id', 'facebook_id');
    }

    public function pages()
    {
        return $this->hasMany(Page::class)->orderBy('name', 'desc');
    }

    public function activatePages($pages)
    {
        $updated = true;

        foreach ($this->pages as $page) {
            $updated = $updated && $page->update([
                'active' => in_array($page->id, $pages) ? true : false
            ]);
        }

        return $updated;
    }

    public function hasPage($page_id)
    {
        return $this->pages()->where('active', true)->findOrFail($page_id);
    }
}
