<div class="episodes">
    <div class="episodes-body">
        <div class="episodes-tabs tab-wrapper">
            
            <ul class="nav nav-pills nav-secondary" id="episodes-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="episodes-heroes-tab" data-toggle="pill" href="#episodes-heroes" role="tab" aria-controls="episodes-heroes" aria-selected="true">
                        {{ clean(trans('ebook::ebooks.form.tabs.heroes')) }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="episodes-item_skills-tab" data-toggle="pill" href="#episodes-item_skills" role="tab" aria-controls="episodes-item_skills" aria-selected="false">
                        {{ clean(trans('ebook::ebooks.form.tabs.item_skills')) }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="episodes-episodes-tab" data-toggle="pill" href="#episodes-episodes" role="tab" aria-controls="episodes-episodes" aria-selected="false">
                        {{ clean(trans('ebook::ebooks.form.tabs.episodes')) }}
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link" id="episodes-enemies-tab" data-toggle="pill" href="#episodes-enemies" role="tab" aria-controls="episodes-enemies" aria-selected="false">
                        {{ clean(trans('ebook::ebooks.form.tabs.enemies')) }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="episodes-battles-tab" data-toggle="pill" href="#episodes-battles" role="tab" aria-controls="episodes-battles" aria-selected="false">
                        {{ clean(trans('ebook::ebooks.form.tabs.battles')) }}
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link" id="episodes-rules-tab" data-toggle="pill" href="#episodes-rules" role="tab" aria-controls="episodes-rules" aria-selected="false">
                        {{ clean(trans('ebook::ebooks.form.tabs.rules')) }}
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="episodes-tabContent">
                <div class="tab-pane fade show active" id="episodes-heroes" role="tabpanel" aria-labelledby="episodes-heroes-tab">
                   
                    @include('ebook::admin.ebooks.include.tab_content.hero_content')

                </div>
                <div class="tab-pane fade" id="episodes-item_skills" role="tabpanel" aria-labelledby="episodes-item_skills-tab">
                
                    @include('ebook::admin.ebooks.include.tab_content.item_content')
                    
                </div>
                <div class="tab-pane fade" id="episodes-episodes" role="tabpanel" aria-labelledby="episodes-episodes-tab">

                    @include('ebook::admin.ebooks.include.tab_content.episode_content')
                    
                </div>
                <div class="tab-pane fade" id="episodes-enemies" role="tabpanel" aria-labelledby="episodes-enemies-tab">
                    dddd
                </div>
                <div class="tab-pane fade" id="episodes-battles" role="tabpanel" aria-labelledby="episodes-battles-tab">
                    eeee
                </div>
                <div class="tab-pane fade" id="episodes-rules" role="tabpanel" aria-labelledby="episodes-rules-tab">
                    ffff
                </div>
            </div>
           
        </div>
    </div>
</div>
@include('ebook::admin.ebooks.include.tab_content.script')

