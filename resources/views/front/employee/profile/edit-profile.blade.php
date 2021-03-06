@extends('front.employee.profile.my-profile')
@push('css')
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
        .error:focus,.error{
            color: #d25858 !important;
            border-bottom: 1px solid red !important;
            box-shadow: 0 0 0 0 !important;
        }
        form label.error {
            border-bottom: 0  !important;
        }
        #price-error{
            top:60px !important;
            font-size:13px !important;
        }
        .materialert.error{
            background-color: #c62828 !important;
            color: #fff !important;
        }

    </style>
@endpush
@section('profile-content')
    <div class="page-title">
        <h1>Edit Profile</h1>
    </div><!-- /.page-title -->

    <div class="background-white p20 mb30">
        <form method="post" action="{{route('employee.contact.update')}}" name="contact">
            @csrf
            @method('put')
            <h3 class="page-title">
                General
                <input type="submit" value="Save" class="btn btn-primary btn-xs pull-right"/>
            </h3>

            <div class="row">
                <div class="form-group col-sm-6">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{$user->name}}">
                </div><!-- /.form-group -->

                <div class="form-group col-sm-6">
                    <label>Gender</label>
                    <p>
                        <label>
                            <input class="with-gap" name="gender" type="radio" value="male"
                                   @if($user->gender=='male') checked @endif />
                            <span>male</span>
                        </label>
                        <label>
                            <input class="with-gap" name="gender" type="radio" value="female"
                                   @if($user->gender=='female') checked @endif />
                            <span>female</span>
                        </label>
                    </p>
                </div><!-- /.form-group -->

                <div class="form-group col-sm-6">
                    <label for="email">E-mail</label>
                    <input type="text" name="email" id="email" class="form-control" value="{{$user->email}}">
                </div><!-- /.form-group -->

                <div class="form-group col-sm-6">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{$user->phone}}">
                </div><!-- /.form-group -->
            </div><!-- /.row -->
        </form>
    </div>
{{--    <div class="background-white p20 mb30">--}}
{{--        <form method="post" action="{{route('employee.connections.update')}}">--}}
{{--            @csrf--}}
{{--            @method('put')--}}
{{--            <h3 class="page-title">--}}
{{--                Social Connections--}}
{{--                <input type="submit" value="Save" class="btn btn-primary btn-xs pull-right"/>--}}
{{--            </h3>--}}
{{--            --}}{{--            <div class="row" style="position:relative;">--}}
{{--            --}}{{--            <div class="fixed-action-btn" style="position:absolute;">--}}
{{--            --}}{{--                <a class="btn-floating btn-large red">--}}
{{--            --}}{{--                    <i class="large material-icons">mode_edit</i>--}}
{{--            --}}{{--                </a>--}}
{{--            --}}{{--                <ul>--}}
{{--            --}}{{--                    <li><a class="btn-floating red"><i class="material-icons">insert_chart</i></a></li>--}}
{{--            --}}{{--                    <li><a class="btn-floating green"><i class="material-icons">facebook</i></a></li>--}}
{{--            --}}{{--                    <li><a class="btn-floating blue"><i class="material-icons">twitter</i></a></li>--}}
{{--            --}}{{--                    <li><a class="btn-floating yellow darken-1"><i class="material-icons">instagram</i></a></li>--}}
{{--            --}}{{--                </ul>--}}
{{--            --}}{{--            </div>--}}
{{--            --}}{{--            </div>--}}
{{--            <div class="form-horizontal">--}}
{{--                <div class="form-group">--}}
{{--                    <label for="facebook" class="col-sm-2 control-label">Facebook</label>--}}
{{--                    <div class="col-sm-9">--}}
{{--                        <input type="text" class="form-control" name="facebook" id="facebook"--}}
{{--                               value="@if($user->facebook){{$user->facebook}}@else http://facebook.com/@endif">--}}
{{--                    </div><!-- /.col-* -->--}}
{{--                </div><!-- /.form-group -->--}}

