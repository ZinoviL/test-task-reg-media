<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use PhpParser\Builder;


/**
 * @mixin Builder
 */
class Document extends Model
{
    const STATUS_PUBLISHED = 'published';
    const STATUS_DRAFT = 'draft';

    /**
     * @inheritdoc
     */
    protected $casts = [
        'id' => 'string',
        'payload' => 'array',
    ];

    /**
     * @inheritdoc
     */
    public $incrementing = false;

    /**
     * @inheritdoc
     */
    protected $attributes = [
        'id' => null,
        'status' => self::STATUS_DRAFT,
        'payload' => '{}',
        'updated_at' => null,
        'created_at' => null,
    ];

    /**
     * Puts UUID at id field when creating event called
     * @inheritdoc
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid()->toString();
        });
    }

    /**
     * Merges old data with new (wraps recursive function)
     *
     * @param array $newData
     * @return $this
     */
    public function mergePayload(array $newData): Document
    {
        $oldData = $this->payload;

        $this->payload = $this->mergePayloadRecursive($newData, $oldData);

        return $this;
    }

    /**
     * Merges old data with new
     *
     * @param array $newData
     * @param array $oldData
     * @return array
     */
    protected function mergePayloadRecursive(array $newData, array &$oldData): array
    {
        foreach ($newData as $key => $item) {
            if (isset($oldData[$key]) && is_array($oldData[$key]) && is_array($item)) {
                $this->mergePayloadRecursive($item, $oldData[$key]);
            } else {
                if ($item === null) {
                    unset($oldData[$key]);
                } else {
                    $oldData[$key] = $item;
                }
            }
        }

        return $oldData;
    }

    /**
     * Checks if the document is published
     *
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->status == self::STATUS_PUBLISHED;
    }

    public function publish(): Document
    {
        $this->status = self::STATUS_PUBLISHED;
        return $this;
    }
}
