{{-- запрос городов --}}
<div class="list-group city-list-group">
@foreach ($cities as $city)
    <a href="{{ route('lawyers.city', ['city' => $city]) }}" class="city-link list-group-item"><h5 class="list-group-item-heading city-name">{{ $city->name }}</h5><p class="small list-group-item-text">{{ $city->region->name }}</p></a>
@endforeach
</div>
