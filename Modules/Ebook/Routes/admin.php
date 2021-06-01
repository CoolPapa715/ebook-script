<?php

Route::get('ebooks', [
    'as' => 'admin.ebooks.index',
    'uses' => 'EbookController@index',
    'middleware' => 'can:admin.ebooks.index',
]);

Route::get('ebooks/create', [
    'as' => 'admin.ebooks.create',
    'uses' => 'EbookController@create',
    'middleware' => 'can:admin.ebooks.create',
]);

Route::post('ebooks', [
    'as' => 'admin.ebooks.store',
    'uses' => 'EbookController@store',
    'middleware' => 'can:admin.ebooks.create',
]);

/*-------------------------------  items start -------------------------------*/
Route::any('ebooks/items_create', [
    'as' => 'admin.ebooks.items_create',
    'uses' => 'EpisodesController@items_create',
    // 'middleware' => 'can:admin.ebooks.items_show',
]);

Route::any('ebooks/get_items', [
    'as' => 'admin.ebooks.get_items',
    'uses' => 'EpisodesController@get_items',
    // 'middleware' => 'can:admin.ebooks.items_show',
]);

Route::any('ebooks/items_show', [
    'as' => 'admin.ebooks.items_show',
    'uses' => 'EpisodesController@items_show',
    // 'middleware' => 'can:admin.ebooks.items_show',
]);

Route::get('ebooks/items_destroy/{id}', [
    'as' => 'admin.ebooks.items_destroy',
    'uses' => 'EpisodesController@items_destroy',
]);

Route::post('ebooks/item_edit', [
    'as' => 'admin.ebooks.item_edit',
    'uses' => 'EpisodesController@item_edit',
    // 'middleware' => 'can:admin.ebooks.items_show',
]);

Route::delete('ebooks/items_destroy/{ids}', [
    'as' => 'admin.ebooks.items_destroy',
    'uses' => 'EpisodesController@items_destroy',
    // 'middleware' => 'can:admin.ebooks.items_show',
]);
/*-------------------------------  items end -------------------------------*/

/*-------------------------------  skills start -------------------------------*/
Route::any('ebooks/skills_create', [
    'as' => 'admin.ebooks.skills_create',
    'uses' => 'EpisodesController@skills_create',
    // 'middleware' => 'can:admin.ebooks.items_show',
]);

Route::any('ebooks/get_skills', [
    'as' => 'admin.ebooks.get_skills',
    'uses' => 'EpisodesController@get_skills',
    // 'middleware' => 'can:admin.ebooks.items_show',
]);

Route::any('ebooks/skills_show', [
    'as' => 'admin.ebooks.skills_show',
    'uses' => 'EpisodesController@skills_show',
    // 'middleware' => 'can:admin.ebooks.items_show',
]);

// Route::post('ebooks/skill_edit', [
//     'as' => 'admin.ebooks.skill_edit',
//     'uses' => 'EpisodesController@skill_edit',
//     // 'middleware' => 'can:admin.ebooks.items_show',
// ]);

Route::get('ebooks/skills_destroy/{id}', [
    'as' => 'admin.ebooks.skills_destroy',
    'uses' => 'EpisodesController@skills_destroy',
]);

Route::delete('ebooks/skills_destroy/{ids}', [
    'as' => 'admin.ebooks.skills_destroy',
    'uses' => 'EpisodesController@skills_destroy',
    // 'middleware' => 'can:admin.ebooks.items_show',
]);
/*-------------------------------  skills end -------------------------------*/

/*-------------------------------  codewords start -------------------------------*/
Route::any('ebooks/codewords_create', [
    'as' => 'admin.ebooks.codewords_create',
    'uses' => 'EpisodesController@codewords_create',
    // 'middleware' => 'can:admin.ebooks.items_show',
]);

Route::any('ebooks/get_codewords', [
    'as' => 'admin.ebooks.get_codewords',
    'uses' => 'EpisodesController@get_codewords',
    // 'middleware' => 'can:admin.ebooks.items_show',
]);

Route::any('ebooks/codewords_show', [
    'as' => 'admin.ebooks.codewords_show',
    'uses' => 'EpisodesController@codewords_show',
    // 'middleware' => 'can:admin.ebooks.items_show',
]);

// Route::post('ebooks/codeword_edit', [
//     'as' => 'admin.ebooks.codeword_edit',
//     'uses' => 'EpisodesController@codeword_edit',
//     // 'middleware' => 'can:admin.ebooks.items_show',
// ]);

