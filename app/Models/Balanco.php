<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balanco extends Model
{
    use HasFactory;
    protected $fillable = ['descricao', 'valor', 'tipo', 'competencia', 'user_id'];

}
