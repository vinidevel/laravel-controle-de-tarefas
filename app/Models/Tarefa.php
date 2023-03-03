<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tarefa extends Model
{
    use HasFactory;

    protected $fillable = ['tarefa', 'data_limite_conclusao', 'user_id'];

    public function user():BelongsTo
    {
 
     return $this->belongsTo(User::class);
    }
 
}
