@extends('cms.layouts.app')

@section('content')



    <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
        <div class="main-card mb-3 card">
            <div class="card-body"><h5 class="card-title">Request Information</h5>
                <form class="">
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="exampleEmail11" class="">Client</label>
                                <input value="{{$request->client['name']}}" name="name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="examplePassword11"
                                                                             class="">Employee</label><input
                                    name="employee" id="examplePassword11"
                                    value="{{$request->handyman['name']}} "
                                    class="form-control"></div>
                        </div>
                    </div>
                    <div class="position-relative form-group"><label for="exampleAddress" class="">Subject</label><input
                            name="address" id="exampleAddress" value="{{$request->subject}}" type="text"
                            class="form-control"></div>
                    <div class="position-relative form-group"><label for="exampleAddress2" class="">Description
                            </label><input name="address2" id="exampleAddress2" value="{{$request->description}}"
                                            type="text" class="form-control">
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="exampleCity"
                                                                             class="">Date</label><input name="city"
                                                                                                         id="exampleCity"
                                                                                                         type="text"
                                                                                                         value="{{$request->date}}"
                                                                                                         class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group"><label for="exampleState"
                                                                             class="">From</label><input name="state"
                                                                                                         id="exampleState"
                                                                                                         value="{{$request->from}}:00"
                                                                                                         type="text"
                                                                                                         class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="position-relative form-group"><label for="exampleZip" class="">To</label><input
                                    value="{{$request->to}}:00" name="zip" id="exampleZip" type="text"
                                    class="form-control"></div>
                        </div>
                    </div>


                    <div class="container mt-2" id="services">
                        <div class="row">
                            @if($request->images!=null)
                            @foreach($request->images as $image)
                                @if ( $loop->index % 4 == 0 )
                        </div>
                        <div class="row">
                            @endif
                            <div class="col-md-3 col-sm-6">
                                <div class="card card-block">
                                    <img src="{{config('image.path').$image}}" alt="later">
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>


                </form>

            </div>
        </div>

    </div>
@endsection


@push('js')


    <script src="https://unpkg.com/sweetalert2@7.19.1/dist/sweetalert2.all.js"></script>
    <script type="text/javascript">
        function deletePost(id) {
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    event.preventDefault();
                    document.getElementById('delete-form-' + id).submit();
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    swal(
                        'Cancelled',
                        'Your data is safe :)',
                        'error'
                    )
                }
            })
        }
    </script>
@endpush
@push('css')
    <style>
        * {
            box-sizing: border-box;
        }

        img {
            vertical-align: middle;
        }

        /* Position the image container (needed to position the left and right arrows) */
        .container {
            position: relative;
        }

        /* Hide the images by default */
        .mySlides {
            display: none;
        }

        /* Add a pointer when hovering over the thumbnail images */
        .cursor {
            cursor: pointer;
        }

        /* Next & previous buttons */
        .prev,
        .next {
            cursor: pointer;
            position: absolute;
            top: 40%;
            width: auto;
            padding: 16px;
            margin-top: -50px;
            color: white;
            font-weight: bold;
            font-size: 20px;
            border-radius: 0 3px 3px 0;
            user-select: none;
            -webkit-user-select: none;
        }

        /* Position the "next button" to the right */
        .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }

        /* On hover, add a black background color with a little bit see-through */
        .prev:hover,
        .next:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        /* Number text (1/3 etc) */
        .numbertext {
            color: #f2f2f2;
            font-size: 12px;
            padding: 8px 12px;
            position: absolute;
            top: 0;
        }

        /* Container for image text */
        .caption-container {
            text-align: center;
            background-color: #222;
            padding: 2px 16px;
            color: white;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        /* Six columns side by side */
        .column {
            float: left;
            width: 16.66%;
        }

        /* Add a transparency effect for thumnbail images */
        .demo {
            opacity: 0.6;
        }

        .active,
        .demo:hover {
            opacity: 1;
        }</style>
    <link rel="stylesheet" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <link href="{{asset('css/client/cards.css')}}" rel="stylesheet">
    <style>
        img:before {
            content: ' ';
            background-image: url();
        }
    </style>
@endpush



