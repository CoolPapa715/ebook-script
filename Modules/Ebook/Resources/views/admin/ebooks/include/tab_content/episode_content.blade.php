@component('ebook::admin.ebooks.include.table.table')
    @slot('title', clean(trans('ebook::ebooks.form.episodes_table.title')))
    @slot('buttons', ['create'])
    @slot('resource', 'episodes')
    @slot('name', clean(trans('ebook::ebooks.form.episodes_table.title')))

    @slot('thead')
        <tr>
            @include('admin::include.table.select-all',["name"=>trans('ebook::ebooks.form.episodes_table.title')])
            <th>{{ clean(trans('ebook::ebooks.form.episodes_table.number')) }}</th>
            <th>{{ clean(trans('ebook::ebooks.form.episodes_table.description')) }}</th>
            <th data-sort>{{ clean(trans('admin::admin.table.created')) }}</th>
        </tr>
    @endslot
@endcomponent

<div class="modal fade episode_modal" id="episodes_create_modal" role="dialog" style="overflow-x: hidden !important; overflow-y: auto !important;">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style='display:block'>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Episode</h4>
        </div>
        <div class="modal-body">
            <div class="card">
                <div class="card-header" style="display:block">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Hero</h4>
                    </div>
                </div>
                <div class="card-body">
                    <input type="hidden" id="episode_id" class="form-control">
                    {{ Form::text('episode_number',clean(trans('ebook::attributes.episode_number')), $errors,) }}
                    {{ Form::wysiwyg('episode_description', clean(trans('ebook::attributes.description')), $errors, ['rows' => 2,]) }}
                    <div class="row">
                        <div class='col-md-4' >
                            {{ Form::checkbox('death_episode','', clean(trans('ebook::ebooks.form.death_episode')), $errors) }}
                        </div>
                        <div class='col-md-4' >
                            {{ Form::checkbox('episode_with_fight', '', clean(trans('ebook::ebooks.form.episode_with_fight')), $errors) }}
                        </div>
                        <div class='col-md-4' >
                            {{ Form::checkbox('dice', '', clean(trans('ebook::ebooks.form.dice')), $errors) }}
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-md-4' >
                            {{ Form::checkbox('final_episode','', clean(trans('ebook::ebooks.form.final_episode')), $errors) }}
                        </div>
                        <div class='col-md-4' >
                            {{ Form::checkbox('is_first', '', clean(trans('ebook::ebooks.form.is_first')), $errors) }}
                        </div>
                    </div>
                                        
                    <div class="form-group row">
                        <label for="episode_number" class="col-md-4 text-left"></label>
                        <div class='col-md-8 row' >
                            <div class='col-md-12'>
                                <table id='episode_item_table' class="record_table">
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class='form-group row'>
                        <label for="episode_number" class="col-md-4 text-left"> {{clean(trans('ebook::ebooks.form.episode_skill'))}} </label>
                        <div class='col-md-8 row' >
                            <div class='col-md-7'>
                                <select class="form-control item_select" data-url='admin/ebooks/get_items'>
                                <option></option>
                                </select>
                            </div>
                            <div class='col-md-3'>
                                <input type='number' class="form-control" >
                            </div>
                            <div class='col-md-2'>
                                <a class="btn btn-sm btn-primary record_add" style="color: white" data-type='episode_item'>+</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="episode_number" class="col-md-4 text-left"></label>
                        <div class='col-md-8 row' >
                            <div class='col-md-12'>
                                <table id='episode_skill_table' class="record_table">
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class='form-group row'>
                        <label for="episode_number" class="col-md-4 text-left"> {{clean(trans('ebook::ebooks.form.episode_item'))}} </label>
                        <div class='col-md-8 row' >
                            <div class='col-md-7'>
                                <select class="form-control skill_select" data-url='admin/ebooks/get_skills'>
                                <option></option>
                                </select>
                            </div>
                            <div class='col-md-3'>
            
                                <input type='number' class="form-control" >
                            </div>
                            <div class='col-md-2'>
                                <a class="btn btn-sm btn-primary record_add" style="color: white" data-type='episode_skill'>+</a>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="episode_number" class="col-md-4 text-left"></label>
                        <div class='col-md-8 row' >
                            <div class='col-md-12'>
                                <table id='episode_codeword_table' class='record_table'>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class='form-group row'>
                        <label for="episode_number" class="col-md-4 text-left"> {{clean(trans('ebook::ebooks.form.episode_code'))}} </label>
                        <div class='col-md-8 row' >
                            <div class='col-md-7'>
                                <select class='form-control codeword_select' data-url='admin/ebooks/get_codewords'>
                                <option></option>
                                </select>
                            </div>
                            <div class='col-md-3'>
                                <input type='number' class='form-control' >
                            </div>
                            <div class='col-md-2'>
                                <a class="btn btn-sm btn-primary record_add" style="color: white" data-type='episode_codeword'>+</a>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="episode_number" class="col-md-2 text-left"></label>
                        <div class='col-md-10 row' >
                            <div class='col-md-12'>
                                <table id='episode_link_table' class='record_table'>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class='form-group row dice_uncheck'>
                        <label for="episode_number" class="col-md-2 text-left"> {{clean(trans('ebook::ebooks.form.episode_next_link'))}} </label>
                            <div class='col-md-10 row'>
                                
                                <div class='col-md-6'>
                                    <input type='text' class='form-control' placeholder="text"  >
                                </div>
                                <div class='col-md-4'>
                                    <select class='form-control episode_select' data-url='admin/ebooks/get_episodesNum' placeholder='Num'>
                                    <option></option>
                                    </select>
                                </div>
                                <div class='col-md-2'>
                                    <a class="btn btn-sm btn-primary record_add"  style="color: white" data-type='episode_link'>+</a>
                                </div>
                               
                            </div>
                            
                    </div>
                    <div class='form-group row dice_check' style="display: none">
                        <div class='col-md-12 row'>
                            <label for="episode_number" class="col-md-2 text-left"> {{clean(trans('ebook::ebooks.form.episode_is_even'))}} </label>
                            
                            <div class='col-md-5'>
                                <input type='text' class='form-control' placeholder="text" id='episode_even_linkText'>
                            </div>
                            <div class='col-md-4'>
                                <select class='form-control episode_select' data-url='admin/ebooks/get_episodesNum' placeholder='Num' id='episode_even_linkID'>
                                <option></option>
                                </select>
                            </div>
                           
                        </div>
                        <div class='col-md-12 row'>
                            <label for="episode_number" class="col-md-2 text-left"> {{clean(trans('ebook::ebooks.form.episode_is_odd'))}} </label>
                           
                            <div class='col-md-5'>
                                <input type='text' class='form-control' placeholder="text" id='episode_odd_linkText'>
                            </div>
                            <div class='col-md-4'>
                                <select class='form-control episode_select' data-url='admin/ebooks/get_episodesNum' placeholder='Num' id='episode_odd_linkID'> 
                                <option></option>
                                </select>
                            </div>
                            
                        </div>
                        
                    </div>

                </div>
            </div>
         </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-delete m-2" id="episode_save">save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      </form>
    </div>
</div>
 