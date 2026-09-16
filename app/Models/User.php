<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'phone', 'professional_url', 'photo_path', 'is_admin'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo_path
            ? asset('storage/' . $this->photo_path)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7C3AED&background=EDE9FE';
    }

    public function getWhatsAppUrlAttribute(): string
    {
        if (!$this->phone) {
            return '#';
        }
        $prefix = config('services.whatsapp.prefix', '54');
        $phone = preg_replace('/[^0-9]/', '', $this->phone);
        return "https://wa.me/{$prefix}{$phone}";
    }
}
