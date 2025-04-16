<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaGame - Buat Artikel</title>
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

        .create-btn,
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

        .back-btn:hover {
            background-color: #555555;
            color: #ffffff;
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
                <li><a href="{{ route('penulis.artikel.create') }}" class="active">Buat Artikel</a></li>
                <li><a href="/">Favorit</a></li>
                <li><a href="/">Komentar</a></li>
                <li><a href="{{ route('profile') }}">Pengaturan Akun</a></li>
            </ul>
        </div>

        <div class="dashboard-content">
            <div class="dashboard-header">
                <h2>Buat Artikel Baru</h2>
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

            <div class="form-container">
                <form action="{{ route('penulis.artikel.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_user" value="{{ $user->id }}">

                    <div class="form-group">
                        <label for="id_game">Game:</label>
                        <select class="form-control" id="id_game" name="id_game" required>
                            <option value="">Pilih Game...</option>
                            @foreach ($games as $game)
                                <option value="{{ $game->id_game }}"
                                    {{ old('id_game') == $game->id_game ? 'selected' : '' }}>
                                    {{ $game->nama_game }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="judul">Judul Artikel:</label>
                        <input type="text" class="form-control" name="judul" id="judul" required
                            value="{{ old('judul') }}">
                    </div>

                    <div class="form-group">
                        <label for="image">Gambar Utama:</label>
                        <input type="file" class="form-control-file" name="image" id="image">
                        <div class="media-preview" id="image-preview">
                            <p class="text-muted">Belum ada gambar yang dipilih.</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="konten">Konten Artikel:</label>
                        <textarea class="form-control" name="konten" id="konten" rows="6" required>{{ old('konten') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="tags">Tag:</label>
                        <div class="tag-grid">
                            @foreach ($tags as $tag)
                                <div class="tag-check">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id_tag }}"
                                        id="tag_{{ $tag->id_tag }}">
                                    <label for="tag_{{ $tag->id_tag }}">{{ $tag->nama_tag }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status">Status Artikel:</label>
                        <select class="form-control" name="status" id="status">
                            <option value="draft">Draft</option>
                            <option value="pending">Pending (Kirim untuk Ditinjau)</option>
                        </select>
                    </div>

                    <div id="sections-container"></div>

                    <div class="action-buttons">
                        <a href="{{ route('penulis.myarticles') }}" class="btn back-btn">Batal</a>
                        <div class="right-buttons">
                            <button type="button" id="add-section" class="btn add-section-btn">Tambah Bagian</button>
                            <button type="submit" class="btn submit-btn">Simpan Artikel</button>
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
        document.querySelector('.mobile-menu-toggle')?.addEventListener('click', function() {
            document.querySelector('.nav-links')?.classList.toggle('show');
        });

        document.getElementById('image')?.addEventListener('change', function(event) {
            const preview = document.getElementById('image-preview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML =
                        `<img src="${e.target.result}" class="img-fluid" style="max-width: 100%; max-height: 200px;">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = `<p class="text-muted">Belum ada gambar yang dipilih.</p>`;
            }
        });

        function addSection(index) {
            let container = document.getElementById("sections-container");
            let newSection = `
        <div class="section-item">
            <button type="button" class="remove-section">×</button>
            
            <div class="form-group">
                <label>Sub Judul:</label>
                <input type="text" class="form-control" name="sections[${index}][sub_judul]" required>
            </div>

            <div class="form-group">
                <label>Jenis Media:</label>
                <select class="form-control" name="sections[${index}][media_type]" onchange="toggleMediaInputs(this, ${index})">
                    <option value="">Pilih Jenis Media...</option>
                    <option value="image">Gambar</option>
                    <option value="embed">Embed</option>
                </select>
            </div>

            <div class="form-group" id="media-inputs-${index}" style="display: none;">
                <div id="file-input-container-${index}" style="display: none;">
                    <label>Gambar:</label>
                    <input type="file" class="form-control-file" name="sections[${index}][media_content_file]" id="media-file-${index}" onchange="previewImage(event, ${index})">
                </div>
                <div id="link-input-container-${index}" style="display: none;">
                    <label>Link Embed:</label>
                    <input type="text" class="form-control" name="sections[${index}][media_content]" id="media-link-${index}" placeholder="Masukkan link embed (YouTube, Twitter)" oninput="previewEmbed(${index})">
                </div>
            </div>

            <div class="media-preview" id="media-preview-${index}">
                <p class="text-muted">Belum ada media yang dipilih.</p>
            </div>

            <div class="form-group">
                <label>Deskripsi:</label>
                <textarea class="form-control" name="sections[${index}][deskripsi]" rows="3" required></textarea>
            </div>
        </div>
        `;
            container.insertAdjacentHTML("beforeend", newSection);
        }

        document.addEventListener("DOMContentLoaded", function() {
            let sectionIndex = 0;

            const addSectionButton = document.getElementById("add-section");
            if (addSectionButton) {
                addSectionButton.addEventListener("click", function() {
                    addSection(sectionIndex);
                    sectionIndex++;
                });
            }

            const sectionsContainer = document.getElementById("sections-container");
            if (sectionsContainer) {
                sectionsContainer.addEventListener("click", function(e) {
                    if (e.target.classList.contains("remove-section")) {
                        e.target.closest(".section-item").remove();
                    }
                });
            }
        });

        function toggleMediaInputs(selectElement, index) {
            const mediaType = selectElement.value;
            const mediaInputs = document.getElementById(`media-inputs-${index}`);
            const fileInputContainer = document.getElementById(`file-input-container-${index}`);
            const linkInputContainer = document.getElementById(`link-input-container-${index}`);
            const mediaPreview = document.getElementById(`media-preview-${index}`);

            if (!mediaInputs || !fileInputContainer || !linkInputContainer || !mediaPreview) {
                console.error("Error: Tidak dapat menemukan elemen yang diperlukan");
                return;
            }

            if (mediaType === "embed") {
                mediaInputs.style.display = "block";
                fileInputContainer.style.display = "none";
                linkInputContainer.style.display = "block";
                mediaPreview.innerHTML = `<p class="text-muted">Masukkan link embed di atas.</p>`;
            } else if (mediaType === "image") {
                mediaInputs.style.display = "block";
                fileInputContainer.style.display = "block";
                linkInputContainer.style.display = "none";
                mediaPreview.innerHTML = `<p class="text-muted">Pilih file gambar.</p>`;
            } else {
                mediaInputs.style.display = "none";
                fileInputContainer.style.display = "none";
                linkInputContainer.style.display = "none";
                mediaPreview.innerHTML = `<p class="text-muted">Belum ada media yang dipilih.</p>`;
            }
        }

        function previewImage(event, index) {
            const mediaPreview = document.getElementById(`media-preview-${index}`);
            if (!mediaPreview) {
                console.error("Error: Tidak dapat menemukan elemen preview");
                return;
            }

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
        }

        function previewEmbed(index) {
            const mediaPreview = document.getElementById(`media-preview-${index}`);
            const mediaLinkElement = document.getElementById(`media-link-${index}`);

            if (!mediaPreview || !mediaLinkElement) {
                console.error("Error: Tidak dapat menemukan elemen preview atau link");
                return;
            }

            const embedLink = mediaLinkElement.value;

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
                mediaPreview.innerHTML = `<p class="text-muted">Silakan masukkan link embed yang valid.</p>`;
            }
        }
    </script>
</body>

</html>
