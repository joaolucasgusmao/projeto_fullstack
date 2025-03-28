<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "image",
        "link",
        "top",
        "side",
        "home",
        "description",
        "is_active"
    ];

    public function toArray()
    {
        return [
            'id' => $this->id,
        ] + parent::toArray();
    }
}
