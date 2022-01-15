<?php

namespace Tests\Unit;

use App\Document;
use Tests\TestCase;

class DocumentPayloadMergeTest extends TestCase
{
    /**
     * A normal data merging.
     *
     * @return void
     */
    public function testExample()
    {
        $document = new Document();

        $document->payload = [
            'actor' => 'The fox',
            'meta' => [
                'type' => 'quick',
                'color' => 'brown'
            ],
            'actions' => [
                [
                    'action' => 'jump over',
                    'actor' => 'lazy dog'
                ]
            ]
        ];

        $document->mergePayload([
            'meta' => [
                'type' => 'cunning',
                'color' => null
            ],
            'actions' => [
                [
                    'action' => 'eat',
                    'actor' => 'blob'
                ],
                [
                    'action' => 'run away'
                ]
            ]
        ]);

        $neededResult = [
            'actor' => 'The fox',
            'meta' => [
                'type' => 'cunning'
            ],
            'actions' => [
                [
                    'action' => 'eat',
                    'actor' => 'blob'
                ],
                [
                    'action' => 'run away'
                ]
            ]
        ];

        $this->assertEquals($neededResult, $document->payload);
    }
}
