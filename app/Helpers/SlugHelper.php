<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;

class SlugHelper
{
    /**
     * Generate a unique slug from a given string for a specified model.
     */
    public static function createUniqueSlug(string $string, string $modelClass, ?int $id = null): string
    {
        // Membuat slug dasar
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string); // Mengganti karakter non-alfanumerik dengan -
        $slug = strtolower(trim($slug, '-')); // Menghapus tanda hubung di awal dan akhir, lalu mengubah ke lowercase

        // Memastikan slug unik
        $originalSlug = $slug;
        $count = 1;

        while ($modelClass::where('slug', $slug)->when($id, function ($query) use ($id) {
            return $query->where('id', '!=', $id); // Menghindari pengecekan pada slug yang sama ketika mengupdate
        })->exists()) {
            $slug = "{$originalSlug}-" . $count++;
        }

        return $slug;
    }
}
