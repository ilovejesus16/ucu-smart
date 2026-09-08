@extends('layouts.admin')

@section('title', 'Campus Map Editor')

@section('content')
<div x-data="campusMapEditor()" class="max-w-[1600px] mx-auto">

    <div class="mb-6">
        <h1 class="text-3xl font-extrabold text-[#0E2958]">Campus Map Editor</h1>
        <p class="text-gray-500 mt-2">
            Arrange the 35 official campus locations from the site development plan.
        </p>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_350px] gap-6">

        <!-- MAP -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

            <div class="px-5 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="font-bold text-[#0E2958]">Map Workspace</h2>
                    <p class="text-xs text-gray-500 mt-1">
                        Drag the map to pan. Drag a location marker to reposition it.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" @click="zoomOut()" class="w-10 h-10 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 font-bold">−</button>
                    <div class="min-w-[65px] text-center text-sm font-semibold text-gray-600"
                         x-text="Math.round(zoom * 100) + '%'"></div>
                    <button type="button" @click="zoomIn()" class="w-10 h-10 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 font-bold">+</button>
                    <button type="button" @click="resetView()" class="px-4 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-sm font-semibold">
                        Reset View
                    </button>
                </div>
            </div>

            <div
                x-ref="viewport"
                @pointerdown="startPan($event)"
                @pointermove="panMap($event)"
                @pointerup="stopPan($event)"
                @pointercancel="stopPan($event)"
                class="relative w-full overflow-hidden bg-gray-100 select-none"
                :class="panning ? 'cursor-grabbing' : 'cursor-grab'"
                style="height: 700px;"
            >
                <div
                    x-ref="canvas"
                    class="absolute left-1/2 top-1/2 w-[900px] max-w-none"
                    :style="`
                        transform:
                            translate(calc(-50% + ${panX}px), calc(-50% + ${panY}px))
                            scale(${zoom});
                        transform-origin: center center;
                    `"
                >
                    <img
                        src="{{ asset('images/campus-map.png') }}"
                        alt="UCU Campus Map"
                        draggable="false"
                        class="block w-full h-auto pointer-events-none select-none"
                    >

                    <!-- PLACED MARKERS ONLY -->
                    @foreach($locations as $location)
                        @if($location->map_x !== null && $location->map_y !== null)
                            <div
                                data-campus-marker
                                x-data="campusMarker({{ $location->id }}, {{ $location->number }}, {{ $location->map_x }}, {{ $location->map_y }})"
                                class="absolute z-50"
                                :style="`left:${x}%;top:${y}%;transform:translate(-50%,-50%);`"
                                @pointerdown.stop="startMarkerDrag($event)"
                            >
                                <div class="w-9 h-9 rounded-full bg-[#0E4C6B] border-[3px] border-white shadow-lg
                                            flex items-center justify-center text-white font-extrabold text-xs
                                            cursor-grab active:cursor-grabbing hover:scale-110 transition"
                                     title="{{ $location->name }}">
                                    {{ $location->number }}
                                </div>

                                <div class="absolute left-1/2 top-full mt-1 -translate-x-1/2 whitespace-nowrap
                                            bg-white/95 border border-gray-200 shadow rounded-md px-2 py-1
                                            text-[9px] font-semibold text-[#0E2958] pointer-events-none">
                                    {{ $location->name }}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- DROP HINT -->
                <div
                    x-show="draggingNew"
                    class="absolute inset-0 z-[80] pointer-events-none flex items-center justify-center"
                >
                    <div class="bg-[#0E2958]/90 text-white px-5 py-3 rounded-xl shadow-xl font-semibold">
                        Release to place <span x-text="draggingName"></span>
                    </div>
                </div>
            </div>

            <div class="px-5 py-4 border-t border-gray-200 bg-gray-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <p class="text-sm text-gray-500">
                    <span class="font-bold text-[#0E2958]">{{ $locations->whereNotNull('map_x')->count() }}</span>
                    / {{ $locations->count() }} locations positioned.
                </p>

                <div class="flex gap-2">
                    <button type="button" @click="resetPositions()"
                        class="px-4 py-2 rounded-lg border border-red-200 bg-white text-red-600 hover:bg-red-50 text-sm font-semibold">
                        Reset Positions
                    </button>

                    <button type="button" @click="savePositions()" :disabled="saving"
                        class="px-5 py-2 rounded-lg bg-[#0E2958] hover:bg-[#0B2148] text-white text-sm font-semibold disabled:opacity-60">
                        <span x-show="!saving">Save All Positions</span>
                        <span x-show="saving">Saving...</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- LOCATION TRAY -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden h-fit">
            <div class="px-5 py-4 border-b border-gray-200">
                <h2 class="font-bold text-[#0E2958]">Campus Locations</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Drag an unpositioned location onto the map.
                </p>
            </div>

            <div class="max-h-[700px] overflow-y-auto p-3 space-y-2">

                @foreach($locations as $location)
                    <div
                        @if($location->map_x === null)
                            @pointerdown="startNewMarkerDrag($event, {{ $location->id }}, @js($location->name))"
                            class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 bg-white
                                   hover:bg-gray-50 cursor-grab active:cursor-grabbing touch-none select-none"
                        @else
                            class="flex items-center gap-3 p-3 rounded-xl border border-green-100 bg-green-50/50"
                        @endif
                    >
                        <div class="w-9 h-9 rounded-full bg-[#0E2958] text-white flex items-center justify-center font-bold text-xs shrink-0">
                            {{ $location->number }}
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-sm text-[#0E2958] leading-tight">
                                {{ $location->name }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ $location->type }}
                            </p>
                        </div>

                        @if($location->map_x === null)
                            <span class="text-[10px] font-bold text-gray-400 whitespace-nowrap">Drag</span>
                        @else
                            <span class="text-[10px] font-bold text-green-600 whitespace-nowrap">Placed</span>
                        @endif
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {

    Alpine.data('campusMapEditor', () => ({
        zoom: 1,
        panX: 0,
        panY: 0,
        panning: false,
        saving: false,

        draggingNew: false,
        draggingName: '',
        draggingNewId: null,

        startMouseX: 0,
        startMouseY: 0,
        startPanX: 0,
        startPanY: 0,

        zoomIn() {
            this.zoom = Math.min(this.zoom + 0.25, 2.5);
        },

        zoomOut() {
            this.zoom = Math.max(this.zoom - 0.25, 0.5);
        },

        resetView() {
            this.zoom = 1;
            this.panX = 0;
            this.panY = 0;
        },

        startPan(event) {
            if (this.draggingNew || event.button !== 0) return;

            this.panning = true;
            this.startMouseX = event.clientX;
            this.startMouseY = event.clientY;
            this.startPanX = this.panX;
            this.startPanY = this.panY;

            this.$refs.viewport.setPointerCapture(event.pointerId);
        },

        panMap(event) {
            if (this.draggingNew) {
                return;
            }

            if (!this.panning) return;

            this.panX = this.startPanX + (event.clientX - this.startMouseX);
            this.panY = this.startPanY + (event.clientY - this.startMouseY);
        },

        stopPan(event) {
            if (this.draggingNew) return;

            this.panning = false;

            try {
                this.$refs.viewport.releasePointerCapture(event.pointerId);
            } catch (error) {}
        },

        startNewMarkerDrag(event, id, name) {
            if (event.button !== 0) return;

            event.preventDefault();

            this.draggingNew = true;
            this.draggingNewId = id;
            this.draggingName = name;

            document.addEventListener('pointermove', this.moveNewMarker);
            document.addEventListener('pointerup', this.dropNewMarker, { once: true });
        },

        moveNewMarker(event) {
            if (!this.draggingNew) return;

            // The visual hint follows the interaction; actual coordinates are
            // calculated on drop against the transformed 900px canvas.
        },

        dropNewMarker(event) {
            if (!this.draggingNew) return;

            const viewport = this.$refs.viewport;
            const canvas = this.$refs.canvas;
            const rect = canvas.getBoundingClientRect();

            const inside =
                event.clientX >= rect.left &&
                event.clientX <= rect.right &&
                event.clientY >= rect.top &&
                event.clientY <= rect.bottom;

            if (inside) {
                const x = ((event.clientX - rect.left) / rect.width) * 100;
                const y = ((event.clientY - rect.top) / rect.height) * 100;

                const marker = {
                    id: this.draggingNewId,
                    x: Math.max(0, Math.min(100, x)),
                    y: Math.max(0, Math.min(100, y)),
                };

                // Save the newly placed marker immediately, then reload so
                // the marker becomes a normal draggable saved marker.
                this.saving = true;

                fetch(@js(route('admin.campus-map.positions')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        positions: [marker],
                    }),
                })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Placement save failed.');
                    }

                    window.location.reload();
                })
                .catch((error) => {
                    console.error(error);
                    alert('Unable to save this marker position.');
                })
                .finally(() => {
                    this.saving = false;
                });
            }

            this.draggingNew = false;
            this.draggingNewId = null;
            this.draggingName = '';

            document.removeEventListener('pointermove', this.moveNewMarker);
        },

        async savePositions() {
            this.saving = true;

            const positions = [];

            document.querySelectorAll('[data-campus-marker]').forEach((element) => {
                const data = Alpine.$data(element);

                positions.push({
                    id: data.id,
                    map_x: Number(data.x.toFixed(4)),
                    map_y: Number(data.y.toFixed(4)),
                });
            });

            try {
                const response = await fetch(@js(route('admin.campus-map.positions')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ positions }),
                });

                if (!response.ok) throw new Error('Save failed.');

                window.location.reload();
            } catch (error) {
                console.error(error);
                alert('Unable to save the marker positions.');
            } finally {
                this.saving = false;
            }
        },

        async resetPositions() {
            if (!confirm('Reset all 35 marker positions?')) return;

            try {
                const response = await fetch(@js(route('admin.campus-map.reset')), {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });

                if (!response.ok) throw new Error('Reset failed.');

                window.location.reload();
            } catch (error) {
                console.error(error);
                alert('Unable to reset marker positions.');
            }
        },
    }));

    Alpine.data('campusMarker', (id, number, initialX, initialY) => ({
        id,
        number,
        x: Number(initialX),
        y: Number(initialY),
        dragging: false,
        startMouseX: 0,
        startMouseY: 0,
        startX: 0,
        startY: 0,

        startMarkerDrag(event) {
            if (event.button !== 0) return;

            event.preventDefault();

            this.dragging = true;
            this.startMouseX = event.clientX;
            this.startMouseY = event.clientY;
            this.startX = this.x;
            this.startY = this.y;

            document.addEventListener('pointermove', this.moveMarker);
            document.addEventListener('pointerup', this.stopMarkerDrag, { once: true });
        },

        moveMarker(event) {
            if (!this.dragging) return;

            const canvas = this.$root.parentElement;
            const rect = canvas.getBoundingClientRect();

            this.x = Math.max(0, Math.min(100,
                this.startX + ((event.clientX - this.startMouseX) / rect.width) * 100
            ));

            this.y = Math.max(0, Math.min(100,
                this.startY + ((event.clientY - this.startMouseY) / rect.height) * 100
            ));
        },

        stopMarkerDrag() {
            this.dragging = false;
            document.removeEventListener('pointermove', this.moveMarker);
        },
    }));
});
</script>
@endsection
