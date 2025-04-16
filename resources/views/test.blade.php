<!DOCTYPE html>
<html>
<head>
    <title>Map4D Route</title>
    <style>
        #map { height: 500px; width: 100%; }
    </style>
    <script src="https://api.map4d.vn/sdk/web/map4d.js?key=320fdc09342c67c6879c20e64e1475c0"></script>
</head>
<body>
<h1>Đường đi từ {{ $origin }} đến {{ $destination }} ({{ $mode }})</h1>

<div id="map"></div>

<h2>Dữ liệu Route (JSON):</h2>
<pre>{{ json_encode($routeData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>

<script>
    // Kiểm tra xem map4d đã được định nghĩa chưa để tránh lỗi
    if (typeof map4d !== 'undefined') {
        try {
            // Khởi tạo bản đồ
            let map = new map4d.Map(document.getElementById("map"), {
                center: [{{ explode(',', $origin)[0] }}, {{ explode(',', $origin)[1] }}],
                zoom: 15
                // Thêm các tùy chọn khác nếu cần
            });

            // === Phần vẽ đường đi ===
            const encodedPolyline = @json($routeData['result']['routes'][0]['overview_polyline']['points'] ?? null); // Sử dụng null nếu không có

            if (encodedPolyline) {
                console.log("Polyline mã hóa:", encodedPolyline);

                // --- BẮT ĐẦU PHẦN CẦN CẬP NHẬT ---

                // 1. Giải mã chuỗi polyline
                // Thử sử dụng hàm decodePath của Map4D (tên hàm có thể khác, cần kiểm tra tài liệu Map4D)
                let decodedPath;
                try {
                    // Giả định hàm này tồn tại và hoạt động giống Google Maps SDK
                    decodedPath = map4d.geometry.encoding.decodePath(encodedPolyline);
                } catch (decodeError) {
                    console.error("Lỗi khi giải mã polyline:", decodeError);
                    alert("Không thể giải mã dữ liệu đường đi.");
                    decodedPath = null; // Đặt là null nếu lỗi
                }


                if (decodedPath && decodedPath.length > 0) {
                    // 2. Tạo đối tượng Polyline
                    let routePolyline = new map4d.Polyline({
                        path: decodedPath, // Mảng các tọa độ [[lat1, lng1], [lat2, lng2], ...]
                        strokeColor: "#007bff", // Màu xanh dương (có thể đổi)
                        strokeOpacity: 0.8,
                        strokeWeight: 6
                    });

                    // 3. Thêm Polyline vào bản đồ
                    routePolyline.setMap(map);

                    // 4. (Tùy chọn nhưng nên có) Tự động zoom và căn giữa để thấy toàn bộ đường đi
                    // Giả định Polyline có phương thức getBounds()
                    try {
                        const bounds = routePolyline.getBounds();
                        if (bounds) {
                            map.fitBounds(bounds);
                        }
                    } catch (boundsError) {
                        console.warn("Không thể tự động điều chỉnh khung nhìn:", boundsError);
                    }

                } else {
                    console.error("Không thể vẽ đường đi do dữ liệu polyline không hợp lệ sau khi giải mã.");
                }

                // --- KẾT THÚC PHẦN CẦN CẬP NHẬT ---

            } else {
                console.error("Không tìm thấy thông tin 'overview_polyline.points' trong dữ liệu trả về từ API Route.");
                alert("Không nhận được dữ liệu đường đi chi tiết từ API.");
            }

            // Thêm Marker cho điểm đầu và điểm cuối
            new map4d.Marker({
                position: [{{ explode(',', $origin)[0] }}, {{ explode(',', $origin)[1] }}],
                map: map,
                title: "Điểm bắt đầu"
            });
            new map4d.Marker({
                position: [{{ explode(',', $destination)[0] }}, {{ explode(',', $destination)[1] }}],
                map: map,
                title: "Điểm kết thúc"
            });

        } catch (error) {
            console.error("Lỗi khi khởi tạo hoặc thao tác với bản đồ Map4D:", error);
            alert("Đã xảy ra lỗi khi hiển thị bản đồ.");
        }
    } else {
        console.error("Thư viện Map4D chưa được tải thành công!");
        alert("Không thể tải thư viện bản đồ. Vui lòng kiểm tra lại thẻ script và API Key.");
    }
</script>
</body>
</html>
