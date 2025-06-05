<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemSize extends Model
{
    protected  $fillable = ['item_id' ,'size', 'price'];
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

}
