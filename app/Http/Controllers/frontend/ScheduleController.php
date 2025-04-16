<?php

namespace App\Http\Controllers\frontend;
use App\Models\Preference;
use App\Services\ProvinceService;
use App\Services\MapAPIService;
use App\Services\WeatherService;
use App\Services\GeminiService;
use App\Services\WikipediaService;
use App\Services\RapidApiService;
use App\Services\GoogleMapsScraper;
use App\Services\Map4DService;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScheduleController
{
    protected $goMapsService;
    protected $weatherService;
    protected $provinceService;
    protected $geminiService;
    protected $wikipediaService;
    protected $rapidApiService;

    protected $map4DService;


    public function __construct(MapAPIService $goMapsService, ProvinceService $provinceService, WeatherService $weatherService,
                                GeminiService $geminiService, WikipediaService $wikipediaService, RapidApiService $rapidApiService,
                                Map4DService  $map4DService)
    {
        $this->goMapsService = $goMapsService;
        $this->provinceService = $provinceService;
        $this->weatherService = $weatherService;
        $this->geminiService = $geminiService;
        $this->wikipediaService = $wikipediaService;
        $this->rapidApiService = $rapidApiService;
        $this->map4DService = $map4DService;

    }

    public function showRoute(Request $request)
    {
        // Lấy tọa độ từ request hoặc dùng giá trị mặc định
        $origin = $request->input('origin', '21.0285,105.8522'); // Mặc định Hồ Hoàn Kiếm
        $destination = $request->input('destination', '21.0367,105.8347'); // Mặc định Lăng Bác
        $mode = $request->input('mode', 'car');

        // Gọi phương thức getRoute từ Map4DService
        $routeData = $this->map4DService->getRoute($origin, $destination, $mode);

        if (!$routeData) {
            // Xử lý khi không lấy được dữ liệu (API lỗi, ...)
            // Ví dụ: quay lại trang trước với thông báo lỗi
            return back()->with('error', 'Không thể tìm thấy đường đi. Vui lòng thử lại.');
        }

        // Nếu thành công, truyền dữ liệu routeData sang View
        // $routeData bây giờ chứa thông tin JSON từ API (vd: các đoạn đường, thời gian, khoảng cách)
        return view('test', [
            'routeData' => $routeData,
            'origin' => $origin,
            'destination' => $destination,
            'mode' => $mode
            // Truyền thêm các dữ liệu khác nếu cần cho View
        ]);
    }
    public function searchAddress(Request $request)
    {
        $address = $request->input('address');
        if (!$address) {
            return response()->json(['error' => 'Vui lòng nhập địa chỉ'], 400);
        }

        $geocodeResult = $this->map4dService->geocode($address);

        if (!$geocodeResult || empty($geocodeResult['result'])) {
            return response()->json(['error' => 'Không tìm thấy địa chỉ'], 404);
        }

        // Trả về kết quả dạng JSON (thường dùng cho AJAX request)
        return response()->json($geocodeResult);
    }
    public function index()
    {
        $currencies = Currency::query()->get();
        $preferences = Preference::query()->get();
        return view('frontend.schedule.index', compact('currencies', 'preferences'));
    }
    public function testMap($placeName)
    {
        $scraper = new GoogleMapsScraper();
        $data = $scraper->getPlaceInfo($placeName);

        return response()->json($data);
    }

    public function map(Request $request)
    {
        $address = $request->input('address');
        $address = str_replace(['Tỉnh ', 'Thành phố '], '', $address);
        $map = null;
        $error = null;

        $endpoint = 'weather';
        $params = [
            'query' => $address,
        ];

        $map = $this->rapidApiService->fetchData($params);

        if ($address) {
            $result = $this->goMapsService->geocode($address); // Gọi API Nominatim

            if (!$result || empty($result)) {
                $error = 'Không tìm thấy địa điểm.';
            } else {
                $map = $result[0];
            }
        }
        if (!empty($map)) {
            $lat = $map['lat'];
            $lon = $map['lon'];
        } else {
            $lat = 10.8206;
            $lon = 106.6281;
        }

        $currencies = Currency::query()->get();
        $weather = $this->weatherService->getWeatherByCity($address);

        if (!$weather) {
            return view('frontend.weather', [
                'error' => "Không thể lấy thông tin thời tiết của thành phố: {$address}. Vui lòng thử lại!"
            ]);
        }

        $address = $request->input('address');
        $address = str_replace(['Tỉnh ', 'Thành phố '], '', $address);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $days = 3;
        if ($startDate && $endDate) {
            $days = (new \DateTime($startDate))->diff(new \DateTime($endDate))->days;
        }
        $budget = floatval($request->input('budget', 0));
        $currencyCode = $request->input('currency', 'VND');

        $adults = $request->input('adults');
        $children_1 = $request->input('children-1');
        $children_2 = $request->input('children-2');
        $transportation = $request->input('transportation');

        $interest = $request->input('interest');
        $interest = implode(', ', $interest);

        $preferences = $request->input('interest');

        $places = $this->geminiService->getTourismInfo($address, $preferences);
        $placeNames = array_keys($places);

        $placeNames = implode(", ", $placeNames);

        $preferences = Preference::query()->get();
        $address = convertVietnameseToLatin($address);

        $params = $request->query();
        $query = http_build_query($params);
        $finalUrl = url('/build-schedule') . '?' . $query;


        $data = [
            'map' => $map,
            'currencies' => $currencies,
            'weather' => $weather,
            'error' => $error,
            'preferences' => $preferences,
            'lat' => $lat,
            'lon' => $lon,
            'address' => $address,
            'places' => $places,
            'finalUrl' => $finalUrl,
            'for_schedule' => [
                'address' => $address,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'placeNames' => $placeNames,
                'budget' => $budget,
                'currency' => $currencyCode,
                'adults' => $adults,
                'children_1' => $children_1,
                'children_2' => $children_2,
                'transportation' => $transportation,
                'interest' => $interest
            ],
            'for_event' => [
                'address' => $address,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ];
        return view('frontend.schedule.index', $data);
    }
    public function build_schedule(Request $request)
    {
        $placeNames = $request->input('placeNames');
//        dd($request->all());
        $data = [
            'address' => $placeNames['address'],
            'start_date' => $placeNames['start_date'],
            'end_date' => $placeNames['end_date'],
            'placeName' => $placeNames['placeNames'],
            'budget' => $placeNames['budget'],
            'currency' => $placeNames['currency'],
            'adults' => $placeNames['adults'],
            'children_1' => $placeNames['children_1'],
            'children_2' => $placeNames['children_2'],
            'transportation' => $placeNames['transportation'],
            'interest' => $placeNames['interest'],
        ];



        $currencies = Currency::query()->get();
        $preferences = Preference::query()->get();

        $plans = $this->geminiService->generateItinerary($data);

        return view('frontend.schedule.ajax.schedule-built', compact('plans', 'currencies', 'preferences'));

    }
    public function getEventAndActivity(Request $request)
    {
//        print_r($request->all());
        $address = $request->input('address');
//        dd($address);
        $data = [
            'address' => $address['address'],
            'start_date' => $address['start_date'],
            'end_date' => $address['end_date']
        ];

//        dd($data);

//        $currencies = Currency::query()->get();
//        $preferences = Preference::query()->get();

        $activity = $this->geminiService->getEvent($data);
        dd($activity);
        return view('frontend.schedule.ajax.schedule-built', compact('plans', 'currencies', 'preferences'));

    }
    public function getDirections(Request $request)
    {
        $origin = $request->input('origin');
        $destination = $request->input('destination');

        if (!$origin || !$destination) {
            return response()->json(['error' => 'Vui lòng nhập điểm đi và điểm đến.'], 400);
        }

        $result = $this->goMapsService->getDirections($origin, $destination);

        return response()->json($result);
    }


    public function search(Request $request)
    {
        $query = trim($request->query('q', ''));

        if (empty($query)) {
            return response()->json([]);
        }

        $provinces = $this->provinceService->getProvinces();

        if (empty($provinces)) {
            return response()->json([]);
        }

        $filtered = collect($provinces)->filter(function ($province) use ($query) {
            $provinceName = str_replace(['Tỉnh ', 'Thành phố '], '', $province['name']);

            return stripos(mb_strtolower($provinceName), mb_strtolower($query)) === 0;
        })->values()->all();

        return response()->json($filtered);
    }



}
