<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Http;
use App\Services\GeminiService;
class ChatbotController
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }
    public function sendMessage(Request $request)
    {
        $message = $request->input('message');

        // Gọi đến phương thức chatBot đã định nghĩa
        $reply = $this->geminiService->chatBot(['message' => $message]);

        return response()->json([
            'reply' => $reply
        ]);
    }
}
