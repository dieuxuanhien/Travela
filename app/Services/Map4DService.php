<?php

namespace App\Services;

use Illuminate\Support\Facades\Http; // Sử dụng Laravel HTTP Client
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Client\RequestException; // Để bắt lỗi HTTP

class Map4DService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        // Lấy key và url từ file config/services.php
        $this->apiKey = Config::get('services.map4d.key');
        $this->baseUrl = Config::get('services.map4d.base_url');

        if (!$this->apiKey) {
            // Có thể throw exception hoặc log lỗi nếu không có API key
            throw new \Exception('Map4D API Key not configured.');
        }
    }

    /**
     * Gọi API tìm đường (Route) của Map4D.
     *
     * @param string $origin Tọa độ điểm bắt đầu (vd: "21.0285,105.8522")
     * @param string $destination Tọa độ điểm kết thúc (vd: "21.0367,105.8347")
     * @param string $mode Phương tiện ('car', 'motorcycle', 'foot', 'bike')
     * @param array $options Các tham số tùy chọn khác (vd: language, points, avoid, ...)
     * @return array|null Dữ liệu JSON trả về từ API hoặc null nếu lỗi
     */
    public function getRoute(string $origin, string $destination, string $mode = 'car', array $options = []): ?array
    {
        // Xây dựng các tham số query
        $queryParams = array_merge([
            'key' => $this->apiKey,
            'origin' => $origin,
            'destination' => $destination,
            'mode' => $mode,
            'language' => 'vi', // Mặc định tiếng Việt
        ], $options); // Gộp các options tùy chọn vào

        try {
            $response = Http::baseUrl($this->baseUrl) // Đặt Base URL
            ->get('/route', $queryParams); // Gọi tới endpoint /route

            $response->throw(); // Ném exception nếu có lỗi HTTP (4xx, 5xx)

            return $response->json(); // Trả về dữ liệu JSON

        } catch (RequestException $e) {
            // Log lỗi hoặc xử lý lỗi cụ thể
            logger()->error('Map4D API Error (Route): ' . $e->getMessage(), [
                'url' => $this->baseUrl . '/route',
                'params' => $queryParams, // Không log API key ở production
                'status' => $e->response->status() ?? 'N/A',
                'response_body' => $e->response->body() ?? 'N/A'
            ]);
            return null; // Trả về null khi có lỗi
        } catch (\Exception $e) {
            logger()->error('Map4D Service Error (Route): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Gọi API Geocoding của Map4D (ví dụ chuyển địa chỉ thành tọa độ).
     *
     * @param string $address Địa chỉ cần tìm kiếm
     * @return array|null Dữ liệu JSON trả về từ API hoặc null nếu lỗi
     */
    public function geocode(string $address): ?array
    {
        $queryParams = [
            'key' => $this->apiKey,
            'address' => $address,
        ];

        try {
            $response = Http::baseUrl($this->baseUrl)
                ->get('/geocode', $queryParams);

            $response->throw();

            return $response->json();

        } catch (RequestException $e) {
            logger()->error('Map4D API Error (Geocode): ' . $e->getMessage(), [
                'url' => $this->baseUrl . '/geocode',
                'params' => ['address' => $address], // Chỉ log tham số không nhạy cảm
                'status' => $e->response->status() ?? 'N/A',
                'response_body' => $e->response->body() ?? 'N/A'
            ]);
            return null;
        } catch (\Exception $e) {
            logger()->error('Map4D Service Error (Geocode): ' . $e->getMessage());
            return null;
        }
    }

    // Thêm các phương thức khác cho các API khác của Map4D nếu cần
    // Ví dụ: reverseGeocode, placeSearch, ...
}
