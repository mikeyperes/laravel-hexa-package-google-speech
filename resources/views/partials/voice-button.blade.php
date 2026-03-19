{{--
    Reusable voice input button component.
    Include this partial wherever you need a mic button that transcribes speech.

    Required: pass the xterm terminal instance name as $terminalVar (default: 'term')
    Usage: @include('google-speech::partials.voice-button', ['terminalVar' => 'term'])

    The transcribed text will be typed into the terminal character by character.
--}}
@php $termVar = $terminalVar ?? 'term'; @endphp

<div x-data="voiceButton()" class="inline-flex">
    <button type="button"
        @mousedown="startListening()" @mouseup="stopListening()" @mouseleave="stopListening()"
        @touchstart.prevent="startListening()" @touchend.prevent="stopListening()"
        :class="listening ? 'bg-red-500 border-red-600 text-white' : 'bg-gray-100 border-gray-300 text-gray-600 hover:bg-gray-200'"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg border transition-colors select-none"
        :title="listening ? 'Release to send' : 'Hold to speak'">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
        </svg>
        <span x-text="listening ? 'Listening...' : 'Voice'"></span>
    </button>
</div>

@push('scripts')
<script>
function voiceButton() {
    return {
        listening: false,
        recognition: null,

        startListening() {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (!SpeechRecognition) {
                alert('Web Speech API not supported. Use Chrome or Edge.');
                return;
            }

            this.recognition = new SpeechRecognition();
            this.recognition.lang = document.documentElement.lang || 'en-US';
            this.recognition.interimResults = false;
            this.recognition.continuous = true;

            this.recognition.onresult = (event) => {
                let text = '';
                for (let i = event.resultIndex; i < event.results.length; i++) {
                    if (event.results[i].isFinal) {
                        text += event.results[i][0].transcript;
                    }
                }
                if (text && typeof {{ $termVar }} !== 'undefined') {
                    // Type text into the terminal
                    {{ $termVar }}.paste(text);
                }
            };

            this.recognition.onerror = (event) => {
                if (event.error !== 'aborted') {
                    console.error('Speech error:', event.error);
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
