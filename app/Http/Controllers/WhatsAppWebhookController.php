<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WhatsAppBotService;
use App\Services\WhatsAppNotificationService;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    protected $botService;
    protected $notificationService;

    public function __construct(WhatsAppBotService $botService, WhatsAppNotificationService $notificationService)
    {
        $this->botService = $botService;
        $this->notificationService = $notificationService;
    }

    public function handle(Request $request)
    {
        // Log incoming request for debugging
        Log::info('WhatsApp Webhook:', $request->all());

        try {
            $from = $request->input('From');
            $body = $request->input('Body');

            // Process message and get response
            $responseMessage = $this->botService->processMessage($from, $body);

            // Send response back via Twilio
            if ($responseMessage) {
                // Determine 'to' number (From webhook is sender, so we reply to them)
                // Twilio format: "whatsapp:+62812345"
                $this->notificationService->send($from, $responseMessage);
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('WhatsApp Webhook Error: ' . $e->getMessage());
            return response('Error', 500);
        }
    }
}
