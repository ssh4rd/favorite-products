<?php

namespace Tests\Feature;

use App\Models\FavoriteList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteListAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $otherUser;
    private string $token;
    private string $otherToken;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->otherUser = User::factory()->create([
            'name' => 'Other User',
            'email' => 'other@example.com',
        ]);

        // Create API tokens (simple auth implementation)
        $this->token = 'test-token-' . $this->user->id;
        $this->otherToken = 'test-token-' . $this->otherUser->id;
    }

    public function test_user_can_get_their_own_favorite_lists()
    {
        // Create favorite lists for the user
        $list1 = FavoriteList::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'My Electronics',
        ]);

        $list2 = FavoriteList::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Books Collection',
        ]);

        // Create a list for another user (should not be visible)
        FavoriteList::factory()->create([
            'user_id' => $this->otherUser->id,
            'name' => 'Other User List',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/lists');

        $response->assertStatus(200)
                ->assertJsonCount(2)
                ->assertJsonFragment([
                    'id' => $list1->id,
                    'userId' => $this->user->id,
                    'name' => 'My Electronics',
                ])
                ->assertJsonFragment([
                    'id' => $list2->id,
                    'userId' => $this->user->id,
                    'name' => 'Books Collection',
                ])
                ->assertJsonMissing([
                    'name' => 'Other User List',
                ]);
    }

    public function test_user_cannot_access_lists_without_authentication()
    {
        $response = $this->getJson('/api/lists');

        $response->assertStatus(401);
    }

    public function test_user_cannot_access_lists_with_invalid_token()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid-token',
        ])->getJson('/api/lists');

        $response->assertStatus(401);
    }

    public function test_user_can_get_specific_favorite_list_with_products()
    {
        $list = FavoriteList::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Electronics List',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/lists/' . $list->id);

        $response->assertStatus(200)
                ->assertJson([
                    'list' => [
                        'id' => $list->id,
                        'userId' => $this->user->id,
                        'name' => 'Electronics List',
                    ],
                    'products' => [],
                ]);
    }

    public function test_user_cannot_access_other_users_favorite_list()
    {
        $otherUserList = FavoriteList::factory()->create([
            'user_id' => $this->otherUser->id,
            'name' => 'Other User Private List',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/lists/' . $otherUserList->id);

        $response->assertStatus(404);
    }

    public function test_user_can_create_favorite_list()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/lists', [
            'name' => 'New Favorite List',
        ]);

        $response->assertStatus(201)
                ->assertJson([
                    'userId' => $this->user->id,
                    'name' => 'New Favorite List',
                ]);

        $this->assertDatabaseHas('favorite_lists', [
            'user_id' => $this->user->id,
            'name' => 'New Favorite List',
        ]);
    }

    public function test_user_cannot_create_favorite_list_without_name()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/lists', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name']);
    }

    public function test_user_can_update_their_own_favorite_list()
    {
        $list = FavoriteList::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Old Name',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/lists/' . $list->id, [
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $list->id,
                    'userId' => $this->user->id,
                    'name' => 'Updated Name',
                ]);

        $this->assertDatabaseHas('favorite_lists', [
            'id' => $list->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_user_cannot_update_other_users_favorite_list()
    {
        $otherUserList = FavoriteList::factory()->create([
            'user_id' => $this->otherUser->id,
            'name' => 'Other User List',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/lists/' . $otherUserList->id, [
            'name' => 'Hacked Name',
        ]);

        $response->assertStatus(404);

        $this->assertDatabaseMissing('favorite_lists', [
            'id' => $otherUserList->id,
            'name' => 'Hacked Name',
        ]);
    }

    public function test_user_can_delete_their_own_favorite_list()
    {
        $list = FavoriteList::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'List to Delete',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson('/api/lists/' . $list->id);

        $response->assertStatus(200)
                ->assertJson(['message' => 'List deleted']);

        $this->assertSoftDeleted('favorite_lists', [
            'id' => $list->id,
        ]);
    }

    public function test_user_cannot_delete_other_users_favorite_list()
    {
        $otherUserList = FavoriteList::factory()->create([
            'user_id' => $this->otherUser->id,
            'name' => 'Other User List',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson('/api/lists/' . $otherUserList->id);

        $response->assertStatus(404);

        $this->assertDatabaseHas('favorite_lists', [
            'id' => $otherUserList->id,
            'deleted_at' => null,
        ]);
    }

    public function test_getting_nonexistent_list_returns_404()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/lists/99999');

        $response->assertStatus(404);
    }

    public function test_updating_nonexistent_list_returns_404()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/lists/99999', [
            'name' => 'New Name',
        ]);

        $response->assertStatus(404);
    }

    public function test_deleting_nonexistent_list_returns_404()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson('/api/lists/99999');

        $response->assertStatus(404);
    }
}
