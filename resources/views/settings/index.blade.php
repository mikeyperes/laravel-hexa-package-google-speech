@extends('layouts.app')
@section('title', 'Google Speech Settings')

@section('content')
<div class="max-w-4xl mx-auto" x-data="googleSpeechSettings()">


    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Google Speech Settings</h1>
        <p class="mt-1 text-sm text-gray-500">Browser-native voice-to-text using the Web Speech API. Free, no API key required.</p>
    </div>

    {{-- Setup Instructions --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
        <h3 class="text-sm font-semibold text-blue-900 mb-2">How It Works</h3>
        <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside">
            <li>Uses the browser's built-in SpeechRecognition API (Chrome/Edge)</li>
            <li>No API key needed -- transcription runs entirely in the browser</li>
            <li>Hold the mic button, speak, release -- text appears instantly</li>
            <li>Works best with English; other languages supported via dropdown</li>
        </ul>
        <p class="mt-2 text-xs text-blue-600">Requires Chrome or Edge. Firefox/Safari do not support the Web Speech API.</p>
    </div>

    {{-- Settings Card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Configuration</h2>
        </div>
        <div class="p-6 space-y-6">

            {{-- Enable/Disable --}}
            <div class="flex items-center justify-between">
                <div>
                    <label class="text-sm font-medium text-gray-700">Enable Voice Input</label>
                    <p class="text-xs text-gray-400">Show the mic button on terminal pages.</p>
                </div>
                <button type="button" @click="enabled = !enabled"
                    :class="enabled ? 'bg-purple-600' : 'bg-gray-300'"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors">
                    <span :class="enabled ? 'translate-x-6' : 'translate-x-1'"
                        class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                </button>
            </div>

            {{-- Language --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Transcription Language</label>
                <select x-model="language"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                    <option value="en-US">English (US)</option>
                    <option value="en-GB">English (UK)</option>
                    <option value="es-ES">Spanish</option>
                    <option value="fr-FR">French</option>
                    <option value="de-DE">German</option>
                    <option value="pt-BR">Portuguese (Brazil)</option>
                    <option value="it-IT">Italian</option>
                    <option value="ja-JP">Japanese</option>
                    <option value="ko-KR">Korean</option>
                    <option value="zh-CN">Chinese (Mandarin)</option>
                    <option value="ru-RU">Russian</option>
                    <option value="ar-SA">Arabic</option>
                    <option value="hi-IN">Hindi</option>
                </select>
            </div>

            {{-- Save --}}
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="button" @click="save()" :disabled="saving"
                    class="inline-flex items-center px-6 py-2.5 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 disabled:opacity-50 transition-colors">
                    <svg x-show="saving" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <span x-text="saving ? 'Saving...' : 'Save Settings'"></span>
                </button>
            </div>

            {{-- Result Banner --}}
            <template x-if="result.message">
                <div class="rounded-lg px-4 py-3 text-sm font-medium flex items-center gap-2"
                    :class="result.type === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800'">
                    <template x-if="result.type === 'success'">
                        <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </template>
                    <template x-if="result.type === 'error'">
                        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </template>
                    <span x-text="result.message"></span>
                </div>
            </template>

        </div>
    </div>

    {{-- Browser Test --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Test Microphone</h2>
            <p class="mt-1 text-sm text-gray-500">Hold the button and speak to test browser speech recognition.</p>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex items-center gap-4">
                <button type="button"
                    @mousedown="startListening()" @mouseup="stopListening()" @mouseleave="stopListening()"
                    @touchstart.prevent="startListening()" @touchend.prevent="stopListening()"
                    :class="listening ? 'bg-red-500 border-red-600 text-white' : 'bg-gray-100 border-gray-300 text-gray-700 hover:bg-gray-200'"
                    class="inline-flex items-center gap-2 px-5 py-3 border rounded-lg text-sm font-medium transition-colors select-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                    </svg>
                    <span x-text="listening ? 'Listening...' : 'Hold to Speak'"></span>
                </button>
                <span class="text-sm text-gray-500" x-show="!testText && !listening">Press and hold, then speak</span>
            </div>
            <template x-if="testText">
                <div class="bg-gray-900 rounded-lg px-4 py-3 text-sm font-mono text-green-400 break-words whitespace-pre-wrap" x-text="testText"></div>
            </template>
            <template x-if="speechError">
                <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-sm text-red-800" x-text="speechError"></div>
            </template>
        </div>
    </div>

</div>

@push('scripts')
<script>
function googleSpeechSettings() {
    return {
        enabled: @json($enabled),
        language: @json($language),
        saving: false,
        result: { type: '', message: '' },

        // Test
        listening: false,
        testText: '',
        speechError: '',
        recognition: null,

        async save() {
            this.saving = true;
            this.result = { type: '', message: '' };
            try {
                const resp = await fetch('{{ route("settings.google-speech.save") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                    body: JSON.stringify({ google_speech_enabled: this.enabled, google_speech_language: this.language }),
                });
                const data = await resp.json();
                this.result = { type: data.success ? 'success' : 'error', message: data.message };
            } catch (e) {
                this.result = { type: 'error', message: 'Network error: ' + e.message };
            }
            this.saving = false;
        },

        startListening() {
            this.speechError = '';
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (!SpeechRecognition) {
                this.speechError = 'Web Speech API not supported in this browser. Use Chrome or Edge.';
                return;
            }

            this.recognition = new SpeechRecognition();
            this.recognition.lang = this.language;
            this.recognition.interimResults = true;
            this.recognition.continuous = true;

            this.recognition.onresult = (event) => {
                let text = '';
                for (let i = 0; i < event.results.length; i++) {
                    text += event.results[i][0].transcript;
                }
                this.testText = text;
            };

            this.recognition.onerror = (event) => {
                if (event.error !== 'aborted') {
                    this.speechError = 'Speech recognition error: ' + event.error;
                }
                this.listening = false;
            };

            this.recognition.onend = () => {
                this.listening = false;
            };

            this.recognition.start();
            this.listening = true;
        },

        stopListening() {
            if (this.recognition && this.listening) {
                this.recognition.stop();
                this.listening = false;
            }
        },
    };
}
</script>
@endpush
@endsection
