<?php

namespace Modules\Ebook\Entities;

use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Eloquent\Model;


class EbookGameEpisodeSteps extends Model
{
    protected $table = "game_episodes_steps";

    protected $fillable = [
        'game_id',
        'episode_id'
    ];

     /**
     * Get table data for the resource
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
   

 
    
}