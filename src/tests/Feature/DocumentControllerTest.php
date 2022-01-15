<?php

namespace Tests\Feature;

use App\Document;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCaseWithMigrations;

class DocumentControllerTest extends TestCaseWithMigrations
{
    use RefreshDatabase;

    /**
     * Tests that default perPage is 20, page is 1, and response status is 200
     *
     * @return void
     */
    public function testListDefaultPagination()
    {
        $response = $this->get('/api/document');
        $response->assertStatus(200);

        $data = $response->json();
        $this->assertEquals(20, $data['pagination']['perPage']);
        $this->assertEquals(1, $data['pagination']['page']);
    }

    /**
     * Tests that response status is 404 when page * pageSize > (count of documents)
     *
     * @return void
     */
    public function testListOutOfRangePagination()
    {
        $response = $this->get('/api/document?page=2');
        $response->assertStatus(404);
    }

    /**
     * Tests that response status is 200 at first page when documents table is empty
     *
     * @return void
     * @throws Exception
     */
    public function testListFirstPageAlwaysShows()
    {
        foreach (Document::all() as $document) {
            $document->delete();
        }
        $response = $this->get('/api/document');
        $response->assertStatus(200);
    }

    /**
     * Tests that response status is 200 for correct show method
     *
     * @return void
     */
    public function testShowDocumentCorrect()
    {
        $response = $this->get('/api/document/' . Document::first()->id);
        $response->assertStatus(200);
    }

    /**
     * Tests that response status is 404 for incorrect show method
     *
     * @return void
     */
    public function testShowDocumentIncorrect()
    {
        $response = $this->get('/api/document/x');
        $response->assertStatus(404);
    }

    /**
     * Tests that response status is 201 for create method
     *
     * @return void
     */
    public function testCreate()
    {
        $response = $this->post('/api/document');
        $response->assertStatus(201);
    }

    protected static $correctRequestDataForUpdate = [
        'document' => [
            'payload' => [
                'x' => 'y'
            ]
        ]
    ];

    protected static $incorrectRequestDataForUpdate = [
        'document' => [
            'data' => [
                'x' => 'y'
            ]
        ]
    ];

    /**
     * Tests that response status is 200 for correct edit method
     *
     * @return void
     */
    public function testEditCorrect()
    {

        $response = $this->patchJson('/api/document/' . Document::first()->id, self::$correctRequestDataForUpdate);
        $response->assertStatus(200);
    }

    /**
     * Tests that response status is 201 for create method
     *
     * @return void
     */
    public function testEditPublishedDocument()
    {
        $document = Document::first();
        $document->publish()->save();

        $response = $this->patchJson('/api/document/' . $document->id, self::$correctRequestDataForUpdate);
        $response->assertStatus(400);
    }

    /**
     * Tests that response status is 201 for create method
     *
     * @return void
     */
    public function testEditWithoutPayload()
    {
        $id = Document::first()->id;
        $response = $this->patchJson('/api/document/' . $id, self::$incorrectRequestDataForUpdate);
        $response->assertStatus(400);
    }

    /**
     * Tests that response status is always 200 if correct document id given
     *
     * @return void
     */
    public function testPublish()
    {
        $id = Document::first()->id;
        for ($i = 0; $i < 2; $i++) {
            $response = $this->post('/api/document/' . $id . '/publish');
            $response->assertStatus(200);
        }
    }
}
