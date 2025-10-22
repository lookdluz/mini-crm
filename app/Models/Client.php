<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;


class Client extends Model
{
use HasFactory;


protected $fillable = [
'name','email','phone','profile_photo_path'
];


public function purchases(): HasMany
{
return $this->hasMany(Purchase::class);
}


public function getProfilePhotoUrlAttribute(): string
{
if ($this->profile_photo_path && Storage::disk('public')->exists($this->profile_photo_path)) {
return Storage::url($this->profile_photo_path);
}
return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random';
}


public function getTotalSpentAttribute(): float
{
return (float) $this->purchases()->sum('amount');
}
}