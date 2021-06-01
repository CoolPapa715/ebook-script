<?php

namespace Modules\Ebook\Entities;

use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Eloquent\Model;


class EbookGameHasCodewords extends Model
{
    protected $table = "games_has_codewords";

    protected $fillable = [
        'game_id',
        'codeword_id',
        'value'
    ];

     /**
     * Get table data for the resource
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */


 
    
}