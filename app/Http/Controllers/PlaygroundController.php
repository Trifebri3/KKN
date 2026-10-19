<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Faq;
use App\Models\QnaSubmission;
use App\Services\VirtualAssistantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlaygroundController extends Controller
{
    /**
     * Display the /playground page featuring interactive QnA and Virtual Assistant.
     */
    public function index(Request $request): View
    {
        $faqs = Faq::where('is_active', true)
            ->orderBy('urutan')
            ->orderByDesc('likes_count')
            ->get();

        $categories = Faq::where('is_active', true)
            ->distinct()
            ->pluck('kategori');

        // Session ID for chatbot conversation history
        $sessionId = $request->session()->getId();

        $chatHistory = ChatMessage::where('session_id', $sessionId)
            ->oldest()
            ->take(50)
            ->get();

        return view('playground.index', compact('faqs', 'categories', 'sessionId', 'chatHistory'));
    }

    /**
     * Send message to Virtual Assistant and get intelligent response.
     */
    public function chat(Request $request, VirtualAssistantService $assistant): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $sessionId = $request->input('session_id') ?: $request->session()->getId();
        $userMessage = $request->input('message');

        $response = $assistant->reply($userMessage, $sessionId);

        return response()->json([
            'status' => 'success',
            'data' => $response,
        ]);
    }

    /**
     * Upvote a FAQ item.
     */
    public function likeFaq(Request $request, int $id): JsonResponse
    {
        $faq = Faq::findOrFail($id);
        $faq->increment('likes_count');

        return response()->json([
            'status' => 'success',
            'likes_count' => $faq->likes_count,
        ]);
    }

    /**
     * Submit a user's question when not found in QnA.
     */
    public function askQuestion(Request $request): JsonResponse
    {
        $request->validate([
            'pertanyaan' => 'required|string|min:5|max:1000',
            'nama' => 'nullable|string|max:100',
            'email_or_phone' => 'nullable|string|max:100',
        ]);

        $submission = QnaSubmission::create([
            'nama' => $request->input('nama') ?: 'Anonim',
            'email_or_phone' => $request->input('email_or_phone'),
            'pertanyaan' => $request->input('pertanyaan'),
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pertanyaan Anda telah kami terima dan akan ditinjau oleh pengelola.',
            'data' => $submission,
        ]);
    }

    /**
     * Reset / Clear chat history for current session.
     */
    public function clearChat(Request $request): JsonResponse
    {
        $sessionId = $request->input('session_id') ?: $request->session()->getId();
        ChatMessage::where('session_id', $sessionId)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Riwayat percakapan berhasil dibersihkan.',
        ]);
    }
}
