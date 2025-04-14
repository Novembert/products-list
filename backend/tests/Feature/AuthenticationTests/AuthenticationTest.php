<?php

namespace Tests\Feature\AuthenticationTests;

use Tests\Feature\FeatureTestCase;

class AuthenticationTest extends FeatureTestCase
{
    public function testReturns401IfUserIsNotAuthenticated(): void
    {
      $this->withoutHeader('X-User-Id');
      $response = $this->getJson('/api/products');

      $response->assertStatus(401)
          ->assertJson([
              'error' => [
                  'message' => 'Unauthenticated',
              ],
          ]);
    }

    public function testAllowsAuthenticatedUserToAccessProtectedRoute(): void
    {
        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'vatRate',
                        'price',
                        'createdAt',
                        'updatedAt',
                    ],
                ],
            ]);
    }
}
