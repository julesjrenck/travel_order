<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TravelOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelOrderControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_store_a_travel_order()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $data = [
            'destination' => 'Nova York',
            'start_date' => '2025-01-01',
            'end_date' => '2025-01-10',
        ];

        $response = $this->postJson('/api/travel-orders', $data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'user_id',
            'requester_name',
            'destination',
            'start_date',
            'end_date',
            'status',
        ]);

        $this->assertDatabaseHas('travel_orders', [
            'user_id' => $user->id,
            'destination' => 'Nova York',
            'start_date' => '2025-01-01',
            'end_date' => '2025-01-10',
            'status' => 'solicitado',
        ]);
    }

    /** @test */
    public function it_can_update_a_travel_order_status()
    {
        $user = User::factory()->create();
        $travelOrder = TravelOrder::factory()->create(['user_id' => $user->id]);

        $data = [
            'status' => 'aprovado',
        ];

        $response = $this->actingAs($user, 'api')->putJson("/api/travel-orders/{$travelOrder->id}", $data);

        $response->assertStatus(200);
        $response->assertJsonFragment(['status' => 'aprovado']);
        $this->assertDatabaseHas('travel_orders', [
            'id' => $travelOrder->id,
            'status' => 'aprovado',
        ]);
    }

    /** @test */
    public function it_returns_404_when_travel_order_not_found()
    {
        $user = User::factory()->create();
        $nonExistentOrderId = 999999;

        $data = [
            'status' => 'aprovado',
        ];

        $response = $this->actingAs($user, 'api')->putJson("/api/travel-orders/{$nonExistentOrderId}", $data);

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Pedido de viagem não encontrado ou não autorizado.']);
    }

    /** @test */
    public function it_can_list_travel_orders()
    {
        $user = User::factory()->create();
        $travelOrders = TravelOrder::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'api')->getJson('/api/travel-orders');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
        $response->assertJsonFragment([
            'destination' => $travelOrders[0]->destination,
            'start_date' => $travelOrders[0]->start_date,
            'end_date' => $travelOrders[0]->end_date,
        ]);
    }

    /** @test */
    public function it_can_show_a_travel_order()
    {
        $user = User::factory()->create();
        $travelOrder = TravelOrder::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'api')->getJson("/api/travel-orders/{$travelOrder->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $travelOrder->id,
            'destination' => $travelOrder->destination,
            'start_date' => $travelOrder->start_date,
            'end_date' => $travelOrder->end_date,
        ]);
    }
}
