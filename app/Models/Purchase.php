<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Purchase extends Model
{
use HasFactory;


protected $fillable = ['client_id','purchased_at','amount','description'];
protected $casts = [ 'purchased_at' => 'date' ];


public function client(): BelongsTo
{
return $this->belongsTo(Client::class);
}
}