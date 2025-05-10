<div class="p-6 xl:p-10 rounded-lg flex-1">
    <div class="flex items-center justify-between gap-5">
        <div class="size-9 rounded-full bg-blue-700 shadow-blue-500/10">
        </div>
        <div class="flex items-center justify-end gap-1">
            <form method="POST" action="{{ route('logout') }}" class="flex items-center justify-center">
                @csrf
                @method('DELETE')
                <button type="submit" href="{{ route('logout') }}"
                    class="inline-flex items-center gap-1 text-blue-500 hover:text-blue-400 font-semibold text-xs cursor-pointer">
                    Log out <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-door-open-icon lucide-door-open size-4">
                        <path d="M11 20H2" />
                        <path
                            d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z" />
                        <path d="M11 4H8a2 2 0 0 0-2 2v14" />
                        <path d="M14 12h.01" />
                        <path d="M22 20h-3" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
    <div class="flex flex-col gap-2 items-center justify-center mt-10 text-center">
        <h1 class="text-xl font-bold">Verify Your Email Address</h1>
        <p class="text-sm text-gray-400 mt-1">
            We have sent a verification code to <span
                class="font-medium text-blue-400">{{ Auth::user()->email }}</span>.
        </p>
        <p class="text-sm text-gray-400 mt-1">
            If you didn't receive the email, we will gladly send you another. <br> Remember to check your spam folder.
        </p>
    </div>
    <form wire:submit="verifyEmail">
        <div class="flex items-center justify-center gap-2 mt-6 mb-7" x-data="{
            codes: ['', '', '', '', '', ''],
        
            focusInput(index) {
                this.$nextTick(() => {
                    const el = this.$refs['codeInput' + index];
                    if (el) {
                        el.focus();
                        el.select();
                    }
                });
            },
        
            init() {
                this.focusInput(0);
            },
        
            handleInput(currentIndex, event) {
                const inputElement = event.target;
                let value = inputElement.value;
        
                if (value.length > 1) {
                    value = value.substring(0, 1);
                    inputElement.value = value;
                }
                this.codes[currentIndex] = value;
        
                if (value.length === 1 && currentIndex < this.codes.length - 1) {
                    this.focusInput(currentIndex + 1);
                }
            },
        
            handleKeyDown(currentIndex, event) {
                const inputElement = event.target;
                if (event.key === 'Backspace') {
                    event.preventDefault();
                    if (inputElement.value === '' && currentIndex > 0) {
                        this.codes[currentIndex - 1] = '';
                        const prevInputEl = this.$refs['codeInput' + (currentIndex - 1)];
                        if (prevInputEl) {
                            prevInputEl.value = '';
                            prevInputEl.dispatchEvent(new Event('input', { bubbles: true }));
                        }
                        this.focusInput(currentIndex - 1);
                    } else if (inputElement.value.length > 0) {
                        inputElement.value = '';
                        this.codes[currentIndex] = '';
                        inputElement.dispatchEvent(new Event('input', { bubbles: true }));
                        this.focusInput(currentIndex);
                    }
                } else if (event.key === 'ArrowLeft') {
                    if (currentIndex > 0) {
                        event.preventDefault();
                        this.focusInput(currentIndex - 1);
                    }
                } else if (event.key === 'ArrowRight') {
                    if (currentIndex < this.codes.length - 1) {
                        event.preventDefault();
                        this.focusInput(currentIndex + 1);
                    }
                }
            },
        
            handlePaste(currentIndex, event) {
                event.preventDefault();
                const pastedText = event.clipboardData.getData('text').trim();
        
                for (let i = 0; i < pastedText.length; i++) {
                    const targetInputIndex = currentIndex + i;
                    if (targetInputIndex < this.codes.length) {
                        const charToPaste = pastedText[i];
                        if (charToPaste.length === 1) {
                            this.codes[targetInputIndex] = charToPaste;
                            const inputEl = this.$refs['codeInput' + targetInputIndex];
                            if (inputEl) {
                                inputEl.value = charToPaste;
                                inputEl.dispatchEvent(new Event('input', { bubbles: true }));
                            }
                        }
                    } else {
                        break;
                    }
                }
        
                let nextFocusIdx = this.codes.findIndex(code => code === '');
                if (nextFocusIdx === -1) {
                    nextFocusIdx = this.codes.length - 1;
                }
                this.focusInput(nextFocusIdx);
            }
        }" x-init="init()">
            @for ($i = 0; $i < 6; $i++)
                <input type="text" wire:model.defer="code{{ $i }}" maxlength="1"
                    x-ref="codeInput{{ $i }}" x-on:input="handleInput({{ $i }}, $event)"
                    x-on:keydown="handleKeyDown({{ $i }}, $event)"
                    x-on:paste="handlePaste({{ $i }}, $event)" required
                    class="size-12 rounded-md bg-gray-900 text-gray-400 text-center focus:outline-none focus:ring-2 focus:ring-blue-500"
                    aria-label="Verification code digit {{ $i + 1 }}" />
            @endfor
        </div>

        <button type="submit" class="btn-primary px-4 py-2 flex justify-center w-11/12 sm:w-2/3 mx-auto">
            <span wire:loading.remove wire:target="verifyEmail">Verify Email</span>
            <x-spinner class="size-6" wire:loading wire:target="verifyEmail" />
        </button>
    </form>
    <form wire:submit="sendVerifyMail" class="w-11/12 sm:w-2/3 mx-auto mt-4">
        <button type="submit" class="btn-secondary px-4 py-2 w-full flex justify-center">
            <span wire:loading.remove wire:target="sendVerifyMail">Resend Code</span>
            <x-spinner class="size-6" wire:loading wire:target="sendVerifyMail" />
        </button>
    </form>

    <div class="flex items-center justify-between mt-10">
        <span class="text-xs text-gray-500">
            @ {{ date('Y') }} {{ config('app.name') }}
        </span>
        <a href="{{ route('home') }}" wire:navigate.hover
            class="inline-flex items-center text-xs text-gray-500 hover:text-blue-400 transition duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-arrow-left-icon lucide-arrow-left size-5 mr-0.5">
                <path d="m12 19-7-7 7-7" />
                <path d="M19 12H5" />
            </svg> Go back to home
        </a>
    </div>
</div>
