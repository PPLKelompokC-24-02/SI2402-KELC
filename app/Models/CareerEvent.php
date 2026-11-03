<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CareerEvent extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    /**
     * Kategori umum event pengembangan karier.
     * Dapat digunakan sebagai opsi kategori pada form dan filter.
     */
    public const CATEGORIES = [
        'Webinar',
        'Seminar',
        'Workshop',
        'Career Fair',
        'Info Session',
        'Sertifikasi',
        'Kompetisi',
    ];

    protected $attributes = [
        'status' => self::STATUS_PUBLISHED,
    ];

    protected $fillable = [
        'title',
        'slug',
        'category',
        'description',
        'organizer',
        'start_at',
        'end_at',
        'location',
        'poster_path',
        'status',
        'registration_url',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }

    /**
     * Generate unique slug automatically from title.
     */
    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 1;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Helper untuk memeriksa apakah status event adalah published.
     */
    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    /**
     * Scope untuk pencarian keyword (judul, penyelenggara, lokasi).
     */
    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        if (blank($keyword)) {
            return $query;
        }

        return $query->where(function ($q) use ($keyword) {
            $q->where('title', 'like', '%'.$keyword.'%')
              ->orWhere('organizer', 'like', '%'.$keyword.'%')
              ->orWhere('location', 'like', '%'.$keyword.'%');
        });
    }

    /**
     * Scope untuk filter kategori.
     */
    public function scopeCategory(Builder $query, ?string $category): Builder
    {
        if (blank($category)) {
            return $query;
        }

        return $query->where('category', $category);
    }

    /**
     * Scope untuk filter status (draft / published).
     */
    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        if (blank($status)) {
            return $query;
        }

        return $query->where('status', $status);
    }
}

