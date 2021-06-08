<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_company_can_be_added_to_the_system()
    {
        // $this->withoutExceptionHandling();

        $response = $this->post('/companies', [
            'name' => 'The Company',
            'description' => 'Some description',
        ]);

        $response->assertOk();
        $this->assertCount(1, Company::all());
    }

    /** @test */
    public function a_company_can_be_updated_in_the_system()
    {
        $this->withoutExceptionHandling();

        $this->post('/companies', [
            'name' => 'The Company',
            'description' => 'The description',
        ]);

        $company = Company::first();

        $response = $this->patch('/companies/' . $company->id, [
            'name' => 'The Updated Company',
            'description' => 'The updated description',
        ]);

        $response->assertOk();
        $this->assertCount(1, Company::all());
    }

    /** @test */
    public function a_company_can_be_deleted_from_the_system()
    {
        $this->withoutExceptionHandling();

        $this->post('/companies', [
            'name' => 'The Company',
            'description' => 'The description',
        ]);

        $company = Company::first();

        $response = $this->delete('/companies/' . $company->id);

        $response->assertOk();
        $this->assertCount(0, Company::all());
    }

    /** @test */
    public function company_name_is_required()
    {
        $response = $this->post('/companies', [
            'name' => '',
            'description' => 'something',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertCount(0, Company::all());
    }

    /** @test */
    public function company_description_is_required()
    {
        $response = $this->post('/companies', [
            'name' => 'bus name',
            'description' => '',
        ]);

        $response->assertSessionHasErrors('description');
        $this->assertCount(0, Company::all());
    }

    /** @test */
    public function all_buses_will_cascade_on_delete()
    {
        $this->withoutExceptionHandling();

        $this->post('/companies', [
            'name' => 'bus name',
            'description' => 'desc',
        ]);

        $this->post('/buses', [
            'name' => 'Bus name',
            'plate_num' => 'kh200',
            'company_id' => 1,
        ]);
        $this->post('/buses', [
            'name' => 'Bus name 2',
            'plate_num' => 'kh201',
            'company_id' => 1,
        ]);

        $this->assertCount(2, Bus::all());

        $company = Company::first();
        $this->delete('/companies/' . $company->id);

        $this->assertCount(0, Company::all());
        $this->assertCount(0, Bus::all());
    }
}
