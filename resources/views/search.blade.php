@extends('layouts.app')

@section('content')

  <!-- Search Section -->
  <section class="justify-content-around cs-space">

    <!-- Search Container -->
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="title m-b-md">

              <div class="">
                <div class="cs-input d-flex flex-row">
                  <input type="search" id="address" class="form-control" placeholder="Dove vuoi andare?"/>
                  <button class="cs-btn cs-btn-search" onclick="loadCitta()"> <a href="{{route('search')}}">Cerca </a></button>
                </div>

                {{-- <div class="cs-rooms">
                  <input id="rooms" type="number" name="rooms" value="">
                  <label for="beds"> Numero di stanze </label>
                </div>

                <div class="cs-beds">
                  <input id="beds" type="number" name="beds" value="">
                  <label for="beds"> Numero di posti letto </label>
                </div>

                <div class="cs-radius">
                  <select class="" name="radius">
                    <option value="20">20Km</option>
                    <option value="40">40km</option>
                    <option value="60">60Km</option>
                    <option value="80">80Km</option>
                    <option value="100">100Km</option>
                  </select>
                </div>

                <div class="cs-services">
                  @foreach ($services as $service)
                    <input type="checkbox" id="service" name="services[]" value="{{ $service->id }}">
                    <label for="service"> {{ $service->type }} </label>
                  @endforeach
                </div> --}}

                {{-- <div class="">
                  <button onclick="loadCitta()">Cerca</button>
                </div> --}}
              </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Search Container -->

  </section>
  <!-- End Search Section -->

  <!-- Apartment Result Section -->
  <section>
    <div class="container">
      <div class="row">
        <div class="col-12">
          <!-- Search Result Container -->
          <!-- >> Handlebars will print its template here -->
          <div id="apartment_list"></div>
          <!-- End Search Result Container -->
        </div>
      </div>
    </div>
  </section>
  <!-- End Apartment Result Section -->

  <!-- Handlebars Template -->
  <script id="apartment-template" type="text/x-handlebars-template">

    <!-- Apartment Card -->
    <div class="card my-3" style="width: 18rem;">

      <!-- Apartment Image -->
      <img class="card-img-top" src=" {{ asset('storage') . '/'}}@{{{image}}} " alt="Apartment image">

      <!-- Apartment Infos -->
      <div class="card-body d-flex flex-column">
        <!-- Apartment Title -->
        <h5 class="card-title text-center">Titolo : @{{ title }}</h5>
        <!-- Apartment Description -->
        <p class="card-text">@{{ description }}</p>
        <!-- Apartment Address -->
        <p class="card-text">@{{ address }}</p>
        <!-- Apartment Btn -->
        <a href="/apartments/@{{ id }}" class="btn cs-btn align-self-center">SCOPRI</a>
      </div>
    </div>
    <!-- End Apartment Card -->

  </script>
  <!-- End Handlebars Template -->


{{-- JS --}}
  <script src="https://cdn.jsdelivr.net/npm/places.js@1.19.0"></script>

  <script>
  function loadCitta() {
    var citta = $('#address').val();
    $('#apartment_list').html('');
      $.ajax({
        url: 'https://places-dsn.algolia.net/1/places/query?query=' + citta,
        method: 'GET',
        success: function(data) {
          var coord = data.hits[0]._geoloc;
          var lat = coord.lat;
          var lng = coord.lng;
          searchCitta(lat, lng);
        },
        error: function() {
        }
      });
  }
  function searchCitta(lat, lng) {
    $.ajax(
      {
        url: 'http://localhost:8000/api/apisearch',
        method: 'GET',
        success: function(dataResponse) {
          console.log(dataResponse.apartments[0].id);

          var allApartments = dataResponse.apartments;
          var searchResult =[];
          var errore = '<h2>Nessun appartamento presente</h2>';

          var source = $("#apartment-template").html();
          var template = Handlebars.compile(source);

          for (var i = 0; i < allApartments.length; i++) {
            var thisApartment = allApartments[i];
            if(lat == thisApartment.lat && lng == thisApartment.lon) {
              console.log(thisApartment.id);
              if (thisApartment.rooms != $("#roomSelected").val()) {
                console.log('stanza ok');
              } else {
                console.log('stanza non ok');
              }

              searchResult.push(thisApartment);
              var html = template(thisApartment);
              $('#apartment_list').append(html);
            } else {

            }
          }
          if (searchResult.length == 0) {
            $('#apartment_list').append(errore);
          }
        },
        error: function() {
          alert('error');
        }
      }
    );
  }
  </script>

@endsection
