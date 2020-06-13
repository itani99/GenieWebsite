@extends('front.client.profile.my-profile')
@push('css')
    <link href="{{asset('css/client/map.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/client/new-address.css')}}" rel="stylesheet" type="text/css">
    <style>
        #map {
            height: 550px;
        }
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
        <h1>Edit Address</h1>
    </div><!-- /.page-title -->

            <div class="background-white p20 mb30">
                <form method="post" action="{{route('client.address.update',$address['_id'])}}">
                    @csrf
                    @method('put')
                    <h4 class="page-title">
                        Address
                        <button type="submit" class="btn btn-primary btn-xs pull-right">Save</button>
                    </h4>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="address_name">Address Name, this will be displayed when choosing your location in a
                                request </label>
                            <input type="text" class="form-control" name="name" id="address_name" value="{{$address['name']}}" placeholder="Home Beirut">
                        </div>
                    </div>
                    <div class="map-position">
                        <input id="pac-input" name="map-input" class="controls" type="text" placeholder="Search Box">
                        <input type="hidden" name="lat" id="lat">
                        <input type="hidden" name="lng" id="lng">
                        <div id="map"></div>
                    </div><!-- /.map-property -->
                    <div class="row" style="margin-top:20px">
                        <div class="form-group col-sm-6">
                            <label for="street">Street</label>
                            <input type="text" class="form-control" name="street" id="street" value="{{$address['street']}}" placeholder="">
                        </div><!-- /.form-group -->
                        <div class="form-group col-sm-3">
                            <label for="house"> House Number/Name </label>
                            <input type="text" class="form-control" name="house" id="house" value="{{$address['building']}}" placeholder="">
                        </div><!-- /.form-group -->
                        <div class="form-group col-sm-3">
                            <label for="zip"> ZIP </label>
                            <input type="text" class="form-control" name="zip" id="zip" value="{{$address['zip']}}" placeholder="">
                        </div><!-- /.form-group -->
                    </div><!-- /.row -->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select name="property">
                                    <option selected="@if($address['property_type'] =='Apartment') selected @endif">Apartment</option>
                                    <option selected="@if($address['property_type'] =='Condo') selected @endif">Condo</option>
                                    <option selected="@if($address['property_type'] =='House') selected @endif">House</option>
                                    <option selected="@if($address['property_type'] =='Villa') selected @endif">Villa</option>
                                </select>
                            </div><!-- /.form-group -->
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <select name="contract">
                                    <option selected="@if($address['contract_type'] =='Rent') selected @endif">Rent</option>
                                    <option selected="@if($address['contract_type'] =='Sale') selected @endif">Sale</option>
                                </select>
                            </div><!-- /.form-group -->
                        </div><!-- /.col-* -->
                    </div><!-- /.row -->
                </form>
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
        function initAutocomplete() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: -33.8688, lng: 151.2195},
                zoom: 13,
                mapTypeId: 'roadmap'
            });
            var input = document.getElementById('pac-input');
            var searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            map.addListener('bounds_changed', function () {
                searchBox.setBounds(map.getBounds());
            });
            var markers = [];
            searchBox.addListener('places_changed', function () {
                var places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                markers.forEach(function (marker) {
                    marker.setMap(null);
                });
                markers = [];
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
                    markers.push(new google.maps.Marker({
                        map: map,
                        icon: icon,
                        title: place.name,
                        position: place.geometry.location
                    }));

                    if (place.geometry.viewport) {
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
