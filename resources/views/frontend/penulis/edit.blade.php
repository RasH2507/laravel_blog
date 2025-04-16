<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaGame - Edit Artikel</title>
    <link rel="stylesheet" href="{{ asset('frontend/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/my-articles.css') }}">
    <style>
        .form-container {
            background-color: #1f1f1f;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            padding: 25px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #e0e0e0;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            background-color: #2d2d2d;
            border: 1px solid #3d3d3d;
            border-radius: 4px;
            color: #e0e0e0;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #03dac6;
            outline: none;
        }

        .form-control-file {
            display: block;
            margin-top: 5px;
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        .tag-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .tag-check {
            display: flex;
            align-items: center;
            background-color: #2d2d2d;
            padding: 10px 15px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .tag-check:hover {
            background-color: #3d3d3d;
        }

        .tag-check input {
            margin-right: 8px;
        }

        .tag-check label {
            font-size: 14px;
            margin-bottom: 0;
            font-weight: 400;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .section-item {
            background-color: #2a2a2a;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 20px;
            position: relative;
        }

        .remove-section {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: #cf6679;
            color: #121212;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .media-preview {
            margin-top: 15px;
            padding: 15px;
            background-color: #252525;
            border-radius: 4px;
            text-align: center;
        }

        .alert {
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-weight: 500;
        }

        .alert-danger {
            background-color: rgba(207, 102, 121, 0.2);
            border: 1px solid #cf6679;
            color: #cf6679;
        }

        .alert-success {
            background-color: rgba(3, 218, 198, 0.2);
            border: 1px solid #03dac6;
            color: #03dac6;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #3d3d3d;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .update-btn,
        .submit-btn {
            background-color: #03dac6;
            color: #121212;
        }

        .add-section-btn {
            background-color: #bb86fc;
            color: #121212;
        }

        .back-btn {
            background-color: #3d3d3d;
            color: #e0e0e0;
        }

        .current-image {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #252525;
            border-radius: 4px;
            text-align: center;
        }

        .current-image img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            @include('frontend.nav')
        </div>
    </header>

    <div class="dashboard">
        <div class="sidebar-menu">
            <div class="user-profile">
                <img src="/api/placeholder/100/100" alt="Profil Pengguna" class="profile-img">
                <h3 class="user-name">{{ $user->name }}</h3>
                <span class="user-level">{{ ucfirst($user->role) }}</span>
                <a href="{{ route('profile') }}" class="btn">Edit Profil</a>
            </div>
            <ul class="menu-items">
                <li><a href="{{ route('penulis.dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('penulis.myarticles') }}">Artikel Saya</a></li>
                <li><a href="{{ route('penulis.artikel.create') }}">Buat Artikel</a></li>
                <li><a href="/">Favorit</a></li>
                <li><a href="/">Komentar</a></li>
                <li><a href="{{ route('profile') }}">Pengaturan Akun</a></li>
            </ul>
        </div>

        <div class="dashboard-content">
            <div class="dashboard-header">
                <h2>Edit Artikel: {{ $artikel->judul }}</h2>
                <a href="{{ route('penulis.myarticles') }}" class="btn back-btn">Kembali ke Artikel Saya</a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="form-container">
                <form action="{{ route('penulis.artikel.update', $artikel->id_artikel) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_user" value="{{ $user->id }}">

                    <div class="form-group">
                        <label for="id_game">Game:</label>
                        <select class="form-control" id="id_game" name="id_game" required>
                            <option value="">Pilih Game...</option>
                            @foreach ($games as $game)
                                <option value="{{ $game->id_game }}"
                                    {{ $artikel->id_game == $game->id_game ? 'selected' : '' }}>
                                    {{ $game->nama_game }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="judul">Judul Artikel:</label>
                        <input type="text" class="form-control" name="judul" id="judul" required
                            value="{{ $artikel->judul }}">
                    </div>

                    <div class="form-group">
                        <label for="image">Gambar Utama:</label>
                        @if ($artikel->image)
                            <div class="current-image">
                                <p><strong>Gambar Saat Ini:</strong></p>
                                <img src="{{ asset('storage/' . $artikel->image) }}" alt="{{ $artikel->judul }}">
                            </div>
                        @endif
                        <input type="file" class="form-control-file" name="image" id="image">
                        <div class="media-preview" id="image-preview">
                            <p class="text-muted">Unggah gambar baru atau tetap gunakan yang ada.</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="konten">Konten Artikel:</label>
                        <textarea class="form-control" name="konten" id="konten" rows="6" required>{{ $artikel->konten }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="tags">Tag:</label>
                        <div class="tag-grid">
                            @foreach ($tags as $tag)
                                <div class="tag-check">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id_tag }}"
                                        id="tag_{{ $tag->id_tag }}"
                                        {{ in_array($tag->id_tag, $selectedTags) ? 'checked' : '' }}>
                                    <label for="tag_{{ $tag->id_tag }}">{{ $tag->nama_tag }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status">Status Artikel:</label>
                        <select class="form-control" name="status" id="status">
                            <option value="draft" {{ $artikel->status == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="pending" {{ $artikel->status == 'pending' ? 'selected' : '' }}>Pending
                                (Ajukan untuk Ditinjau)</option>
                            @if ($artikel->status == 'confirmed' || $artikel->status == 'rejected')
                                <option value="{{ $artikel->status }}" selected>
                                    {{ $artikel->status == 'confirmed' ? 'Dikonfirmasi' : 'Ditolak' }}
                                </option>
                            @endif
                        </select>
                    </div>

                    <div id="sections-container">
                        @foreach ($artikelSections as $index => $section)
                            <div class="section-item" data-original-id="{{ $section->id_section }}">
                                <button type="button" class="remove-section">×</button>

                                <div class="form-group">
                                    <label>Sub Judul:</label>
                                    <input type="text" class="form-control"
                                        name="sections[{{ $index }}][sub_judul]"
                                        value="{{ $section->sub_judul }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Tipe Media:</label>
                                    <select class="form-control" name="sections[{{ $index }}][media_type]"
                                        onchange="toggleMediaInputs(this, {{ $index }})"
                                        id="media-type-{{ $index }}">
                                        <option value="">Pilih Tipe Media...</option>
                                        <option value="image"
                                            {{ $section->media_type == 'image' ? 'selected' : '' }}>Gambar</option>
                                        <option value="embed"
                                            {{ $section->media_type == 'embed' ? 'selected' : '' }}>Embed</option>
                                    </select>
                                </div>

                                <div class="form-group" id="media-inputs-{{ $index }}"
                                    style="display: {{ $section->media_type ? 'block' : 'none' }};">
                                    <div id="file-input-container-{{ $index }}"
                                        style="display: {{ $section->media_type == 'image' ? 'block' : 'none' }};">
                                        <label>Gambar:</label>
                                        @if ($section->media_type == 'image' && $section->media_content)
                                            <div class="current-image">
                                                <p><strong>Gambar Saat Ini:</strong></p>
                                                <img src="{{ asset('storage/' . $section->media_content) }}"
                                                    alt="{{ $section->sub_judul }}">
                                                <input type="hidden"
                                                    name="sections[{{ $index }}][original_media_content]"
                                                    value="{{ $section->media_content }}">
                                            </div>
                                        @endif
                                        <input type="file" class="form-control-file"
                                            name="sections[{{ $index }}][media_content_file]"
                                            id="media-file-{{ $index }}"
                                            onchange="previewImage(event, {{ $index }})">
                                    </div>
                                    <div id="link-input-container-{{ $index }}"
                                        style="display: {{ $section->media_type == 'embed' ? 'block' : 'none' }};">
                                        <label>Link Embed:</label>
                                        <input type="text" class="form-control"
                                            name="sections[{{ $index }}][media_content]"
                                            id="media-link-{{ $index }}"
                                            placeholder="Masukkan link embed (YouTube, Twitter)"
                                            value="{{ $section->media_type == 'embed' ? $section->media_content : '' }}"
                                            oninput="previewEmbed({{ $index }})">
                                    </div>
                                </div>

                                <div class="media-preview" id="media-preview-{{ $index }}">
                                    @if ($section->media_type == 'image' && $section->media_content)
                                        <img src="{{ asset('storage/' . $section->media_content) }}"
                                            class="img-fluid" style="max-width: 100%; max-height: 200px;">
                                    @elseif ($section->media_type == 'embed' && $section->media_content)
                                        @if (strpos($section->media_content, 'youtube.com') !== false || strpos($section->media_content, 'youtu.be') !== false)
                                            @php
                                                $embedUrl = $section->media_content;
                                                if (strpos($embedUrl, 'watch?v=') !== false) {
                                                    $embedUrl = str_replace('watch?v=', 'embed/', $embedUrl);
                                                } elseif (strpos($embedUrl, 'youtu.be/') !== false) {
                                                    $embedUrl = str_replace(
                                                        'youtu.be/',
                                                        'youtube.com/embed/',
                                                        $embedUrl,
                                                    );
                                                }
                                            @endphp
                                            <iframe width="100%" height="200" src="{{ $embedUrl }}"
                                                frameborder="0" allowfullscreen></iframe>
                                        @else
                                            <div class="embed-preview">{{ $section->media_content }}
                                                <br><small>(Pratinjau mungkin tidak tersedia)</small>
                                            </div>
                                        @endif
                                    @else
                                        <p class="text-muted">Belum ada media yang dipilih.</p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>Deskripsi:</label>
                                    <textarea class="form-control" name="sections[{{ $index }}][deskripsi]" rows="3" required>{{ $section->deskripsi }}</textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="action-buttons">
                        <a href="{{ route('penulis.myarticles') }}" class="btn back-btn">Batal</a>
                        <div class="right-buttons">
                            <button type="button" id="add-section" class="btn add-section-btn">Tambah
                                Bagian</button>
                            <button type="submit" class="btn update-btn">Perbarui Artikel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="copyright">
                <p>&copy; {{ date('Y') }} MetaGame. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let sectionIndex = {{ count($artikelSections) }};

            window.toggleMediaInputs = function(selectElement, index) {
                const mediaType = selectElement.value;
                const mediaInputs = document.getElementById(`media-inputs-${index}`);
                const fileInputContainer = document.getElementById(`file-input-container-${index}`);
                const linkInputContainer = document.getElementById(`link-input-container-${index}`);
                const mediaPreview = document.getElementById(`media-preview-${index}`);

                if (mediaType === "embed") {
                    mediaInputs.style.display = "block";
                    fileInputContainer.style.display = "none";
                    linkInputContainer.style.display = "block";

                    if (!mediaPreview.querySelector('iframe')) {
                        mediaPreview.innerHTML = `<p class="text-muted">Masukkan link embed di atas.</p>`;
                    }
                } else if (mediaType === "image") {
                    mediaInputs.style.display = "block";
                    fileInputContainer.style.display = "block";
                    linkInputContainer.style.display = "none";

                    if (!mediaPreview.querySelector('img')) {
                        mediaPreview.innerHTML = `<p class="text-muted">Pilih file gambar.</p>`;
                    }
                } else {
                    mediaInputs.style.display = "none";
                    fileInputContainer.style.display = "none";
                    linkInputContainer.style.display = "none";
                    mediaPreview.innerHTML = `<p class="text-muted">Belum ada media yang dipilih.</p>`;
                }
            };

            window.previewImage = function(event, index) {
                const mediaPreview = document.getElementById(`media-preview-${index}`);
                const file = event.target.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        mediaPreview.innerHTML =
                            `<img src="${e.target.result}" class="img-fluid" style="max-width: 100%; max-height: 200px;">`;
                    };
                    reader.readAsDataURL(file);
                } else {
                    mediaPreview.innerHTML = `<p class="text-muted">Tidak ada gambar yang dipilih.</p>`;
                }
            };

            window.previewEmbed = function(index) {
                const mediaPreview = document.getElementById(`media-preview-${index}`);
                const embedLink = document.getElementById(`media-link-${index}`).value;

                if (embedLink.includes("youtube.com") || embedLink.includes("youtu.be")) {
                    let embedUrl = embedLink;
                    if (embedLink.includes("watch?v=")) {
                        embedUrl = embedLink.replace("watch?v=", "embed/");
                    } else if (embedLink.includes("youtu.be/")) {
                        embedUrl = embedLink.replace("youtu.be/", "youtube.com/embed/");
                    }
                    mediaPreview.innerHTML =
                        `<iframe width="100%" height="200" src="${embedUrl}" frameborder="0" allowfullscreen></iframe>`;
                } else if (embedLink.trim() !== '') {
                    mediaPreview.innerHTML =
                        `<div class="embed-preview">${embedLink} <br><small>(Pratinjau mungkin tidak tersedia)</small></div>`;
                } else {
                    mediaPreview.innerHTML = `<p class="text-muted">Masukkan link embed yang valid.</p>`;
                }
            };

            function addSection() {
                let container = document.getElementById("sections-container");
                let newSection = `
<div class="section-item">
    <button type="button" class="remove-section">×</button>
    
    <div class="form-group">
        <label>Sub Judul:</label>
        <input type="text" class="form-control" name="sections[${sectionIndex}][sub_judul]" required>
    </div>

    <div class="form-group">
        <label>Tipe Media:</label>
        <select class="form-control" name="sections[${sectionIndex}][media_type]" onchange="toggleMediaInputs(this, ${sectionIndex})">
            <option value="">Pilih Tipe Media...</option>
            <option value="image">Gambar</option>
            <option value="embed">Embed</option>
        </select>
    </div>

    <div class="form-group" id="media-inputs-${sectionIndex}" style="display: none;">
        <div id="file-input-container-${sectionIndex}" style="display: none;">
            <label>Gambar:</label>
            <input type="file" class="form-control-file" name="sections[${sectionIndex}][media_content_file]" id="media-file-${sectionIndex}" onchange="previewImage(event, ${sectionIndex})">
        </div>
        <div id="link-input-container-${sectionIndex}" style="display: none;">
            <label>Link Embed:</label>
            <input type="text" class="form-control" name="sections[${sectionIndex}][media_content]" id="media-link-${sectionIndex}" placeholder="Masukkan link embed (YouTube, Twitter)" oninput="previewEmbed(${sectionIndex})">
        </div>
    </div>

    <div class="media-preview" id="media-preview-${sectionIndex}">
        <p class="text-muted">Belum ada media yang dipilih.</p>
    </div>

    <div class="form-group">
        <label>Deskripsi:</label>
        <textarea class="form-control" name="sections[${sectionIndex}][deskripsi]" rows="3" required></textarea>
    </div>
</div>
`;
                container.insertAdjacentHTML("beforeend", newSection);
                sectionIndex++;
            }

            document.getElementById("add-section").addEventListener("click", function() {
                addSection();
            });

            document.querySelectorAll(".remove-section").forEach(function(button) {
                button.addEventListener("click", function() {
                    this.closest(".section-item").remove();
                });
            });

            document.getElementById("sections-container").addEventListener("click", function(e) {
                if (e.target.classList.contains("remove-section")) {
                    e.target.closest(".section-item").remove();
                }
            });
        });
    </script>
</body>

</html>
