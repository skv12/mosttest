<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class MessageIsSeen extends Model
{
    use HasFactory;
    protected $guarded = false;
    protected $table = 'message_is_seens';
}
