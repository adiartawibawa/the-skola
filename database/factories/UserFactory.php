<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Src\Core\Models\Sekolah;

/**
 * Factory: UserFactory (diperbarui untuk multi-tenancy)
 *
 * Contoh penggunaan di test:
 *   // User biasa dalam sekolah tertentu
 *   $user = User::factory()->forSekolah($sekolah)->create();
 *
 *   // Super-Admin
 *   $superAdmin = User::factory()->superAdmin()->create();
 *
 *   // 10 guru dalam satu sekolah
 *   $guru = User::factory()->forSekolah($sekolah)->count(10)->create();
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'sekolah_id' => Sekolah::factory(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'nip' => $this->faker->optional()->numerify('##################'),
            'avatar_path' => null,
            'is_aktif' => true,
            'last_login_at' => null,
            'remember_token' => Str::random(10),
        ];
    }

    /** User yang tidak terverifikasi email-nya */
    public function unverified(): static
    {
        return $this->state(['email_verified_at' => null]);
    }

    /** Super-Admin — sekolah_id = null */
    public function superAdmin(): static
    {
        return $this->state(['sekolah_id' => null]);
    }

    /** Assign user ke sekolah yang sudah ada */
    public function forSekolah(Sekolah $sekolah): static
    {
        return $this->state(['sekolah_id' => $sekolah->id]);
    }

    /** User nonaktif */
    public function nonaktif(): static
    {
        return $this->state(['is_aktif' => false]);
    }
}
