<?php

namespace Modules\Ebook\Entities;

use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Eloquent\Model;


class EbookGameHasItems extends Model
{
    protected $table = "games_has_items";

    protected $fillable = [
        'game_id',
        'item_id',
        'value'
    ];

     /**
     * Get table data for the resource
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
  
 
    
}