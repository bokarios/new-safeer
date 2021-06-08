<?php

namespace Tests\Feature;

use App\Models\Trip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TripTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_trip_can_be_added_to_the_system()
    {
        // $this->withoutExceptionHandling();

        $this->post('/companies', ['name' => 'Company', 'description' => 'Description']);

        $this->post('/buses', [
            'name' => 'Bus name',
            'plate_num' => 'kh200',
            'company_id' => 1,
        ]);

        $response = $this->post('/trips', [
            'source' => 'Khartoum',
            'destination' => 'madani',
            'attend_time' => '12:00 pm',
            'leave_time' => '01:00 pm',
            'bus_id' => 1,
            'ticket' => 200,
            'seats' => 90
        ]);

        $response->assertOk();
        $this->assertCount(1, Trip::all());
    }

    /** @test */
    public function a_trip_can_be_updated_in_the_system()
    {
        // $this->withoutExceptionHandling();

        $this->post('/companies', ['name' => 'Company', 'description' => 'Description']);

        $this->post('/buses', [
            'name' => 'Bus name',
            'plate_num' => 'kh200',
            'company_id' => 1,
        ]);

        $this->post('/trips', [
            'source' => 'Khartoum',
            'destination' => 'madani',
            'attend_time' => '12:00 pm',
            'leave_time' => '01:00 pm',
            'bus_id' => 1,
            'ticket' => 200,
            'seats' => 90
        ]);

        $trip = Trip::first();

        $response = $this->patch('/trips/' . $trip->id, [
            'source' => 'Khartoum',
            'destination' => 'madani',
            'attend_time' => '11:00 pm',
            'leave_time' => '12:00 pm',
            'ticket' => 220,
            'seats' => 100
        ]);

        $response->assertOk();
        $this->assertEquals('11:00 pm', $trip->refresh()->attend_time);
        $this->assertNotEquals(200, $trip->refresh()->ticket);
        $this->assertCount(1, Trip::all());
    }

    /** @test */
    public function a_trip_can_be_deleted_from_the_system()
    {
        // $this->withoutExceptionHandling();

        $this->post('/companies', ['name' => 'Company', 'description' => 'Description']);

        $this->post('/buses', [
            'name' => 'Bus name',
            'plate_num' => 'kh200',
            'company_id' => 1,
        ]);

        $this->post('/trips', [
            'source' => 'Khartoum',
            'destination' => 'madani',
            'attend_time' => '12:00 pm',
            'leave_time' => '01:00 pm',
            'bus_id' => 1,
            'ticket' => 200,
            'seats' => 90
        ]);

        $trip = Trip::first();

        $response = $this->delete('/trips/' . $trip->id);

        $response->assertOk();
        $this->assertCount(0, Trip::all());
    }

    /** @test */
    public function trip_source_is_required()
    {
        // $this->withoutExceptionHandling();

        $this->post('/companies', ['name' => 'Company', 'description' => 'Description']);

        $this->post('/buses', [
            'name' => 'Bus name',
            'plate_num' => 'kh200',
            'company_id' => 1,
        ]);

        $response = $this->post('/trips', [
            'source' => '',
            'destination' => 'madani',
            'attend_time' => '12:00 pm',
            'leave_time' => '01:00 pm',
            'bus_id' => 1,
            'ticket' => 200,
            'seats' => 90
        ]);

        $response->assertSessionHasErrors('source');
        $this->assertCount(0, Trip::all());
    }

    /** @test */
    public function trip_destination_is_required()
    {
        // $this->withoutExceptionHandling();

        $this->post('/companies', ['name' => 'Company', 'description' => 'Description']);

        $this->post('/buses', [
            'name' => 'Bus name',
            'plate_num' => 'kh200',
            'company_id' => 1,
        ]);

        $response = $this->post('/trips', [
            'source' => 'kh',
            'destination' => '',
            'attend_time' => '12:00 pm',
            'leave_time' => '01:00 pm',
            'bus_id' => 1,
            'ticket' => 200,
            'seats' => 90
        ]);

        $response->assertSessionHasErrors('destination');
        $this->assertCount(0, Trip::all());
    }

    /** @test */
    public function trip_attend_time_is_required()
    {
        // $this->withoutExceptionHandling();

        $this->post('/companies', ['name' => 'Company', 'description' => 'Description']);

        $this->post('/buses', [
            'name' => 'Bus name',
            'plate_num' => 'kh200',
            'company_id' => 1,
        ]);

        $response = $this->post('/trips', [
            'source' => 'kh',
            'destination' => 'madani',
            'attend_time' => '',
            'leave_time' => '01:00 pm',
            'bus_id' => 1,
            'ticket' => 200,
            'seats' => 90
        ]);

        $response->assertSessionHasErrors('attend_time');
        $this->assertCount(0, Trip::all());
    }

    /** @test */
    public function trip_leave_time_is_required()
    {
        // $this->withoutExceptionHandling();

        $this->post('/companies', ['name' => 'Company', 'description' => 'Description']);

        $this->post('/buses', [
            'name' => 'Bus name',
            'plate_num' => 'kh200',
            'company_id' => 1,
        ]);

        $response = $this->post('/trips', [
            'source' => 'kh',
            'destination' => 'madani',
            'attend_time' => '12:00 pm',
            'leave_time' => '',
            'bus_id' => 1,
            'ticket' => 200,
            'seats' => 90
        ]);

        $response->assertSessionHasErrors('leave_time');
        $this->assertCount(0, Trip::all());
    }

    /** @test */
    public function trip_bus_id_is_required()
    {
        // $this->withoutExceptionHandling();

        $this->post('/companies', ['name' => 'Company', 'description' => 'Description']);

        $this->post('/buses', [
            'name' => 'Bus name',
            'plate_num' => 'kh200',
            'company_id' => 1,
        ]);

        $response = $this->post('/trips', [
            'source' => '',
            'destination' => 'madani',
            'attend_time' => '12:00 pm',
            'leave_time' => '01:00 pm',
            'bus_id' => null,
            'ticket' => 200,
            'seats' => 90
        ]);

        $response->assertSessionHasErrors('bus_id');
        $this->assertCount(0, Trip::all());
    }

    /** @test */
    public function trip_ticket_is_required()
    {
        // $this->withoutExceptionHandling();

        $this->post('/companies', ['name' => 'Company', 'description' => 'Description']);

        $this->post('/buses', [
            'name' => 'Bus name',
            'plate_num' => 'kh200',
            'company_id' => 1,
        ]);

        $response = $this->post('/trips', [
            'source' => 'kh',
            'destination' => 'madani',
            'attend_time' => '12:00 pm',
            'leave_time' => '01:00 pm',
            'bus_id' => 1,
            'ticket' => null,
            'seats' => 90
        ]);

        $response->assertSessionHasErrors('ticket');
        $this->assertCount(0, Trip::all());
    }

    /** @test */
    public function trip_seats_is_required()
    {
        // $this->withoutExceptionHandling();

        $this->post('/companies', ['name' => 'Company', 'description' => 'Description']);

        $this->post('/buses', [
            'name' => 'Bus name',
            'plate_num' => 'kh200',
            'company_id' => 1,
        ]);

        $response = $this->post('/trips', [
            'source' => 'Khartoum',
            'destination' => 'madani',
            'attend_time' => '12:00 pm',
            'leave_time' => '01:00 pm',
            'bus_id' => 1,
            'ticket' => 200,
            'seats' => null
        ]);

        $response->assertSessionHasErrors('seats');
        $this->assertCount(0, Trip::all());
    }
}
