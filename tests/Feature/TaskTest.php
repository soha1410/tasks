<?php

namespace Tests\Feature;

use App\Models\Mention;
use App\Models\Task;
use Tests\TestCase;

class TaskTest extends TestCase
{
    public function test_create_task()
    {
        $jwt = $this->loginAs('user');
        $data = Task::factory()->definition();
        $response = $this->json('post', '/api/tasks', $data, ['Authorization' => $jwt]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'desc'
            ]
        ]);
    }

    public function test_update_task()
    {
        $jwt = $this->loginAs('user');
        $data = Task::factory()->definition();
        $user = auth()->user();
        $task = $user->tasks()->inRandomOrder()->first();
        $response = $this->json('put', "/api/tasks/{$task->id}", $data, ['Authorization' => $jwt]);
        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'id' => $task->id,
                'title' => $data['title'],
                'desc' => $data['desc']
            ]
        ]);
    }

    public function test_list_task()
    {
        $jwt = $this->loginAs('user');
        $response = $this->json('get', '/api/tasks', [], ['Authorization' => $jwt]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [
            [
                'id',
                'title',
                'desc'
            ]
        ]]);
    }

    public function test_delete_task()
    {
        $jwt = $this->loginAs('user');
        $user = auth()->user();
        $task = $user->tasks()->inRandomOrder()->first();
        $response = $this->json('delete', "/api/tasks/{$task->id}", [], ['Authorization' => $jwt]);
        $response->assertStatus(201);
    }

    public function test_delete_others_task()
    {
        $jwt = $this->loginAs('user');
        $user = auth()->user();
        $task = Mention::where('user_id', '!=', $user->id)->inRandomOrder()->first()->task;
        $response = $this->json('delete', "/api/tasks/{$task->id}", [], ['Authorization' => $jwt]);
        $response->assertStatus(403);
    }
}
