import './bootstrap';
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import '../../vendor/masmerise/livewire-toaster/resources/js';
import focus from "@alpinejs/focus";

Alpine.plugin(focus);

Livewire.start()