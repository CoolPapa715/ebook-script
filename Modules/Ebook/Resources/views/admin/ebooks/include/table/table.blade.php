    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="display:block">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">{{ clean($title) }}</h4>
                        
                        @if (isset($buttons, $name))
                            @foreach($buttons as $view)
                           
                                    <a href="#" id='{{$resource.'_create_btn'}}' class="btn btn-primary ml-auto modal_show " data-toggle="modal" data-target="#{{$resource.'_create_modal'}} ">
                                        {{ clean(trans("admin::resource.{$view}", ['resource' => $name])) }}
                                    </a>
                             
                            @endforeach
                        @else
                            {{ $buttons ?? '' }}
                        @endif
                    
                    </div>
                </div>
                <div class="card-body" id="{{ isset($resource) ? "{$resource}_table" : '' }}">
                    <div class="table-responsive">
                        @if (isset($thead))
                            <table class="display table table-striped table-hover {{ $class ?? '' }}" id="{{ $id ?? '' }}" >
                                <thead>{{ $thead }}</thead>

                                <tbody>{{ $slot }}</tbody>

                                @isset($tfoot)
                                    <tfoot>{{ $tfoot }}</tfoot>
                                @endisset
                            </table>
                        @else
                            {{ $slot }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@isset($name)
    @push('scripts')
        
        <script>
            @isset($resource)
                (function () {
                    "use strict";
                    DataTable.setRoutes('#{{ $resource }}_table .table', {
                        index: '{{ "admin.ebooks.{$resource}_show" }}',
                        // edit: '{{ "admin.ebooks.{$resource}_edit" }}',
                        destroy: '{{ "admin.ebooks.{$resource}_destroy" }}',
                    });
                    
                    
                })();   
                
                @if($resource == 'items')
                new DataTable('#items_table .table', {
                        columns: [
                            { data: 'checkbox', orderable: false, searchable: false, width: '3%' },
                            { data: 'item_image', orderable: false, searchable: false, width: '10%' },
                            { data: 'item_name', name: 'item_name', orderable: false, defaultContent: '' },
                            { data: 'item_slug', name: 'item_slug', orderable: false, defaultContent: '' },
                            { data: 'item_description', name: 'item_description', orderable: false, defaultContent: '' },
                            { data: 'item_image_id', name: 'item_image_id', orderable: false, defaultContent: '', className: "hide_column"  },
                            { data: 'created', name: 'created_at' },
                        ],
                    });
                   
                @endif
                @if($resource == 'skills')
                
                    new DataTable('#skills_table .table', {
                        columns: [
                            { data: 'checkbox', orderable: false, searchable: false, width: '3%' },
                            { data: 'skill_name', name: 'item_name', orderable: false, defaultContent: '' },
                            { data: 'skill_slug', name: 'item_slug', orderable: false, defaultContent: '' },
                            { data: 'skill_description', name: 'item_description', orderable: false, defaultContent: '' },
                            { data: 'created', name: 'created_at' },
                        ],
                    });
                @endif
                @if($resource == 'codewords')
               
                    new DataTable('#codewords_table .table', {
                        columns: [
                            { data: 'checkbox', orderable: false, searchable: false, width: '3%' },
                            { data: 'codeword_name', name: 'item_name', orderable: false, defaultContent: '' },
                            { data: 'codeword_slug', name: 'item_slug', orderable: false, defaultContent: '' },
                            { data: 'created', name: 'created_at' },
                        ],
                    });
                @endif
                @if($resource == 'heroes')
               
                    new DataTable('#heroes_table .table', {
                        columns: [
                            { data: 'checkbox', orderable: false, searchable: false, width: '3%' },
                            { data: 'hero_image', name: 'hero_image', orderable: false, defaultContent: '' },
                            { data: 'hero_name', name: 'hero_name', orderable: false, defaultContent: '' },
                            { data: 'hero_slug', name: 'hero_slug', orderable: false, defaultContent: '' },
                            { data: 'hero_description', name: 'hero_description', orderable: false, defaultContent: '' },
                            { data: 'created', name: 'created_at' },
                        ],
                    });
                @endif
                @if($resource == 'episodes')
               
                    new DataTable('#episodes_table .table', {
                        columns: [
                            { data: 'checkbox', orderable: false, searchable: false, width: '3%' },
                            { data: 'episode_number', name: 'episode_number', orderable: true, defaultContent: '' },
                            { data: 'episode_description', name: 'episode_description', orderable: false, defaultContent: '' },
                            { data: 'created', name: 'created_at',orderable: false },
                        ],
                    });
                @endif
            @endisset
            
        </script>
        
    @endpush
@endisset