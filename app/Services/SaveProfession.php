<?php

namespace App\Services;

use App\Http\Requests\WebProfessionRequest;
use App\Models\Profession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class SaveProfession
{
    public function handle(WebProfessionRequest $request, ?Profession $profession = null): Profession
    {
        $createdFiles = [];
        $retiredFiles = [];
        try {
            $profession = DB::transaction(function () use ($request, $profession, &$createdFiles, &$retiredFiles) {
                $profession = $profession?->exists ? Profession::lockForUpdate()->findOrFail($profession->id) : new Profession;
                $data = $request->validated();
                $values = collect($data)->only(['name', 'career_category_id', 'summary', 'description', 'entry_level', 'career_start', 'career_end', 'industries', 'prospects', 'responsibilities'])->all();
                $values['illustration_path'] = $profession->illustration_path;
                if ($request->boolean('remove_illustration') || $request->hasFile('illustration')) {
                    if ($profession->illustration_path) {
                        $retiredFiles[] = $profession->illustration_path;
                    }
                    $values['illustration_path'] = null;
                }
                if ($request->hasFile('illustration')) {
                    $values['illustration_path'] = $this->store($request->file('illustration'), $createdFiles);
                }
                $oldTools = collect($profession->tools ?? [])->keyBy('key');
                $tools = [];
                foreach ($data['tools'] ?? [] as $index => $tool) {
                    $existing = $oldTools->get($tool['key'] ?? '') ?? [];
                    $path = $existing['logo_path'] ?? null;
                    if ($request->boolean('tools.'.$index.'.remove_logo') || $request->hasFile('tools.'.$index.'.logo')) {
                        $path = null;
                    }
                    if ($request->hasFile('tools.'.$index.'.logo')) {
                        $path = $this->store($request->file('tools.'.$index.'.logo'), $createdFiles);
                    }
                    $tools[] = ['key' => $existing['key'] ?? (string) Str::uuid(), 'name' => $tool['name'] ?? '', 'usage' => $tool['usage'] ?? '', 'logo_path' => $path];
                }
                $retiredFiles = array_merge($retiredFiles, $oldTools->pluck('logo_path')->filter()->diff(collect($tools)->pluck('logo_path'))->all());
                $values['tools'] = $tools;
                $values['publication_status'] = $data['action'] === 'publish' ? 'published' : 'draft';
                $values['is_active'] = $data['action'] === 'publish';
                $profession->fill($values)->save();
                $profession->competencies()->sync($data['competency_ids'] ?? []);

                return $profession;
            });
        } catch (Throwable $e) {
            Storage::disk('local')->delete($createdFiles);
            throw $e;
        }
        Storage::disk('local')->delete($retiredFiles);

        return $profession;
    }

    private function store($file, array &$createdFiles): string
    {
        $path = $file->store('professions', 'local');
        if (! $path) {
            throw new RuntimeException('Gambar belum dapat disimpan. Silakan coba lagi.');
        }
        $createdFiles[] = $path;

        return $path;
    }
}
