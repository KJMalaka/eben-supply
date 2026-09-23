<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EbenSupplyTest extends TestCase
{
    use RefreshDatabase;

    // Test 1: Landing page loads for guests
    public function test_landing_page_loads()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /* Test 2: Guest cannot access admin orders
    public function test_guest_cannot_access_admin_orders()
    {
        $response = $this->get('admin.orders.index');
        $response->assertRedirect('/login');
    } */

    // Test 3: Guest cannot access checkout
    public function test_guest_cannot_access_checkout()
    {
        $response = $this->get('/checkout');
        $response->assertRedirect('/login');
    }

    // Test 4: Login page loads
    public function test_login_page_loads()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    // Test 5: Register page loads
    public function test_register_page_loads()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    // Test 6: Products page loads
    public function test_products_page_loads(): void
    {
        $response = $this->get('/products');
        $response->assertStatus(200);
    }

    // Test 7: Cart page loads
    public function test_cart_page_loads(): void
    {
        $response = $this->get('/cart');
        $response->assertStatus(200);
    }
}