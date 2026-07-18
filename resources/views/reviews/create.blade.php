@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-12">
    <!-- Kotak Form Utama -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 md:p-10">
        
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-slate-900 mb-2">Tulis Ulasan Anda</h2>
            <p class="text-slate-500">Bantu <strong>{{ $organizer->name }}</strong> dan calon pengunjung lain dengan membagikan pengalaman Anda.</p>
        </div>

        <!-- Alert Error (Jika user memaksa masuk tanpa punya tiket) -->
        @if(session('error'))
            <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        <!-- Form Pengisian -->
        <form action="{{ route('review.store', $organizer->id) }}" method="POST">
            @csrf

            <!-- 1. Dropdown Event (Hanya event yang tiketnya terbeli oleh user) -->
            <div class="mb-6">
                <label for="event_id" class="block text-sm font-semibold text-slate-700 mb-2">Pilih Event</label>
                <select name="event_id" id="event_id" required class="w-full bg-slate-50 rounded-xl border-slate-200 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-3 px-4 transition">
                    <option value="">-- Pilih event yang pernah Anda ikuti --</option>
                    @foreach($eligibleEvents as $event)
                        <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                            {{ $event->title }} ({{ \Carbon\Carbon::parse($event->date)->translatedFormat('d M Y') }})
                        </option>
                    @endforeach
                </select>
                @error('event_id')
                    <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- 2. Pilihan Rating Bintang (Dropdown untuk kepastian data) -->
            <div class="mb-6">
                <label for="rating" class="block text-sm font-semibold text-slate-700 mb-2">Penilaian Bintang</label>
                <select name="rating" id="rating" required class="w-full bg-slate-50 rounded-xl border-slate-200 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-3 px-4 transition">
                    <option value="">-- Berikan bintang (1-5) --</option>
                    <option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>5 - Sangat Memuaskan (★★★★★)</option>
                    <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>4 - Memuaskan (★★★★☆)</option>
                    <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>3 - Cukup (★★★☆☆)</option>
                    <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>2 - Buruk (★★☆☆☆)</option>
                    <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>1 - Sangat Buruk (★☆☆☆☆)</option>
                </select>
                @error('rating')
                    <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- 3. Kolom Komentar (Textarea) -->
            <div class="mb-10">
                <label for="comment" class="block text-sm font-semibold text-slate-700 mb-2">Komentar / Ulasan</label>
                <textarea name="comment" id="comment" rows="5" required placeholder="Ceritakan pengalaman Anda di event ini..." class="w-full bg-slate-50 rounded-xl border-slate-200 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-3 px-4 transition resize-none">{{ old('comment') }}</textarea>
                @error('comment')
                    <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Aksi -->
            <div class="flex gap-4">
                <a href="{{ route('organizers.show', $organizer->id) }}" class="w-1/3 text-center py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="w-2/3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl transition shadow-lg shadow-indigo-200">
                    Kirim Ulasan
                </button>
            </div>
            
        </form>
    </div>
</div>
@endsection