Route::get('ebooks/codewords_destroy/{id}', [
    'as' => 'admin.ebooks.codewords_destroy',
    'uses' => 'EpisodesController@codewords_destroy',
]);

Route::delete('ebooks/codewords_destroy/{ids}', [
    'as' => 'admin.ebooks.codewords_destroy',
    'uses' => 'EpisodesController@codewords_destroy',
    // 'middleware' => 'can:admin.ebooks.items_show',
]);
/*-------------------------------  codewords end -------------------------------*/

/*-------------------------------  heroes start -------------------------------*/
Route::any('ebooks/heroes_create', [
    'as' => 'admin.ebooks.heroes_create',
    'uses' => 'EpisodesController@heroes_create',
    // 'middleware' => 'can:admin.ebooks.items_show',
]);

Route::any('ebooks/heroes_show', [
    'as' => 'admin.ebooks.heroes_show',
    'uses' => 'EpisodesController@heroes_show',
    // 'middleware' => 'can:admin.ebooks.items_show',
]);

Route::get('ebooks/heroes_destroy/{id}', [
    'as' => 'admin.ebooks.heroes_destroy',
    'uses' => 'EpisodesController@heroes_destroy',
]);

Route::delete('ebooks/heroes_destroy/{ids}', [
    'as' => 'admin.ebooks.heroes_destroy',
    'uses' => 'EpisodesController@heroes_destroy',
    // 'middleware' => 'can:admin.ebooks.items_show',
]);

Route::post('ebooks/hero_edit', [
    'as' => 'admin.ebooks.hero_edit',
    'uses' => 'EpisodesController@hero_edit',
    // 'middleware' => 'can:admin.ebooks.items_show',
]);

Route::any('ebooks/get_episodesNum', [
    'as' => 'admin.ebooks.get_episodesNum',
    'uses' => 'EpisodesController@get_episodesNum',
    // 'middleware' => 'can:admin.ebooks.items_show',
]);

/*-------------------------------  heroes end -------------------------------*/

/*-------------------------------  episodes start -------------------------------*/
Route::any('ebooks/episodes_create', [
    'as' => 'admin.ebooks.episodes_create',
    'uses' => 'EpisodesController@episodes_create',
    // 'middleware' => 'can:admin.ebooks.items_show',
]);

Route::any('ebooks/episodes_show', [
    'as' => 'admin.ebooks.episodes_show',
    'uses' => 'EpisodesController@episodes_show',
    // 'middleware' => 'can:admin.ebooks.items_show',
]);

Route::get('ebooks/episodes_destroy/{id}', [
    'as' => 'admin.ebooks.episodes_destroy',
    'uses' => 'EpisodesController@episodes_destroy',
]);

Route::delete('ebooks/episodes_destroy/{ids}', [
    'as' => 'admin.ebooks.episodes_destroy',
    'uses' => 'EpisodesController@episodes_destroy',
    // 'middleware' => 'can:admin.ebooks.items_show',
]);

Route::post('ebooks/episode_edit', [
    'as' => 'admin.ebooks.episode_edit',
    'uses' => 'EpisodesController@episode_edit',
    // 'middleware' => 'can:admin.ebooks.items_show',
]);

/*-------------------------------  episodes end -------------------------------*/

Route::get('ebooks/{id}', [
    'as' => 'admin.ebooks.show',
    'uses' => 'EbookController@show',
    'middleware' => 'can:admin.ebooks.edit',
]);

Route::get('ebooks/{id}/edit', [
    'as' => 'admin.ebooks.edit',
    'uses' => 'EbookController@edit',
    'middleware' => 'can:admin.ebooks.edit',
]);

Route::put('ebooks/{id}', [
    'as' => 'admin.ebooks.update',
    'uses' => 'EbookController@update',
    'middleware' => 'can:admin.ebooks.edit',
]);

Route::delete('ebooks/{ids}', [
    'as' => 'admin.ebooks.destroy',
    'uses' => 'EbookController@destroy',
    'middleware' => 'can:admin.ebooks.destroy',
]);

Route::get('reported-ebooks', [
    'as' => 'admin.reportedebooks.index',
    'uses' => 'ReportedEbookController@index',
    'middleware' => 'can:admin.reportedebooks.index',
]);

Route::delete('reported-ebooks/{ids}', [
    'as' => 'admin.reportedebooks.destroy',
    'uses' => 'ReportedEbookController@destroy',
    'middleware' => 'can:admin.reportedebooks.destroy',
]);
