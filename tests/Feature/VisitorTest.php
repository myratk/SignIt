<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Visitor;
use App\Models\User;

class VisitorTest extends TestCase
{
    public function test_index_not_authorized_user_is_redirected()
    {
        $response = $this->get('/visitors');
        $response->assertRedirect('/login');
    }

    public function test_index_authorized_user_can_see_message_board()
    {
        $visitor = Visitor::factory()->create();
        $response = $this->followingRedirects()->actingAs($visitor->user)->get('/visitors');

        $response->assertOk();
        $response->assertViewIs('visitors.index');

        $expectedPage1NameData = Visitor::orderBy('created_at', 'desc')
            ->take(20)
            ->pluck('comments');

        $response->assertSeeInOrder(array_merge([
            'All of our visitors'
        ], $expectedPage1NameData->toArray()));
    }

    public function test_show_not_authorized_user_is_redirected()
    {
        $newComments = 'Some test comments';
        $visitor = Visitor::factory()->create();
        $response = $this->get("/visitors/{$visitor->id}", [
            'comments' =>$newComments
        ]);
        $response->assertRedirect('/login');
    }

    public function test_authorized_user_can_see_own_message()
    {
        $newComments = 'Some test comments';
        $visitor = Visitor::factory()->create();
        $response = $this->actingAs($visitor->user)
            ->followingRedirects()
            ->patch("/visitors/{$visitor->id}", [
                'comments' => $newComments
            ]);

        $response->assertOk();
        $response->assertViewIs('visitors.show{}')
    }

    /*
    public function test_update_visitors()
    {
        $newComments = 'Some test comments';
        $visitor = Visitor::factory()->create();

        $response = $this->actingAs($visitor->user)
            ->followingRedirects()
            ->patch("/visitors/{$visitor->id}", [
                'comments' => $newComments
            ]);

        $newVisitor = $visitor->fresh();

        $response->assertOk();
        $this->assertEquals($newComments, $newVisitor->comments);

    }

    public function test_update_visitors_wrong_user() {
        $newComments = 'Some test comments';
        $visitor = Visitor::factory()->create();
        $wrongUser = User::factory()->create();

        $response = $this->actingAs($wrongUser)
            ->followingRedirects()
            ->patch("/visitors/{$visitor->id}", [
                'comments' => $newComments
            ]);

        $newVisitor = $visitor->fresh();
        $response->assertUnauthorized();
        $this->assertNotEquals($newComments, $newVisitor->comments);
    } */
}