{{--                <div class="form-group">--}}
{{--                    <label for="twitter" class="col-sm-2 control-label">Twitter</label>--}}
{{--                    <div class="col-sm-9">--}}
{{--                        <input type="text" id="twitter" name="twitter" class="form-control"--}}
{{--                               value="@if($user->twitter){{$user->twitter}}@else http://twitter.com/@endif">--}}
{{--                    </div><!-- /.col-* -->--}}
{{--                </div><!-- /.form-group -->--}}
{{--                <div class="form-group">--}}
{{--                    <label for="instagram" class="col-sm-2 control-label">Instagram</label>--}}
{{--                    <div class="col-sm-9">--}}
{{--                        <input type="text" class="form-control" name="instagram" id="instagram"--}}
{{--                               value="@if($user->instagram){{$user->instagram}}@else http://instagram.com/@endif">--}}
{{--                    </div><!-- /.col-* -->--}}
{{--                </div><!-- /.form-group -->--}}
{{--            </div><!-- /.form-inline -->--}}
{{--        </form>--}}
{{--    </div><!-- /.background-white -->--}}
    <div class="background-white p20 mb30">
        <form method="post" action="{{route('employee.address.update')}}">
            @csrf
            @method('put')
            <h3 class="page-title">
                Address
                <input type="submit" value="Save" class="btn btn-primary btn-xs pull-right"/>
            </h3>
            <div class="map-position">
                <input id="pac-input" name="map-input" class="controls" type="text" placeholder="Search Box">
                <input type="hidden" name="lat" value="{{$user->employee_address['location'][1]}}" id="lat">
                <input type="hidden" name="lng" id="lng" value="{{$user->employee_address['location'][0]}}">
                <div id="map"></div>
            </div><!-- /.map-property -->
            <div class="row">
                <div class="form-group col-sm-6">
                    <label for="street">Street</label>
                    <input type="text" class="form-control" id="street" name="street"
                           value="{{$user->employee_address['street']}}">
                </div><!-- /.form-group -->
                <div class="form-group col-sm-3">
                    <label for="building">Building Number/Name</label>
                    <input type="text" class="form-control" id="building" name="building"
                           value="{{$user->employee_address['building']}}">
                </div><!-- /.form-group -->
                <div class="form-group col-sm-3">
                    <label for="zip">ZIP</label>
                    <input type="text" class="form-control" id="zip" name="zip"
                           value="{{$user->employee_address['zip']}}">
                </div><!-- /.form-group -->
            </div><!-- /.row -->
        </form>
    </div>
    <div class="background-white p20 mb30">
        <form method="post" action="{{route('employee.biography.update')}}" name="biographyForm">
            @csrf
            @method('put')
            <h3 class="page-title">
                Biography
                <input type="submit" value="Save" class="btn btn-primary btn-xs pull-right"/>
            </h3>
            <div class="row">
                <div class="input-field col s12">
                    <textarea id="biography" name="biography"
                              class="materialize-textarea">{{$user->biography}}</textarea>
                    <label for="biography"></label>
                </div>
            </div>
        </form>
    </div>
    <div class="background-white p20 mb30">
        <form method="post" action="{{route('employee.services.update')}}" name="servicesForm">
            @csrf
            @method('put')
            <h3 class="page-title">
                Services
                <input type="submit" value="Save" class="btn btn-primary btn-xs pull-right"/>
            </h3>
            <div class="row">
                <div class="input-field col s12 ">
                    <select class="icons" name="services[]" id="services" multiple="multiple">
                        <option value="" disabled>Choose your services</option>
                        @isset($user->service_ids)
                        @foreach($services as $service)
                            <option value="{{$service->id}}" data-icon="{{config('image.path').$service->image}}"
                                    @if(in_array($service->id,$user->service_ids))
                                    selected
                                @endif
                            >{{$service->name}}</option>
                        @endforeach
                        @else
                            @foreach($services as $service)
                                <option value="{{$service->id}}" data-icon="{{config('image.path').$service->image}}">
                                    {{$service->name}}
                                </option>
                            @endforeach
                            @endisset
                    </select>
                    <label for="services">Choose your type of services</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 ">
                    <label style="font-size: 16px"> Price per hour</label>
                    <input type="text" class="form-control" id="price" name="price" placeholder="How much do you get paid per hour?" value="{{$user->price}}">
                </div>
            </div>
        </form>
    </div>
@endsection
@push('js')
    <script src="/public/js/jquery.validate.min.js"></script>
    <script>
        $(function() {
            // Initialize form validation on the registration form.
            // It has the name attribute "registration"
            $("form[name='contact']").validate({
                rules: {
                    name: "required",
                    gender : "required",
                    email: {
                        required: true,
                        email: true
                    },
                    phone: {
                        required: true,
                        minlength: 7,
                        number:true
                    }
                },
                messages: {
                    name: "Please enter your name",
                    gender: "Please choose your gender",
                    email: "Please enter a valid email address",
                    phone: "Please enter a valid phone number"
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
    <script>
        $(function() {
            $("form[name='biographyForm']").validate({
                rules: {
                    biography: {
                        required: true,
                        minlength: 20,
                    }
                },
                messages: {
                    biography: "Please fill in your biography (at least 20 characters)",
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
    <script>
        $(function() {
            $("form[name='servicesForm']").validate({
                rules: {
                    price: {
                        required:true,
                        min:1,
                        number:true
                    }
                    },
                messages: {
                    price: "Please enter a valid price (per hour)",
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var select = document.querySelector('#services');
            var instance = M.FormSelect.init(select);
        });
        $(document).on('change', '#services', function () {
            var servicesArr = $(this).val();
            if (servicesArr.length > 3) {
                alert('maximum of 3 services is allowed')
                servicesArr = servicesArr.slice(0, 3);
                $('#services').val(servicesArr);
                var select = document.querySelector('#services');
                var instance = M.FormSelect.init(select);
            }
        });
        // document.addEventListener('DOMContentLoaded', function() {
        //     var elems = document.querySelectorAll('.fixed-action-btn');
        //     var instances = M.FloatingActionButton.init(elems, {
        //         direction: 'left'
        //     });
        // });
    </script>
    <script>
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

                    // Create a marker for each place.
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
@endpush
