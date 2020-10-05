@extends('layouts.app')

@section('content')

  <!-- Apartment Section -->
  <section class="justify-content-around cs-apartment">
    <!-- Apartment Container -->
    <div class="container">

      <div class="navbar navbar-expand-sm navbar-expand-sm shadow-sm cs-back">
        <a href="{{ route('search') }}"> < Torna alla ricerca</a>
      </div>

      <h1>Appartamento {{ $apartment->id }}</h1>

      <div class="">
        <ul class="list-unstyled">
          <li><h3>Titolo:</h3>{{ $apartment->title }}</li>
          <li><h3>Descrizione:</h3>{{ $apartment->description }}</li>
          <li><h3>Stanze:</h3>{{ $apartment->rooms }}</li>
          <li><h3>Letti:</h3>{{ $apartment->beds }}</li>
          <li><h3>Bagni:</h3>{{ $apartment->baths }}</li>
          <li><h3>Mq:</h3>{{ $apartment->square_meters }}</li>
          <li><h3>Indirizzo:</h3>{{ $apartment->address }}</li>
          <li><h3>Prezzo:</h3>{{ $apartment->price }} €</li>
          <li><img src=" {{ asset('storage') . '/' . $apartment->image }} " alt="{{$apartment->title}}"></li>
          <li><h3>Proprietario:</h3>{{ $apartment->user->name }}</li>
          {{-- <li><h3>Città:</h3>{{ $apartment->city }}</li>
          <li><h3>Stato:</h3>{{ $apartment->country }}</li> --}}
        </ul>
      </div>

      <div id="mapid"></div>

      <script type="text/javascript">
          var mymap = L.map('mapid').setView([{{ $apartment->lat }}, {{ $apartment->lon }}], 17);
          var marker = L.marker([{{ $apartment->lat }}, {{ $apartment->lon }}]).addTo(mymap);

          L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 18,
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'pk.eyJ1IjoiY29ycmFkb2N0IiwiYSI6ImNrZm51YmF0NDBlZTQyeW9lYmpyNGpzcGYifQ.A43eZmLSH1fCHbMCGtG_Zg'
          }).addTo(mymap);
      </script>

      @php
      $isUserAuth = isset($user_auth);
      @endphp

      @if ($isUserAuth === false || $user_auth->id !== $apartment->user_id)
        <div class="">
          <form class="" action="{{ route('message.store' , $apartment) }}" method="post">
            @csrf
            @method('POST')

            <div>
              <input  type="hidden" name="apartment_id" value= {{ $apartment->id }}>
            </div>

            <div>
              <label for="email">Email</label>
              <input  type="email" name="email" value= '' placeholder="Inserisci la mail">
            </div>

            <div>
              <label for="content">Content</label>
              <textarea name="content" rows="8" cols="80" placeholder="Inserisci il messaggio"></textarea>
            </div>

            <div>
              <input class="cs-btn" type="submit" name="" value="Send">
            </div>

          </form>
        </div>
      @else

        @if ($user_auth->id === $apartment->user_id)
          <a href="{{ route('upr.messages.index') }}">Leggi i messaggi ricevuti</a>
        @endif

      @endif
    </div>
    <!-- End Apartment Container -->
  </section>
  <!-- End Apartment Section -->
@endsection
