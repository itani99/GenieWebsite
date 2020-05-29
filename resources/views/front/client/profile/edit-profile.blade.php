@extends('front.client.profile.my-profile')
@push('css')
    <link href="{{asset('css/client/map.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/client/new-address.css')}}" rel="stylesheet" type="text/css">
    <style>

        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 550px;
        }

        /* Optional: Makes the sample page fill the window. */

        #description {
            font-family: Roboto, serif;
            font-size: 15px;
            font-weight: 300;
        }

        #infowindow-content .title {
            font-weight: bold;
        }

        #infowindow-content {
            display: none;
        }

        #map #infowindow-content {
            display: inline;
        }

        .pac-card {
            margin: 10px 10px 0 0;
            border-radius: 2px 0 0 2px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            background-color: #fff;
            font-family: Roboto, serif;
        }

        #pac-container {
            padding-bottom: 12px;
            margin-right: 12px;
        }

        .pac-controls {
            display: inline-block;
            padding: 5px 11px;
        }

        .pac-controls label {
            font-family: Roboto, serif;
            font-size: 13px;
            font-weight: 300;
        }

        #pac-input {
            background-color: #fff;
            font-family: Roboto, serif;
            font-size: 15px;
            font-weight: 300;
            margin-left: 12px;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 400px;
        }

        #pac-input:focus {
            border-color: #4d90fe;
        }

        #title {
            color: #fff;
            background-color: #4d90fe;
            font-size: 25px;
            font-weight: 500;
            padding: 6px 12px;
        }

        #target {
            width: 345px;
        }


    </style>
@endpush
@section('profile-content')
    <div class="page-title">
        <h1>Edit Profile</h1>
    </div><!-- /.page-title -->
    <form method="post" action="{{route('client.contact.update')}}">
        @csrf
        @method('put')
    <div class="background-white p20 mb30">
        <h4 class="page-title">
            General Information
            <input type="submit" value="Save" class="btn btn-primary btn-xs pull-right"/>
        </h4>
        <div class="row">
            <div class="form-group col-sm-6">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" id="name" value="{{$user->name}}">
            </div><!-- /.form-group -->
            <div class="form-group col-sm-6">
                <label>Gender</label>
                <p>
                    <label>
                        <input class="with-gap" name="gender" type="radio" @if($user->gender=='male') checked @endif />
                        <span>male</span>
                    </label>
                    <label>
                        <input class="with-gap" name="gender" type="radio" @if($user->gender=='female') checked @endif />
                        <span>female</span>
                    </label>
                </p>
            </div>
            <div class="form-group col-sm-6">
                <label for="email">E-mail</label>
                <input type="text" class="form-control" name="email" id="email" value="{{$user->email}}">
            </div><!-- /.form-group -->
            <div class="form-group col-sm-6">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" name="phone" id="phone" value="{{$user->phone}}"
                       placeholder="71456789">
            </div><!-- /.form-group -->
        </div><!-- /.row -->
    </div><!--/.background-white p20 mb30-->
    </form>
    <div class="background-white p20 mb30">
        <h4 class="page-title">
            Addresses
        </h4>
    @if($user->client_addresses !=  null)
    @foreach($user->client_addresses as $address)
                <ul class="collapsible">
                    <li>
                        <div class="collapsible-header">
                            <h3 style="width: 100%"> <i class="fa fa-map-marker" aria-hidden="true" ></i>
                                {{ $address['name'] }}
                                <a href="#" onclick="document.getElementById('{{$address['_id']}}').submit();">
                                    <i class="fa fa-trash" style="float: right"></i>
                                </a>
                                <a href="{{route('client.address.edit',$address['_id'])}}">
                                    <i class="fa fa-edit" style="float: right"></i>
                                </a>
                            </h3>
                        </div>
                        <div class="collapsible-body">
                            <span>Street: {{$address['street']}}       </span>
                            <span>Building:{{$address['house']}}</span>
                            <br>
                            <span>Zip:{{$address['zip']}}       </span>
                            <span>property type:{{$address['property_type']}}</span>
                            <br>
                            <span>contract type:{{$address['contract_type']}}</span>
                        </div>
                    </li>
                </ul>
    @endforeach
    @endif
        <div class="row">
            <a href="{{route('client.address.create')}}">
        <div class="line-item__add">
            <button type="button"  class="js-line-item-trigger marketing-button--skin-reset button--icon"
                    id="AddLineItem">
                <svg class="icon icon--fill-primary" aria-hidden="true" focusable="false">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40">
                        <path
                            d="M20 0C9 0 0 9 0 20s9 20 20 20 20-9 20-20S31 0 20 0zm0 38c-9.9 0-18-8.1-18-18S10.1 2 20 2s18 8.1 18 18-8.1 18-18 18z"></path>
                        <path
                            d="M28 20c0 .5-.5 1-1 1h-6v6.3c0 .6-.5 1-1 1s-1-.4-1-1V21h-6.3c-.6 0-1-.5-1-1s.4-1 1-1H19v-6c0-.5.5-1 1-1s1 .5 1 1v6h6c.5 0 1 .5 1 1z"></path>
                    </svg>
                </svg>
                <span class="body-link">
    Add address
  </span>
            </button>
        </div>
            </a>
        </div>
        </div><!-- /.background -white -->

    {{--    <div class="background-white p20 mb30">--}}
    {{--        <h4 class="page-title">--}}
    {{--            Biography--}}
    {{--            <a href="#" class="btn btn-primary btn-xs pull-right">Save</a>--}}
    {{--        </h4>--}}
    {{--        <textarea class="form-control" rows="7"></textarea>--}}
    {{--        <div class="textarea-resize"></div>--}}
    {{--    </div>--}}
@endsection
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('.collapsible');
            var instances = M.Collapsible.init(elems);
        });
    </script>
    <script>
        // This example adds a search box to a map, using the Google Place Autocomplete
        // feature. People can enter geographical searches. The search box will return a
        // pick list containing a mix of places and predicted search terms.

        // This example requires the Places library. Include the libraries=places
        // parameter when you first load the API. For example:
        // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

        function initAutocomplete() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: -33.8688, lng: 151.2195},
                zoom: 13,
                mapTypeId: 'roadmap'
            });

            // Create the search box and link it to the UI element.
            var input = document.getElementById('pac-input');
            var searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);


            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function () {
                searchBox.setBounds(map.getBounds());
            });

            var markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener('places_changed', function () {
                var places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }


                // Clear out the old markers.
                markers.forEach(function (marker) {
                    marker.setMap(null);
                });
                markers = [];

                // For each place, get the icon, name and location.
                var bounds = new google.maps.LatLngBounds();
                places.forEach(function (place) {
                    if (!place.geometry) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var icon = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25)
                    };

                    // Create a marker for each place.
                    markers.push(new google.maps.Marker({
                        map: map,
                        icon: icon,
                        title: place.name,
                        position: place.geometry.location
                    }));

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                    $('#lat').val(place.geometry.location.lat())
                    $('#lng').val(place.geometry.location.lng())
                });

                map.fitBounds(bounds);
            });
        }
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyApA0BZrqcfRauI8W5RLAQYjNJla_AS3gA&libraries=places&callback=initAutocomplete"
        async defer></script>
    <script src="/public/js/client/dropdown.js" type="text/javascript"></script>
    <script src="/public/js/client/collapse.js" type="text/javascript"></script>
    <script src="/public/js/client/jquery.colorbox-min.js" type="text/javascript"></script>
    <script src="/public/js/client/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="/public/js/client/superlist.js" type="text/javascript"></script>
@endpush
