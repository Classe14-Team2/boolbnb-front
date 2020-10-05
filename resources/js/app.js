require('./bootstrap');
require('./search');

var $ = require('jquery');

var Handlebars = require("handlebars");

$(document).ready(function() {
  $('#address-new').focusout(function() {
  var citta = $('#address-new').val();
    $.ajax({
      url: 'https://places-dsn.algolia.net/1/places/query?query=' + citta,
      method: 'GET',
      success: function(data) {
        var coord = data.hits[0]._geoloc;
        var lat = coord.lat;
        var lng = coord.lng;
        // console.log(lat);
        // console.log(lng);
        $('#lat').val(lat);
        $('#lon').val(lng);
      },
      error: function() {
        alert('ciao');
      }
    });
  });

  function loadCitta() {
    var citta = $('#address').val();
      $.ajax({
        url: 'https://places-dsn.algolia.net/1/places/query?query=' + citta,
        method: 'GET',
        success: function(data) {
          var coord = data.hits[0]._geoloc;
          var lat = coord.lat;
          var lng = coord.lng;
          console.log(lat);
          console.log(lng);
          searchCitta(lat, lng);
        },
        error: function() {
        }
      });
    }

  $('#trova').click(function() {

    var citta = $('#address').val();
      $.ajax({
        url: 'https://places-dsn.algolia.net/1/places/query?query=' + citta,
        method: 'GET',
        success: function(data) {
          var coord = data.hits[0]._geoloc;
          var lat = coord.lat;
          var lng = coord.lng;
          console.log(lat);
          console.log(lng);
          searchCitta(lat, lng);
        },
        error: function() {
        }
      });
  });




});

function searchCitta(lat, lng) {
  $.ajax(
    {
      url: 'http://localhost:8000/api/apisearch',
      method: 'GET',
      success: function(dataResponse) {
        var allApartments = dataResponse.apartments;


        var source = $("#apartment-template").html();
        var template = Handlebars.compile(source);

        for (var i = 0; i < allApartments.length; i++) {
          var thisApartment = allApartments[i];
          if(lat == thisApartment.lat && lng == thisApartment.lon) {
            console.log('ok');
            var html = template(thisApartment);
            $('#apartment_list').append(html);
          }
          else {
            console.log('error');
          }

        }

      },
      error: function() {
        alert('error');
      }
    }
  );
}
