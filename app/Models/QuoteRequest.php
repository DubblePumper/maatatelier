<?php

namespace App\Models;

use Database\Factories\QuoteRequestFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Support\Facades\Storage;

class QuoteRequest extends Model
{
    /** @use HasFactory<QuoteRequestFactory> */
    use HasFactory, Prunable;

    /** @var list<string> */
    protected $fillable = [
        'project_type',
        'dimensions_are_approximate',
        'width_mm',
        'height_mm',
        'depth_mm',
        'layout_columns',
        'finish',
        'features',
        'style',
        'budget',
        'timing',
        'notes',
        'attachments',
        'name',
        'email',
        'phone',
        'postal_code',
        'consent_at',
        'status',
    ];

    /** @var array<string, mixed> */
    protected $attributes = [
        'status' => 'new',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'dimensions_are_approximate' => 'boolean',
            'features' => 'array',
            'attachments' => 'array',
            'consent_at' => 'datetime',
        ];
    }

    public function prunable(): Builder
    {
        return static::where('created_at', '<=', now()->subYear());
    }

    protected function pruning(): void
    {
        Storage::disk('local')->delete(
            collect($this->attachments ?? [])->pluck('path')->filter()->all(),
        );
    }
}
