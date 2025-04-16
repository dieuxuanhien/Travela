<?php

namespace App\Services;

use http\Env\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GeminiService
{

    protected $goMapsService;

    protected $apiKey;
    protected $apiUrl;

    public function __construct(MapAPIService $goMapsService)
    {
        $this->goMapsService = $goMapsService;
        $this->apiKey = env('GEMINI_API_KEY');
        $this->apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent"; // Cập nhật model
    }

    public function getEvent($data)
    {
        $address        = $data['address'];
        $startDate      = $data['start_date'];
        $endDate        = $data['end_date'];

        $dataString_event = $address . $startDate . $endDate;
        $cacheKey_event = md5($dataString_event);
        if (Cache::has($cacheKey_event)) {
            return Cache::get($cacheKey_event);
        }

        $prompt = "Hãy tìm kiếm các sự kiện và lễ hội diễn ra quanh khu vực sau: \"" . $address . "\".\n";
        $prompt .= "Khoảng thời gian quan tâm là từ ngày " . $startDate . " đến ngày " . $endDate . ", vui lòng bao gồm cả các sự kiện có thể diễn ra trong khoảng 5 đến 10 ngày sau ngày kết thúc.\n";
        $prompt .= "Kết quả trả về cần được cấu trúc dưới dạng JSON hợp lệ theo định dạng sau:\n\n";
        $prompt .= "```json\n";
        $prompt .= "[\n";
        $prompt .= "  {\n";
        $prompt .= "    \"name\": \"[Tên sự kiện hoặc lễ hội]\",\n";
        $prompt .= "    \"date\": \"[Ngày diễn ra (có thể là một ngày hoặc một khoảng thời gian)]\",\n";
        $prompt .= "    \"location\": \"[Địa điểm cụ thể]\",\n";
        $prompt .= "    \"description\": \"[Mô tả ngắn gọn về sự kiện hoặc lễ hội]\"\n";
        $prompt .= "  },\n";
        $prompt .= "  {\n";
        $prompt .= "    \"name\": \"[Tên sự kiện hoặc lễ hội khác]\",\n";
        $prompt .= "    \"date\": \"[Ngày diễn ra]\",\n";
        $prompt .= "    \"location\": \"[Địa điểm]\",\n";
        $prompt .= "    \"description\": \"[Mô tả]\"\n";
        $prompt .= "  }\n";
        $prompt .= "  // ... Thêm các sự kiện khác nếu có\n";
        $prompt .= "]\n";
        $prompt .= "```\n\n";
        $prompt .= "Hãy đảm bảo rằng kết quả trả về là một JSON hợp lệ và không có bất kỳ văn bản nào khác ngoài cấu trúc JSON này.";


        $response = Http::post($this->apiUrl . "?key=" . $this->apiKey, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ]);

        $activity = $response->json();
        $activity = $activity['candidates'][0]['content']['parts'][0]['text'];
        $activity = str_replace('\n', '', $activity);
        $activity = str_replace('`', '', $activity);
        $activity = str_replace('json', '', $activity);
//        dd($plan);
        $cleanedString = str_replace(chr(0xC2).chr(0xA0), ' ', $activity);
        $cleanedString = preg_replace('/,\s*([\}\]])/', '$1', $cleanedString);
        $cleanedString = trim($cleanedString);
        $activityAray = null;
        try {
            $activityAray = json_decode($cleanedString, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo "Lỗi khi phân tích chuỗi text sau khi làm sạch (vẫn chưa phải JSON hợp lệ): " . $e->getMessage() . "\n";
            echo "Mã lỗi JSON: " . json_last_error() . " - " . json_last_error_msg() . "\n";
            // In ra chuỗi đã làm sạch để kiểm tra lỗi còn sót lại
            echo "\n--- Chuỗi đã làm sạch (gây lỗi) --- \n";
            echo htmlspecialchars($cleanedString);
            echo "\n---------------------------------\n";
        }



//        dd($activityAray);
        Cache::put($cacheKey_event, $activityAray, now()->addMinutes(60));

        return $activityAray;
    }

    public function chatBot($data)
    {
        $userMessage = $data['message'];

        $prompt = "Bạn là một trợ lý AI chuyên tư vấn về du lịch. Hãy trả lời một cách hữu ích, ngắn gọn và đúng trọng tâm:\n\n" . $userMessage;

        try {
            $response = Http::post($this->apiUrl . "?key=" . $this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            $response->throw(); // Throw an exception if the response has an error status code

            $answer = $response->json('candidates.0.content.parts.0.text') ?? 'Xin lỗi, tôi không hiểu.';

            return $answer;

        } catch (\Illuminate\Http\Client\RequestException $e) {
            \Log::error("Lỗi khi gọi Gemini API: " . $e->getMessage());
            return "Xin lỗi, đã có lỗi xảy ra khi liên hệ với trợ lý AI.";
        }
    }
    public function generateItinerary($data)
    {
        $address        = $data['address'];
        $startDate      = $data['start_date'];
        $endDate        = $data['end_date'];
        $placeName      = $data['placeName'];
        $budget         = $data['budget'];
        $currencyCode   = $data['currency'];
        $interest       = $data['interest'];
        $adults         = $data['adults'];
        $children_1     = $data['children_1'];
        $children_2     = $data['children_2'];
        $transportation = $data['transportation'];

        $dataString = $address . $startDate . $endDate . $budget . $currencyCode . $interest . $adults . $children_1 . $children_2 . $transportation;
        $cacheKey = md5($dataString);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $prompt = "Hãy tạo một kế hoạch du lịch chi tiết cho chuyến đi tại $address, bắt đầu từ ngày $startDate đến ngày $endDate.
                    Thông tin chuyến đi:
                    - Điểm bắt đầu: $address
                    - Phương tiện di chuyển chính: $transportation
                    - Số lượng người: Người lớn: $adults, Trẻ em (2-10 tuổi): $children_2, Trẻ em (<2 tuổi): $children_1
                    - Các địa điểm mong muốn tham quan (có thể chọn lọc/bỏ bớt nếu thời gian không đủ): $placeName
                    - Sở thích: $interest
                    - Ngân sách: $budget $currencyCode

                    YÊU CẦU QUAN TRỌNG VỀ ĐỊNH DẠNG ĐẦU RA:
                    Hãy trả về kết quả dưới dạng MỘT ĐỐI TƯỢNG JSON HỢP LỆ DUY NHẤT. Không bao gồm bất kỳ văn bản giới thiệu, giải thích, không trả lời thiếu nhất quán (như tự tìm quán, tự tìm) tôi cần tên địa điểm cụ thể, markdown formatting (như **, *), hay ghi chú nào khác ngoài đối tượng JSON này.

                    CẤU TRÚC JSON MONG MUỐN NHƯ SAU:
                    - Đối tượng JSON gốc có các key là chuỗi đại diện cho từng ngày (ví dụ: 'Ngày 1', 'Ngày 2',...).
                    - Giá trị của mỗi key ngày là một đối tượng chứa các key là 'Sáng', 'Trưa', 'Chiều', 'Tối'.
                    - Giá trị của mỗi key buổi (Sáng, Trưa, Chiều, Tối) là MỘT MẢNG (JSON array) chứa các đối tượng đại diện cho từng hoạt động theo thứ tự thời gian.
                    - Mỗi đối tượng hoạt động trong mảng phải có 2 key chính:
                        1. 'type': Chuỗi cho biết loại hoạt động (ví dụ: 'Ăn sáng', 'Di chuyển', 'Địa điểm tham quan', 'Ăn trưa', 'Ăn tối', 'Chỗ ngủ').
                        2. 'details': Giá trị của key này phụ thuộc vào loại hoạt động:
                            - Đối với 'Ăn sáng', 'Ăn trưa', 'Ăn tối', 'Chỗ ngủ': Giá trị là một CHUỖI (JSON string) mô tả địa điểm/chi tiết (ví dụ: 'Phở khô Gia Lai (tự tìm quán)').
                            - Đối với 'Di chuyển': Giá trị là MỘT ĐỐI TƯỢNG (JSON object) chứa các key sau: 'Điểm đi' (chuỗi), 'Điểm đến' (chuỗi), 'Khoảng cách' (chuỗi, ví dụ: '2 km'), 'Thời gian' (chuỗi, ví dụ: '5 phút'), 'Phương tiện di chuyển' (chuỗi). Hãy cung cấp ước tính khoảng cách và thời gian cụ thể, không dùng các cụm từ chung chung như 'tùy thuộc vào...'.
                            - Đối với 'Địa điểm tham quan': Giá trị là MỘT ĐỐI TƯỢNG (JSON object) chứa các key sau: 'Tên địa điểm' (chuỗi) và 'Thời gian tham quan' (chuỗi, ví dụ: '8:00 -> 10:00').

                    Lập kế hoạch chi tiết cho từng buổi (Sáng, trưa, chiều, tối) bao gồm các hoạt động ăn uống, tham quan, nghỉ ngơi và ĐẦY ĐỦ các bước di chuyển giữa các địa điểm. Đảm bảo tính logic về thời gian và lộ trình.

                    NHẮC LẠI: Chỉ trả về đối tượng JSON hợp lệ. KHÔNG ĐƯA RA LƯU Ý HAY GỢI Ý GÌ THÊM.";

                    // Bây giờ bạn có thể gửi $prompt này đến AI API
                    // và xử lý kết quả trả về bằng json_decode($apiResponse, true) trong PHP

        $response = Http::post($this->apiUrl . "?key=" . $this->apiKey, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ]);

        $plan = $response->json();
        $plan = $plan['candidates'][0]['content']['parts'][0]['text'];
        $plan = str_replace('\n', '', $plan);
        $plan = str_replace('`', '', $plan);
        $plan = str_replace('json', '', $plan);
//        dd($plan);
        $cleanedString = str_replace(chr(0xC2).chr(0xA0), ' ', $plan);
        $cleanedString = preg_replace('/,\s*([\}\]])/', '$1', $cleanedString);
        $cleanedString = trim($cleanedString);
        $planArray = null; // Khởi tạo
        try {
            $planArray = json_decode($cleanedString, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo "Lỗi khi phân tích chuỗi text sau khi làm sạch (vẫn chưa phải JSON hợp lệ): " . $e->getMessage() . "\n";
            echo "Mã lỗi JSON: " . json_last_error() . " - " . json_last_error_msg() . "\n";
            // In ra chuỗi đã làm sạch để kiểm tra lỗi còn sót lại
            echo "\n--- Chuỗi đã làm sạch (gây lỗi) --- \n";
            echo htmlspecialchars($cleanedString);
            echo "\n---------------------------------\n";
        }

        Cache::put($cacheKey, $planArray, now()->addMinutes(60));

        return $planArray;
    }
    public function getTourismInfo($address, $interests = [])
    {
        $interests_string = implode(', ', $interests);
        $dataString_getPlaces = $address . $interests_string;
        $cacheKey_getPlaces = md5($dataString_getPlaces);
        if (Cache::has($cacheKey_getPlaces)) {
            return Cache::get($cacheKey_getPlaces);
        }
        $prompt = "Hãy cung cấp thông tin về các địa điểm du lịch nổi tiếng nhất của khu vực $address.";

        if (!empty($interests)) {
            $prompt .= " Lọc theo sở thích: " . $interests_string . ".";
        }

        $prompt .= " Bao gồm:
        - Tên các địa điểm du lịch ( các địa điểm cụ thể đúng tên có thể tìm kiếm trên google map )
        - Loại địa điểm (ví dụ: di tích lịch sử, công viên, bảo tàng)
        - giờ hoạt động (giờ mở cửa và giờ đóng cửa tôi chỉ muốn con số cụ thể nếu là cả 1 khu du lịch thì giờ của cả khu thay vì quán không mở ngoặc mô tả gì thêm). Không lưu ý và khuyên gì thêm, chỉ các địa điểm, càng nhiều càng tốt
        - Các thẻ (khóa là 'Tag') liên quan (các tên tác cách nhau bằng ', ')
        - Kiểu cấu trúc sẽ như thế này
        1.  **Tên:** Tên địa điểm
            *   **Loại:** Loại địa điểm
            *   **Giờ hoạt động:** giờ:phút - giờ:phút
            *   **Tag:** tag1, tag2, ...
        - Không cần mở bài kết bài, tôi chỉ cần như mẫu trên";

        do {
            $response = Http::post($this->apiUrl . "?key=" . $this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            $places = $response->json();
        } while (!isset($places['candidates']));

        $places = $places['candidates'][0]['content']['parts'][0]['text'];


        $places = str_replace('*', '', $places);

//        dd($places);

        $lines = explode("\n", $places);
        $result = [];
        $skip = false;
        foreach ($lines as $line => $value) {
            $value = trim($value);
            if ($value == '"Lưu ý"' || $value == '"Lời khuyên"') {
                $skip = true;
                continue;
            }

            if ($skip) {
                continue;
            }
            if (preg_match("/^\d+\.\s/", $value)) {
                $value = preg_replace('/^\d+\.\s*/', '', $value);
                $result[$value] = [];
            }
            if ($value != ""){
                $lastKey = array_key_last($result);

                if (strpos($value, ":") !== false) {
                    list($key, $val) = explode(":", $value, 2);
                    $result[$lastKey][trim($key)] = trim($val);
                } else {
                    $result[$lastKey][] = $value;
                }
            }
        }

        $result = array_filter($result, fn($key) => !empty($key), ARRAY_FILTER_USE_KEY);
        foreach ($result as $key => $value) {
            if (isset($value["Tag"])) {
                $tags = explode(", ", $value["Tag"]);
                $result[$key]["Tag"] = $tags;
            }
        }
        Cache::put($cacheKey_getPlaces, $result, now()->addMinutes(60));
        return $result;
    }
}
