<?php

namespace App\Http\Requests;

use App\Models\CareerEvent;
use Illuminate\Foundation\Http\FormRequest;

class AdminCareerEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        $event = $this->route('event');

        return $event instanceof CareerEvent
            ? $this->user()?->can('update', $event) ?? false
            : $this->user()?->can('create', CareerEvent::class) ?? false;
    }

    protected function prepareForValidation(): void
    {
        $status = $this->input('status');
        if ($this->input('action') === 'draft') {
            $status = CareerEvent::STATUS_DRAFT;
        } elseif ($this->input('action') === 'publish') {
            $status = CareerEvent::STATUS_PUBLISHED;
        }

        if ($status) {
            $this->merge(['status' => $status]);
        }

        // If date & time are passed separately in the form
        if ($this->filled('event_date') && ! $this->filled('start_at')) {
            $time = $this->input('event_time', '09:00');
            $this->merge(['start_at' => $this->input('event_date').' '.$time]);
        }
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'organizer' => ['required', 'string', 'max:150'],
            'start_at' => ['required', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'location' => ['required', 'string', 'max:255'],
            'poster' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_poster' => ['nullable', 'boolean'],
            'status' => ['required', 'in:draft,published'],
            'registration_url' => ['required', 'string', 'url', 'max:500', 'starts_with:http://,https://'],
        ];
    }

    public function messages(): array
    {
        return [
            // AC-05: Validasi Data Wajib
            'title.required' => 'Judul event wajib diisi.',
            'category.required' => 'Kategori event wajib dipilih.',
            'description.required' => 'Deskripsi event wajib diisi.',
            'organizer.required' => 'Penyelenggara event wajib diisi.',
            'start_at.required' => 'Tanggal dan waktu mulai event wajib diisi.',
            'location.required' => 'Lokasi event wajib diisi.',
            'status.required' => 'Status event wajib ditentukan.',
            'registration_url.required' => 'Tautan pendaftaran resmi wajib diisi.',

            // AC-05: Validasi Format Tanggal
            'start_at.date' => 'Format tanggal dan waktu mulai tidak valid.',
            'end_at.date' => 'Format tanggal dan waktu selesai tidak valid.',
            'end_at.after_or_equal' => 'Waktu selesai harus setelah atau sama dengan waktu mulai.',

            // AC-05: Validasi Format Tautan Pendaftaran
            'registration_url.url' => 'Format tautan pendaftaran tidak valid. Masukkan URL yang sah.',
            'registration_url.starts_with' => 'Format tautan pendaftaran harus diawali dengan http:// atau https://.',

            // Poster & Status
            'poster.image' => 'Poster harus berupa file gambar.',
            'poster.mimes' => 'Format poster yang didukung adalah JPG, JPEG, PNG, atau WEBP.',
            'poster.max' => 'Ukuran poster maksimal 2 MB.',
            'status.in' => 'Status event harus berupa draft atau published.',
        ];
    }
}
