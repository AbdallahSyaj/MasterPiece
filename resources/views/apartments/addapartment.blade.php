@extends('layouts.app')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --light-bg: #f8f9fa;
            --dark-text: #333;
            --light-text: #888;
            --danger: #e5383b;
            --warning: #ffb703;
            --success: #52b788;
            --border-radius: 10px;
            --box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        body {
            background-color: #f5f7fa;
            color: var(--dark-text);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .apartment-container {
            max-width: 1000px;
            margin: 2rem auto;
        }

        .page-title {
            font-weight: 700;
            color: var(--dark-text);
            font-size: 1.75rem;
            position: relative;
            margin-bottom: 2rem;
        }

        .page-title:after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 40px;
            height: 4px;
            background-color: var(--primary-color);
            border-radius: 2px;
        }

        .apartment-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--box-shadow);
            margin-bottom: 2rem;
        }

        .form-section {
            margin-bottom: 1rem;
        }

        .form-section-title {
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--primary-color);
            display: flex;
            align-items: center;
        }

        .form-section-title i {
            margin-right: 0.5rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark-text);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e1e5eb;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
            background-color: #fff;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .checkbox-group input {
            margin-right: 0.5rem;
        }

        .alert {
            padding: 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background-color: rgba(82, 183, 136, 0.1);
            border-left: 4px solid var(--success);
            color: var(--success);
        }

        .alert-danger {
            background-color: rgba(229, 56, 59, 0.1);
            border-left: 4px solid var(--danger);
            color: var(--danger);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn i {
            margin-right: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
        }

        .btn-secondary {
            background-color: white;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }

        .btn-secondary:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }

        .actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .image-preview-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .image-preview {
            width: 100%;
            height: 150px;
            border-radius: var(--border-radius);
            background-color: var(--light-bg);
            overflow: hidden;
            position: relative;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .file-upload {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.5rem;
            border: 2px dashed #e1e5eb;
            border-radius: var(--border-radius);
            background-color: var(--light-bg);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload:hover {
            border-color: var(--primary-color);
        }

        .file-upload i {
            font-size: 2rem;
            color: var(--light-text);
            margin-bottom: 0.5rem;
        }

        .file-upload input {
            display: none;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .actions {
                flex-direction: column;
                width: 100%;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
<div class="apartment-container">
    <h1 class="page-title">Add New Property</h1>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('apartments.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="apartment-card">
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fas fa-home"></i> Basic Information
                </h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="title" class="form-label">Property Title</label>
                        <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" name="city" value="{{ old('city') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="address" class="form-label">Full Address</label>
                        <textarea class="form-control" name="address" required>{{ old('address') }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="price_per_night" class="form-label">Price Per Night ($)</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="price_per_night" value="{{ old('price_per_night') }}" required>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fas fa-info-circle"></i> Property Details
                </h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="5" required>{{ old('description') }}</textarea>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="bedrooms" class="form-label">Bedrooms</label>
                        <input type="number" min="0" class="form-control" name="bedrooms" value="{{ old('bedrooms', 1) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="bathrooms" class="form-label">Bathrooms</label>
                        <input type="number" min="0" step="0.5" class="form-control" name="bathrooms" value="{{ old('bathrooms', 1) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="max_guests" class="form-label">Maximum Guests</label>
                        <input type="number" min="1" class="form-control" name="max_guests" value="{{ old('max_guests', 2) }}" required>
                    </div>
                </div>
                
                <h4 class="form-section-title">
                    <i class="fas fa-clipboard-list"></i> Amenities
                </h4>
                <div class="form-grid">
                    <div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="has_wifi" name="has_wifi" value="1" {{ old('has_wifi') ? 'checked' : '' }}>
                            <label for="has_wifi" class="form-label mb-0">WiFi</label>
                        </div>
                        
                        <div class="checkbox-group">
                            <input type="checkbox" id="has_parking" name="has_parking" value="1" {{ old('has_parking') ? 'checked' : '' }}>
                            <label for="has_parking" class="form-label mb-0">Parking</label>
                        </div>
                    </div>
                    
                    <div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="has_kitchen" name="has_kitchen" value="1" {{ old('has_kitchen') ? 'checked' : '' }}>
                            <label for="has_kitchen" class="form-label mb-0">Kitchen</label>
                        </div>
                        
                        <div class="checkbox-group">
                            <input type="checkbox" id="has_air_conditioning" name="has_air_conditioning" value="1" {{ old('has_air_conditioning') ? 'checked' : '' }}>
                            <label for="has_air_conditioning" class="form-label mb-0">Air Conditioning</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fas fa-image"></i> Property Images
                </h3>
                <div class="form-group">
                    <label class="file-upload">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Upload Images</span>
                        <input type="file" name="images[]" id="images" multiple accept="image/*" onchange="previewImages(this)">
                    </label>
                    <div class="image-preview-container" id="imagePreviewContainer"></div>
                </div>
            </div>
            
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fas fa-sliders-h"></i> Status
                </h3>
                <div class="form-group">
                    <label for="status" class="form-label">Property Status</label>
                    <select class="form-control" name="status" required>
                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                    </select>
                </div>
            </div>
            
            <div class="actions">
                <a href="{{ route('profile') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Add Property
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function previewImages(input) {
        const previewContainer = document.getElementById('imagePreviewContainer');
        previewContainer.innerHTML = '';
        
        if (input.files) {
            const filesAmount = input.files.length;
            
            for (let i = 0; i < filesAmount; i++) {
                const reader = new FileReader();
                
                reader.onload = function(event) {
                    const preview = document.createElement('div');
                    preview.classList.add('image-preview');
                    
                    const img = document.createElement('img');
                    img.src = event.target.result;
                    
                    preview.appendChild(img);
                    previewContainer.appendChild(preview);
                }
                
                reader.readAsDataURL(input.files[i]);
            }
        }
    }
</script>
@endsection