<?php

use Controllers\OrderController;
use Models\Division;
use Models\Order;
use Models\Product;
use Models\UnitType;
use Models\User;
use PHPUnit\Framework\TestCase;
use Src\Auth\Auth;
use Src\Request;

class CreateOrderTest extends TestCase
{
    private User $storekeeper;
    private User $otherStorekeeper;
    private Division $division;
    private Division $otherDivision;
    private UnitType $unitType;
    private Product $product;

    protected function setUp(): void
    {
        // Bootstrap the application
        $_SERVER['DOCUMENT_ROOT'] = __DIR__ . "/..";
        $GLOBALS['app'] = new Src\Application(new Src\Settings([
            'app' => include $_SERVER['DOCUMENT_ROOT'] . '/config/app.php',
            'db' => include $_SERVER['DOCUMENT_ROOT'] . '/config/db.php',
            'path' => include $_SERVER['DOCUMENT_ROOT'] . '/config/path.php',
        ]));

        if (!function_exists('app')) {
            function app()
            {
                return $GLOBALS['app'];
            }
        }

        // Create a storekeeper
        $this->storekeeper = User::create([
            'name' => 'Test',
            'surname' => 'Storekeeper',
            'patronymic' => 'Test',
            'login' => 'test_storekeeper',
            'password' => 'password',
            'email' => 'test_storekeeper@example.com',
            'phone' => '1234567890',
            'role_id' => 3, // Storekeeper
        ]);

        // Create another storekeeper
        $this->otherStorekeeper = User::create([
            'name' => 'Other',
            'surname' => 'Storekeeper',
            'patronymic' => 'Test',
            'login' => 'other_storekeeper',
            'password' => 'password',
            'email' => 'other_storekeeper@example.com',
            'phone' => '0987654321',
            'role_id' => 3, // Storekeeper
        ]);

        // Create a division and assign the first storekeeper to it
        $this->division = Division::create([
            'name' => 'Test Division',
            'user_id' => $this->storekeeper->id,
        ]);

        // Create another division and assign the other storekeeper to it
        $this->otherDivision = Division::create([
            'name' => 'Other Division',
            'user_id' => $this->otherStorekeeper->id,
        ]);

        // Create a unit type
        $this->unitType = UnitType::create(['name' => 'Test Unit']);

        // Create a product
        $this->product = Product::create([
            'name' => 'Test Product',
            'articul' => "Articul",
            'unit_type_id' => $this->unitType->id,
        ]);
    }

    public function testStorekeeperCanCreateOrder()
    {

        // Mock the request
        $request = $this->createMock(Request::class);
        $request->expects($this->any())
            ->method('all')
            ->willReturn([
                'product_id' => $this->product->id,
                'count' => 5,
            ]);
        $request->method = 'POST';
        Auth::login($this->storekeeper);

        // Instantiate the controller and call the method
        (new OrderController())->store($request);

        // Assert that the order was created
        $this->assertNotNull(Order::where([
            'division_id' => $this->division->id,
            'product_id' => $this->product->id,
            'count' => 5,
        ])->first());
    }

    public function testGhostCannotCreateOrder()
    {
        // Log out
        Auth::logout();

        // Mock the request
        $request = $this->createMock(Request::class);
        $request->expects($this->any())
            ->method('all')
            ->willReturn([
                'product_id' => $this->product->id,
                'count' => 5,
            ]);
        $request->method = 'POST';

        // Instantiate the controller and call the method
        (new OrderController())->store($request);

        // Assert that the order was not created
        $this->assertNull(Order::where([
            'product_id' => $this->product->id,
            'count' => 5,
        ])->first());
    }

    public function testStorekeeperCannotCreateOrderForAnotherDivision()
    {
        // Log in as the storekeeper
        Auth::login($this->storekeeper);

        // Mock the request
        $request = $this->createMock(Request::class);
        $request->expects($this->any())
            ->method('all')
            ->willReturn([
                'product_id' => $this->product->id,
                'count' => 5,
                'division_id' => $this->otherDivision->id, // Try to create order for another division
            ]);
        $request->method = 'POST';

        // Instantiate the controller and call the method
        (new OrderController())->store($request);

        // Assert that the order was not created for the other division
        $this->assertNull(Order::where([
            'division_id' => $this->otherDivision->id,
            'product_id' => $this->product->id,
            'count' => 5,
        ])->first());
    }

    protected function tearDown(): void
    {
        // Clean up created data
        Order::where('division_id', $this->division->id)->delete();
        $this->division->delete();
        $this->otherDivision->delete();
        $this->storekeeper->delete();
        $this->otherStorekeeper->delete();
        $this->product->delete();
        $this->unitType->delete();
    }
}
