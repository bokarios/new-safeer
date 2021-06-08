<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\Trip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_bus_can_be_added_to_the_system()
    {
        // $this->withoutExceptionHandling();

        $this->post('/companies', ['name' => 'Company', 'description' => 'Description']);

        $response = $this->post('/buses', [
            'name' => 'Bus name',
            'plate_num' => 'kh200',
            'company_id' => 1,
        ]);

        $response->assertOk();
        $this->assertCount(1, Bus::all());
    }

    /** @test */
    public function a_bus_can_be_updated_in_the_system()
    {
        $this->withoutExceptionHandling();

        $this->post('/companies', ['name' => 'Company', 'description' => 'Description']);

        $this->post('/buses', [
            'name' => 'Bus name',
            'plate_num' => 'kh200',
            'company_id' => 1,
        ]);

        $bus = Bus::first();

        $response = $this->patch('/buses/' . $bus->id, [
            'name' => 'Bus name new',
            'plate_num' => 'kh201',
        ]);

        $response->assertOk();
        $this->assertEquals('Bus name new', $bus->refresh()->name);
        $this->assertNotEquals('kh200', $bus->refresh()->plate_num);
        $this->assertCount(1, Bus::all());
    }

    /** @test */
    public function a_bus_can_be_deleted_from_the_system()
    {
        $this->withoutExceptionHandling();

        $this->post('/companies', ['name' => 'Company', 'description' => 'Description']);

        $this->post('/buses', [
            'name' => 'Bus name',
            'plate_num' => 'kh200',
            'company_id' => 1,
        ]);

        $bus = Bus::first();

        $response = $this->delete('/buses/' . $bus->id);

        $response->assertOk();
        $this->assertCount(0, Bus::all());
    }

    /** @test */
    public function bus_name_is_required()
    {
        // $this->withoutExceptionHandling();

        $this->post('/companies', ['name' => 'Company', 'description' => 'Description']);

        $response = $this->post('/buses', [
            'name' => '',
            'plate_num' => 'kh200',
            'company_id' => 1,
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertCount(0, Bus::all());
    }

    /** @test */
    public function bus_plate_number_is_required()
    {
        // $this->withoutExceptionHandling();

        $this->post('/companies', ['name' => 'Company', 'description' => 'Description']);

        $response = $this->post('/buses', [
            'name' => 'bus',
            'plate_num' => '',
            'company_id' => 1,
        ]);

        $response->assertSessionHasErrors('plate_num');
        $this->assertCount(0, Bus::all());
    }

    /** @test */
    public function bus_company_id_is_required()
    {
        // $this->withoutExceptionHandling();

        $this->post('/companies', ['name' => 'Company', 'description' => 'Description']);

        $response = $this->post('/buses', [
            'name' => 'bus',
            'plate_num' => 'kh200',
            'company_id' => '',
        ]);

        $response->assertSessionHasErrors('company_id');
        $this->assertCount(0, Bus::all());
    }

    /** @test */
    public function bus_plate_number_is_unique()
    {
        $this->post('/companies', ['name' => 'Company', 'description' => 'Description']);

        $this->post('/buses', [
            'name' => 'bus',
            'plate_num' => 'kh200',
            'company_id' => 1,
        ]);

        $this->post('/companies', ['name' => 'Company2', 'description' => 'Description2']);

        $response = $this->post('/buses', [
            'name' => 'bus2',
            'plate_num' => 'kh200',
            'company_id' => 2,
        ]);

        $response->assertSessionHasErrors('plate_num', 'this plate number is taken');
        $this->assertCount(1, Bus::all());
    }

    /** @test */
    public function all_trips_will_cascade_on_delete()
    {
        $this->post('/companies', ['name' => 'Company', 'description' => 'Description']);

        $this->post('/buses', [
            'name' => 'bus',
            'plate_num' => 'kh200',
            'company_id' => 1,
        ]);

        $this->post('/trips', [
            'source' => 'kh',
            'destination' => 'madani',
            'attend_time' => '12:00 pm',
            'leave_time' => '01:00 pm',
            'bus_id' => 1,
            'ticket' => 200,
            'seats' => 90
        ]);
        $this->post('/trips', [
            'source' => 'kh',
            'destination' => 'madani',
            'attend_time' => '12:00 pm',
            'leave_time' => '01:00 pm',
            'bus_id' => 1,
            'ticket' => 210,
            'seats' => 90
        ]);

        $bus = Bus::first();
        $this->delete('/buses/' . $bus->id);

        $this->assertCount(0, Trip::all());
    }
}
