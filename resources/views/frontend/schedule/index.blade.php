@extends('frontend.layouts.layout')
@section('content')

    <!-- Navbar & Hero Start -->
    <div class="container-fluid position-relative p-0">
        @include('frontend.component.navbar')
    </div>
    <!-- Navbar & Hero End -->

    <!-- Header Start -->
    <div class="container-fluid bg-breadcrumb" style="background: linear-gradient(rgba(19, 53, 123, 0.5), rgba(19, 53, 123, 0.5)), url({{ asset('frontend/images/breadcrumb-bg.jpg') }});">
        <div class="container text-center py-5" style="max-width: 900px;">
            <h1 class="text-white display-3 mb-4">Contact Us</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item active text-white">Contact</li>
            </ol>
        </div>
    </div>
    <!-- Header End -->

    <!-- Contact Start -->
    <div class="container-fluid contact bg-light py-5">
        <div class="container py-5">
            <div class="mx-auto text-center mb-5" style="max-width: 900px;">
                <h5 class="section-title px-3">Build schedule</h5>
                <h1 class="mb-0">Build Your Schedule</h1>
            </div>
            <div class="row g-5 align-items-stretch">
                <div class="col-lg-4 image-left-schedule">
                    <div class="rounded h-100 p-4 position-relative d-flex flex-column">
                        <div class="text-start w-75 position-absolute top-0 start-0">
                            <img src="{{asset('frontend/images/destination-1.jpg')}}" alt="">
                        </div>
                        <div class="text-end w-75 position-absolute end-0" style="    transform: translateY(50%) !important;">
                            <img src="{{asset('frontend/images/destination-2.jpg')}}" alt="">
                        </div>
                        <div class="text-center w-75 position-absolute bottom-0">
                            <img src="{{asset('frontend/images/destination-3.jpg')}}" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <h3 class="mb-2">Enter trip information</h3>
                    <form action="{{ route('map') }}" method="GET" class="">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" id="searchInput" name="address" class="form-control border-0" autocomplete="off" placeholder="Nhập địa điểm..." required>
                                    <label for="searchInput">Where do you want to go ?</label>
                                    <div class="dropdown">
                                        <div id="results" class="dropdown-menu rounded-bottom rounded-0 w-100 p-0"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="date" class="form-control border-0" id="start_date" name="start_date" placeholder="">
                                    <label for="start_date">Departure date</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="date" class="form-control border-0" id="end_date" name="end_date" placeholder="">
                                    <label for="end_date">Return date</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select class="form-control bg-white border-0" name="transportation" id="currency">
                                            <option value="Motorbike (Personal)">Motorbike (Personal)</option>
                                            <option value="Car (Personal)">Car (Personal)</option>
                                            <option value="Bicycle (Personal)">Bicycle (Personal)</option>
                                            <option value="Motorbike (Rental)">Motorbike (Rental)</option>
                                            <option value="Car (Rental)">Car (Rental)</option>
                                            <option value="Bicycle (Rental)">Bicycle (Rental)</option>
                                            <option value="Public transport">Public transport</option>
                                            <option value="Unknown">Unknown</option>
                                    </select>
                                    <label for="end_date">transportation</label>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-floating">
                                    <input type="number" class="form-control border-0" id="budget" name="budget" placeholder="">
                                    <label for="budget">Budget</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select class="form-control bg-white border-0" name="currency" id="currency">
                                        @foreach($currencies as $currency)
                                            <option value="{{$currency->code }}">{{$currency->country}} - {{$currency->code }}</option>
                                        @endforeach
                                    </select>
                                    <label for="currency">Currency</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="number" class="form-control border-0" name="adults" id="adults" placeholder="">
                                            <label for="adults">Adults > 10 years old</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="number" class="form-control border-0" name="children-2" id="children-2" placeholder="">
                                            <label for="children-2">Children 2 - 10 years old</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="number" class="form-control border-0" name="children-1" id="children-1" placeholder="">
                                            <label for="children-1">Children < 2 years old </label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <select data-placeholder="Interest" class="chosen-select form-control" name="interest[]" multiple >
                                        @foreach($preferences as $preference)
                                            <option value="{{$preference->name}}"> {{$preference->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit">Create a schedule now</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            @if(isset($weather))
            <div class="mt-5 row bg-white rounded schedule-page" style="height: 70rem">

                    <h2 class="text-center mt-4 mb-3">{{ $weather['location']['name'] }}, {{ $weather['location']['country'] }}</h2>
                    <hr>
                <div class="col-lg-7 ps-4 h-90">
                    <div class="weather h-15">
                        @if(isset($error))
                            <p style="color: red;">{{ $error }}</p>
                        @else

                            @if(isset($weather['forecast']) && isset($weather['forecast']['forecastday']))
                                <ul class="row mb-0 pb-4">
                                    @foreach($weather['forecast']['forecastday'] as $day)
                                        <li class="col-lg-4">
                                            <strong style="color: #0b204a">{{ \Carbon\Carbon::parse($day['date'])->format('d/m/Y') }}</strong>
                                            <p><strong>Temperature:</strong> {{ $day['day']['avgtemp_c'] }}°C</p>
                                            <p><strong>Humidity:</strong> {{ $day['day']['avghumidity'] }}%</p>
                                            <p><strong>Wind speed:</strong> {{ $day['day']['maxwind_kph'] }} km/h</p>
                                            <p><strong>Status:</strong> {{ $day['day']['condition']['text'] }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p>Không có dữ liệu dự báo thời tiết.</p>
                            @endif
                        @endif
                    </div>
                    <div class="h-85 border-top">
                        <!-- Carousel Start -->
                        <div class="carousel-header h-95">
                            <div id="carouselId" class="carousel slide h-100 mb-0 mt-2 ps-0" data-bs-ride="carousel" data-bs-interval="false">
                                <ol class="carousel-indicators menu-schedule mb-1">
                                    <li data-bs-target="#carouselId" data-bs-slide-to="0" class="border-0 m-0 p-0 active col-lg-4">List of locations</li>
                                    <li data-bs-target="#carouselId" data-bs-slide-to="1" class="border-0 m-0 p-0 col-lg-4 Schedule-tab">Schedule</li>
                                    <li data-bs-target="#carouselId" data-bs-slide-to="2" class="border-0 m-0 p-0  col-lg-4 Event-tab">Event/Activity</li>
                                </ol>
                                <div class="carousel-inner border-top h-95 mb-2" role="listbox">
                                    <div class="carousel-item active h-100 list-of-locations mt-2">
                                        @if(isset($places))
                                            @foreach($places as $place => $thong_tin)
                                                <div class="w-100 h-30 border me-1 mb-2">
                                                    <div class="row h-100 w-100 ms-1">
                                                        <a href="#" class="h-100 col-lg-11 p-0 d-flex">
                                                            <div class="w-35 align-content-center position-relative h-100 p-0">
                                                                <img class="w-75 h-90 ms-2 rounded  " src="{{asset('frontend/images/destination-3.jpg')}}">
                                                                <img class="w-65 h-80 ms-2 rounded position-absolute sec-image" src="{{asset('frontend/images/destination-3.jpg')}}">
                                                            </div>
                                                            <div class="w-65 h-60 m-auto p-0">
                                                                @foreach ($thong_tin as $key => $value)
                                                                    @if($key == "Tên" || $key == "Tên địa điểm" || $key == 0)
                                                                            <p class="fw-bold">{{$value}}</p>
                                                                    @else
                                                                        <p class="">
                                                                            @if(is_array($value))
                                                                                @foreach($value as $tag)
                                                                                    <span class="btn">{{ $tag }}</span>
                                                                                @endforeach
                                                                            @else
                                                                                {{$key}} : {{$value}}
                                                                            @endif
                                                                        </p>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </a>
                                                        <a href="#" class="col-lg-1 p-0"><div class=" h-100 p-0 delete align-content-center white"><i class="bi bi-trash"></i></div></a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div id="schedule-response" class="carousel-item h-100 schedule">
                                        <div id="btn-build-schedule" class="container-fluid h-50 mt-2">
                                            <div id="schedule-response" class="container h-100 text-center">
                                                <button id="generateSchedule" class="rounded btn btn-primary p-3 m-t-100" data-place-names="{{ json_encode($for_schedule) }}">
                                                    <span class="d-none" id="get-url-schedule" data-url="{{route('build-schedule')}}"></span>
                                                    <span class="d-block"><i class="bi bi-stars"></i> Generate Detailed Itinerary <i class="bi bi-stars"></i></span>
                                                    <span class="d-block fw-normal">- Based on a list of locations -</span>
                                                </button>

                                            </div>
                                        </div>
                                        <!-- Spinner Start -->
                                        <div class="position-relative h-50">
                                            <div id="spinner2" class="show bg-white position-absolute translate-middle w-100 top-50 start-50 align-items-center justify-content-center d-none">
                                                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Spinner End -->
                                    </div>
                                    <div id="event-response" class="carousel-item h-100 eventAndActivity mt-2">
                                        <div id="btn-get-event" class="container-fluid h-50 mt-2">
                                            <div id="schedule-response" class="container h-100 text-center">
                                                <button id="getEvent" class="rounded btn btn-primary p-3 m-t-100" data-address="{{ json_encode($for_event) }}">
                                                    <span class="d-none" id="get-url-event" data-url="{{route('get-event')}}"></span>
                                                    <span class="d-block"><i class="bi bi-stars"></i>&nbsp; Check out current events and activities &nbsp;<i class="bi bi-stars"></i></span>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- Spinner Start -->
                                        <div class="position-relative h-50">
                                            <div id="spinner3" class="show bg-white position-absolute translate-middle w-100 top-50 start-50 align-items-center justify-content-center d-none">
                                                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Spinner End -->

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Carousel End -->
                    </div>
                </div>
                <div class="col-lg-5 p-0">
                    @if(isset($error))
                        <div class="alert alert-danger">{{ $error }}</div>
                    @elseif(isset($map))
                        <div class="card border-start-0 border-0">
                            <div class="card-body pt-0">
                                <div id="map" style="width: 100%; height: 61rem;"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
    <!-- Contact End -->

    <!-- Subscribe Start -->
    <div class="container-fluid subscribe py-5">
        <div class="container text-center py-5">
            <div class="mx-auto text-center" style="max-width: 900px;">
                <h5 class="subscribe-title px-3">Subscribe</h5>
                <h1 class="text-white mb-4">Our Newsletter</h1>
                <p class="text-white mb-5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum tempore nam, architecto doloremque velit explicabo? Voluptate sunt eveniet fuga eligendi! Expedita laudantium fugiat corrupti eum cum repellat a laborum quasi.
                </p>
                <div class="position-relative mx-auto">
                    <input class="form-control border-primary rounded-pill w-100 py-3 ps-4 pe-5" type="text" placeholder="Your email">
                    <button type="button" class="btn btn-primary rounded-pill position-absolute top-0 end-0 py-2 px-4 mt-2 me-2">Subscribe</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Subscribe End -->

    <!-- Footer Start -->
    <div class="container-fluid footer py-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="footer-item d-flex flex-column">
                        <h4 class="mb-4 text-white">Get In Touch</h4>
                        <a href=""><i class="fas fa-home me-2"></i> 123 Street, New York, USA</a>
                        <a href=""><i class="fas fa-envelope me-2"></i> info@example.com</a>
                        <a href=""><i class="fas fa-phone me-2"></i> +012 345 67890</a>
                        <a href="" class="mb-3"><i class="fas fa-print me-2"></i> +012 345 67890</a>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-share fa-2x text-white me-2"></i>
                            <a class="btn-square btn btn-primary rounded-circle mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn-square btn btn-primary rounded-circle mx-1" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn-square btn btn-primary rounded-circle mx-1" href=""><i class="fab fa-instagram"></i></a>
                            <a class="btn-square btn btn-primary rounded-circle mx-1" href=""><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="footer-item d-flex flex-column">
                        <h4 class="mb-4 text-white">Company</h4>
                        <a href=""><i class="fas fa-angle-right me-2"></i> About</a>
                        <a href=""><i class="fas fa-angle-right me-2"></i> Careers</a>
                        <a href=""><i class="fas fa-angle-right me-2"></i> Blog</a>
                        <a href=""><i class="fas fa-angle-right me-2"></i> Press</a>
                        <a href=""><i class="fas fa-angle-right me-2"></i> Gift Cards</a>
                        <a href=""><i class="fas fa-angle-right me-2"></i> Magazine</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="footer-item d-flex flex-column">
                        <h4 class="mb-4 text-white">Support</h4>
                        <a href=""><i class="fas fa-angle-right me-2"></i> Contact</a>
                        <a href=""><i class="fas fa-angle-right me-2"></i> Legal Notice</a>
                        <a href=""><i class="fas fa-angle-right me-2"></i> Privacy Policy</a>
                        <a href=""><i class="fas fa-angle-right me-2"></i> Terms and Conditions</a>
                        <a href=""><i class="fas fa-angle-right me-2"></i> Sitemap</a>
                        <a href=""><i class="fas fa-angle-right me-2"></i> Cookie policy</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="footer-item">
                        <div class="row gy-3 gx-2 mb-4">
                            <div class="col-xl-6">
                                <form>
                                    <div class="form-floating">
                                        <select class="form-select bg-dark border" id="select1">
                                            <option value="1">Arabic</option>
                                            <option value="2">German</option>
                                            <option value="3">Greek</option>
                                            <option value="3">New York</option>
                                        </select>
                                        <label for="select1">English</label>
                                    </div>
                                </form>
                            </div>
                            <div class="col-xl-6">
                                <form>
                                    <div class="form-floating">
                                        <select class="form-select bg-dark border" id="select1">
                                            <option value="1">USD</option>
                                            <option value="2">EUR</option>
                                            <option value="3">INR</option>
                                            <option value="3">GBP</option>
                                        </select>
                                        <label for="select1">$</label>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <h4 class="text-white mb-3">Payments</h4>
                        <div class="footer-bank-card">
                            <a href="#" class="text-white me-2"><i class="fab fa-cc-amex fa-2x"></i></a>
                            <a href="#" class="text-white me-2"><i class="fab fa-cc-visa fa-2x"></i></a>
                            <a href="#" class="text-white me-2"><i class="fas fa-credit-card fa-2x"></i></a>
                            <a href="#" class="text-white me-2"><i class="fab fa-cc-mastercard fa-2x"></i></a>
                            <a href="#" class="text-white me-2"><i class="fab fa-cc-paypal fa-2x"></i></a>
                            <a href="#" class="text-white"><i class="fab fa-cc-discover fa-2x"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Copyright Start -->
    <div class="container-fluid copyright text-body py-4">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-md-6 text-center text-md-end mb-md-0">
                    <i class="fas fa-copyright me-2"></i><a class="text-white" href="#">Your Site Name</a>, All right reserved.
                </div>
                <div class="col-md-6 text-center text-md-start">
                    <!--/*** This template is free as long as you keep the below author’s credit link/attribution link/backlink. ***/-->
                    <!--/*** If you'd like to use the template without the below author’s credit link/attribution link/backlink, ***/-->
                    <!--/*** you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". ***/-->
                    Designed By <a class="text-white" href="https://htmlcodex.com">HTML Codex</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Copyright End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary btn-primary-outline-0 btn-md-square back-to-top"><i class="fa fa-arrow-up"></i></a>

    @if(isset($map))
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            var lat = {{ $lat ?? 21.0285 }};
            var lon = {{ $lon ?? 105.8542 }};
            var addr = '{{$address}}';

            var map = L.map('map').setView([lat, lon], 9);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var marker = L.marker([lat, lon]).addTo(map)
                .bindPopup(addr)
                .openPopup();
        </script>

    @endif

@endsection
