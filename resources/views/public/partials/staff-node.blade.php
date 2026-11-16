<div class="org-node-wrap">
    <div class="org-node">
        @if($person->photo_path)<img src="{{ asset('storage/'.$person->photo_path) }}" alt="{{ $person->name }}">@else<span class="org-avatar"><i class="bi bi-person"></i></span>@endif
        <b>{{ $person->name }}</b><span>{{ $person->position }}</span>
        @if($person->department)<small>{{ $person->department }}</small>@endif
    </div>
    @if($person->children->isNotEmpty())
        <div class="org-children">@foreach($person->children as $child)@include('public.partials.staff-node', ['person' => $child])@endforeach</div>
    @endif
</div>
