<?php

namespace hexa_package_google_speech\Http\Controllers;

use hexa_core\Http\Controllers\Controller;
use hexa_core\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * GoogleSpeechSettingController -- manages Google Speech integration settings.
 */
class GoogleSpeechSettingController extends Controller
{
    /**
     * Display the Google Speech settings page.
     *
     * @return View
     */
    public function index(): View
    {
        $enabled = Setting::getValue('google_speech_enabled', '1') === '1';
        $language = Setting::getValue('google_speech_language', 'en-US');

        return view('google-speech::settings.index', compact('enabled', 'language'));
    }

    /**
     * Save Google Speech settings.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request): JsonResponse
    {
        if ($request->has('google_speech_enabled')) {
            Setting::setValue('google_speech_enabled', $request->boolean('google_speech_enabled') ? '1' : '0');
        }

        if ($request->filled('google_speech_language')) {
            Setting::setValue('google_speech_language', $request->input('google_speech_language'));
        }

        return response()->json(['success' => true, 'message' => 'Google Speech settings saved.']);
    }
}
