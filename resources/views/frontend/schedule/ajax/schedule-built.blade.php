<div class="container-fluid h-5 mt-2">
    <div id="schedule-response" class="container h-100 text-center">
        <div class="schedule-carousel h-100">
            @foreach($plans as $dayKey => $dayDetails)
                @php $dayKey_id = str_replace('Ngày ', '', $dayKey); @endphp
                <div class="schedule-item text-center rounded pb-1 target-day"  data-target="myTarget{{$dayKey_id}}">
                    <div class="schedule-day bg-light rounded p-1 ">
                        {{ str_replace(':', '', $dayKey) }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<div class="carousel-item active h-100 detailed-schedule mt-2" id="schedule-content">
    @foreach($plans as $day => $activities)
        @php $day_id = str_replace('Ngày ', '', $day); @endphp
        <h4 id="myTarget{{$day_id}}">{{ $day }}</h4>
        @foreach($activities as $activity => $details)
{{--            <div class="h-95">--}}
                <div class="middle-border"></div>
                <div class="time fw-bold ps-0">{{ $activity }}</div>
                @foreach($details as $detail)
                    @php
                        $type = $detail['type'] ?? '';
                        $detailInfo = $detail['details'] ?? [];
                    @endphp

                    @if(in_array($type, ['Ăn sáng', 'Ăn trưa', 'Ăn tối', 'Địa điểm tham quan', 'Chỗ ngủ']))
                        <div class="w-100 position-relative h-25 mt-2">
                            <div class="row w-10 h-100 position-absolute start-0 d-flex flex-wrap align-content-center">
                                <div class="time">
                                    {{ $type }}
                                </div>
                            </div>
                            <div class="row h-100 w-90 ms-1 position-absolute end-0 border me-1 mb-2 rounded-start">
                                <a href="#" class="h-100 col-lg-11 p-0 d-flex">
                                    <div class="w-35 align-content-center position-relative h-100 p-0">
                                        <img class="w-75 h-90 ms-2 rounded" src="{{ asset('frontend/images/destination-3.jpg') }}">
                                        <img class="w-65 h-80 ms-2 rounded position-absolute sec-image" src="{{ asset('frontend/images/destination-3.jpg') }}">
                                    </div>
                                    <div class="w-65 h-60 m-auto p-0">
                                        @if($type == 'Địa điểm tham quan' && is_array($detailInfo))
                                            <p class="fw-bold">{{ $detailInfo['Tên địa điểm'] ?? '' }}</p>
                                            <p><span class="btn">Rating</span></p>
                                            <p><span class="btn">{{ $detailInfo['Thời gian tham quan'] ?? '' }}</span></p>
                                        @else
                                            <p class="fw-bold">{{ is_string($detailInfo) ? $detailInfo : '' }}</p>
                                            <p><span class="btn">Rating</span></p>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        </div>
                    @elseif($type == 'Di chuyển' && is_array($detailInfo))
                        <div class="w-100 position-relative h-5 mt-2">
                            <div class="row w-10 h-100 position-absolute start-0 d-flex flex-wrap align-content-center">
                                <div class="time">
                                    {{ $type }}
                                </div>
                            </div>
                            <div class="row h-100 w-90 ms-1 position-absolute end-0 border me-1 mb-2 rounded">
                                <div class="h-100 w-85">
                                    <div class="w-100 h-100 p-0 d-flex align-content-center">
                                        @php
                                            $vehicle = $detailInfo['Phương tiện di chuyển'] ?? '';
                                        @endphp
                                        @if($vehicle == 'Motorbike')
                                            <i class="fa-solid fa-motorcycle" style="font-size: 1.25rem; align-content: center; margin-right: 1rem;"></i>
                                        @elseif(in_array($vehicle, ['Car', 'Taxi']))
                                            <i class="fa-solid fa-car-side" style="font-size: 1.25rem; align-content: center; margin-right: 1rem;"></i>
                                        @endif
                                        <div class="d-flex flex-wrap align-items-center h-75 m-auto mx-0 pe-2">
                                            {{ $detailInfo['Điểm đi'] ?? '' }}
                                            <i class="bi bi-arrow-right" style="font-size: 1rem; margin: 0 0.75rem;"></i>
                                            {{ $detailInfo['Điểm đến'] ?? '' }}
                                        </div>
                                        <div class="px-2 d-flex flex-wrap h-75 m-auto mx-0 align-items-center border-start">
                                            {{ $detailInfo['Thời gian'] ?? '' }}
                                        </div>
                                        <div class="px-2 d-flex flex-wrap h-75 m-auto mx-0 align-items-center border-start">
                                            {{ $detailInfo['Khoảng cách'] ?? '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="h-100 w-15 p-0">
                                    <div class="btn w-100 btn-primary h-100 p-0 align-content-center white text-center">Xem đường đi</div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
{{--            </div>--}}
        @endforeach
    @endforeach
</div>
