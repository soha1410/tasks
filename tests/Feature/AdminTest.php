<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminTest extends TestCase
{
    public function test_join_task()
    {
        $jwt = $this->loginAs('admin');
        $user = auth()->user();
        $task = Task::inRandomOrder()->first();
        $response = $this->json('patch', "/api/tasks/{$task->id}", [], ['Authorization' => $jwt]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [
            'id',
            'title',
            'desc'
        ]]);
        $this->assertTrue($task->isAccessibleForUser($user));
    }
    public function test_list_tasks()
    {
        $jwt = $this->loginAs('admin');
        $response = $this->json('get', '/api/tasks', [], ['Authorization' => $jwt]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'title',
                    'desc'
                ]
            ]
        ]);
    }
    public function test_list_users()
    {
        $jwt = $this->loginAs('admin');
        $response = $this->json('get', '/api/users', [], ['Authorization' => $jwt]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [[
                'id',
                'username',
                'role'
            ]]
        ]);
    }
}
