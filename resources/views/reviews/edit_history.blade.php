<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Ulasan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #7f00ff, #7c43bd);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card-form-ulasan {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            max-width: 600px;
            width: 90%;
        }
        .star-rating .star {
            cursor: pointer;
            color: gray;
            font-size: 2rem;
            transition: color 0.2s;
        }
        .star-rating .star:hover,
        .star-rating .star.selected {
            color: gold;
        }
    </style>
</head>
<body>

<div class="card card-form-ulasan">
    <h3 class="mb-4 text-center">Edit Ulasan Anda</h3> 

    <form action="{{ route('reviews.update', $review->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- Penting untuk metode UPDATE --}}

        <div class="mb-3">
            <label for="rating" class="form-label">Rating Bintang:</label>
            <div id="star-rating" class="star-rating">
                @for ($i = 1; $i <= 5; $i++)
                    <span class="star" data-value="{{ $i }}" style="color: {{ $i <= $review->rating ? 'gold' : 'gray' }};">&#9733;</span>
                @endfor
                <input type="hidden" name="rating" id="rating" value="{{ old('rating', $review->rating) }}" required>
            </div>
            @error('rating')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="comment" class="form-label">Ulasan Anda:</label>
            <textarea class="form-control" name="comment" id="comment" rows="5" placeholder="Tulis ulasan Anda...">{{ old('comment', $review->comment) }}</textarea>
            @error('comment')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Ganti Gambar (Opsional):</label>
            @if ($review->image_path)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $review->image_path) }}" alt="Gambar Ulasan Lama" style="max-width: 150px; height: auto;">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="clear_image" id="clear_image">
                        <label class="form-check-label" for="clear_image">Hapus gambar ini</label>
                    </div>
                </div>
            @endif
            <input type="file" class="form-control" name="image" id="image" accept="image/*">
            @error('image')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('reviews.show_product', $review->menu->id) }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const starRatingContainer = document.getElementById('star-rating');
        const stars = starRatingContainer.querySelectorAll('.star');
        const hiddenInput = document.getElementById('rating');

        function updateStars(value) {
            stars.forEach(s => {
                if (parseInt(s.dataset.value) <= value) {
                    s.style.color = 'gold';
                } else {
                    s.style.color = 'gray';
                }
            });
        }

        // Set initial state
        updateStars(parseInt(hiddenInput.value));

        stars.forEach(function(star) {
            star.addEventListener('click', function() {
                const value = parseInt(this.dataset.value);
                hiddenInput.value = value;
                updateStars(value);
            });

            star.addEventListener('mouseover', function() {
                const value = parseInt(this.dataset.value);
                stars.forEach(s => {
                    if (parseInt(s.dataset.value) <= value) {
                        s.style.color = 'orange'; // Warna saat hover
                    } else {
                        s.style.color = 'gray';
                    }
                });
            });

            star.addEventListener('mouseout', function() {
                updateStars(parseInt(hiddenInput.value));
            });
        });
    });
</script>
</body>
</html>