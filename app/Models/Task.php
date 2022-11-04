<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory,SoftDeletes;
    public function users()
    {
        return $this->belongsToMany(User::class, Mention::class);
    }
    public function isAccessibleForUser(User $user)
    {
        if ($user->role == 'user') {
            if (!$user->tasks()->where('tasks.id', $this->id)->first()) {
                return false;
            }
        }
        return true;
    }
    public  function attachMentionedUsers()
    {
        $res = preg_match_all('/@([\w\.\d]+)/', $this->desc, $usernames);
        if ($res) {
            $userIds = User::whereIn('username', $usernames[1])->get()->pluck('id');
            $this->users()->sync($userIds);
        }
    }
    public  function attachUser(User $user)
    {
        $mention = new Mention();
        $mention->user_id = $user->id;
        $mention->task_id = $this->id;
        $mention->save();
    }
}
