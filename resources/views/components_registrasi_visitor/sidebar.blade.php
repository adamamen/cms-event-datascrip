<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <ul class="sidebar-menu">
            @if (!empty($masterEvent))
                @foreach ($masterEvent as $value)
                    
                        <div class="sidebar-brand">
                            <a href="{{ route('dashboard', ['page' => $value['title_url']]) }}">
                                <img src="{{ asset('images/' . $value['logo']) }}" height="54">
                            </a>
                        </div>
                        <div class="sidebar-brand sidebar-brand-sm">
                            <a href="{{ route('dashboard', ['page' => $value['title_url']]) }}">
                                <img src="{{ asset('images/' . $value['logo']) }}" height="15">
                            </a>
                        </div>
                        
                @endforeach
            @endif 
        </ul>
    </aside>
</div>
