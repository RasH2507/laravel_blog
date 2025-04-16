@extends('dashboard.app')

@section('title', 'Tambah Artikel')

@section('content')
    <a href="{{ route('artikel.index') }}" class="btn btn-dark mb-3">Kembali</a>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('artikel.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="id_user">User:</label>
                    <select class="form-control" id="id_user" name="id_user" required>
                        <option value="">Pilih User...</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('id_user') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_game">Game:</label>
                    <select class="form-control" id="id_game" name="id_game" required>
                        <option value="">Pilih Game...</option>
                        @foreach ($game as $games)
                            <option value="{{ $games->id_game }}" {{ old('id_game') == $games->id_game ? 'selected' : '' }}>
                                {{ $games->nama_game }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="judul">Judul Artikel:</label>
                    <input type="text" class="form-control" name="judul" required value="{{ old('judul') }}">
                </div>

                <div class="form-group">
                    <label for="image">Gambar Utama:</label>
                    <input type="file" class="form-control-file" name="image">
                </div>

                <div class="form-group">
                    <label for="konten">Konten Artikel:</label>
                    <textarea class="form-control" name="konten" rows="5" required>{{ old('konten') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="tags">Tag:</label>
                    <div class="d-flex flex-wrap">
                        @foreach ($tags as $tag)
                            <div class="form-check mr-3">
                                <input class="form-check-input" type="checkbox" name="tags[]" value="{{ $tag->id_tag }}" id="tag_{{ $tag->id_tag }}">
                                <label class="form-check-label" for="tag_{{ $tag->id_tag }}">
                                    {{ $tag->nama_tag }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>                  

                <div class="form-group">
                    <label for="status">Status Artikel:</label>
                    <select class="form-control" name="status">
                        <option value="draft">Draft</option>
                        <option value="pending">Pending (Kirim ke Admin)</option>
                    </select>
                </div>

                <h4>Sections</h4>
                <div id="sections-container"></div>
                <button type="button" id="add-section" class="btn btn-primary">Tambah Section</button>

                <button type="submit" class="btn btn-dark">Simpan Artikel</button>
            </form>
        </div>
    </div>


    <script>
       document.addEventListener("DOMContentLoaded", function() {
    let sectionIndex = 0;

    function addSection() {
        let container = document.getElementById("sections-container");
        let newSection = `
        <div class="section-item">
            <div class="form-group">
                <label>Sub Judul:</label>
                <input type="text" class="form-control" name="sections[${sectionIndex}][sub_judul]" required>
            </div>

            <div class="form-group">
                <label>Media Type:</label>
                <select class="form-control" name="sections[${sectionIndex}][media_type]" onchange="toggleMediaInputs(this, ${sectionIndex})">
                    <option value="">Pilih Media...</option>
                    <option value="image">Image</option>
                    <option value="embed">Embed</option>
                </select>
            </div>

            <div class="form-group" id="media-inputs-${sectionIndex}" style="display: none;">
                <input type="file" class="form-control-file d-none" name="sections[${sectionIndex}][media_content_file]" id="media-file-${sectionIndex}" onchange="previewImage(event, ${sectionIndex})">
                <input type="text" class="form-control d-none" name="sections[${sectionIndex}][media_content]" id="media-link-${sectionIndex}" placeholder="Masukkan link embed (YouTube, Twitter)" oninput="previewEmbed(${sectionIndex})">
            </div>

            <div class="form-group media-preview" id="media-preview-${sectionIndex}">
                <p class="text-muted">Belum ada media.</p>
            </div>

            <div class="form-group">
                <label>Deskripsi:</label>
                <textarea class="form-control" name="sections[${sectionIndex}][deskripsi]" rows="3" required></textarea>
            </div>

            <button type="button" class="btn btn-danger remove-section">Hapus Section</button>
            <hr>
        </div>
        `;
        container.insertAdjacentHTML("beforeend", newSection);
        sectionIndex++;
    }

    document.getElementById("add-section").addEventListener("click", function() {
        addSection();
    });

    document.getElementById("sections-container").addEventListener("click", function(e) {
        if (e.target.classList.contains("remove-section")) {
            e.target.closest(".section-item").remove();
        }
    });
});

function toggleMediaInputs(selectElement, index) {
    const mediaType = selectElement.value;
    const mediaInputs = document.getElementById(`media-inputs-${index}`);
    const mediaLinkInput = document.getElementById(`media-link-${index}`);
    const fileInput = document.getElementById(`media-file-${index}`);
    const mediaPreview = document.getElementById(`media-preview-${index}`);

    if (mediaType === "embed") {
        mediaInputs.style.display = "block";
        mediaLinkInput.classList.remove("d-none");
        fileInput.classList.add("d-none");
    } else if (mediaType === "image") {
        mediaInputs.style.display = "block";
        mediaLinkInput.classList.add("d-none");
        fileInput.classList.remove("d-none");
    } else {
        mediaInputs.style.display = "none";
        mediaPreview.innerHTML = `<p class="text-muted">Belum ada media.</p>`;
    }
}

function previewImage(event, index) {
    const mediaPreview = document.getElementById(`media-preview-${index}`);
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            mediaPreview.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded" width="200">`;
        };
        reader.readAsDataURL(file);
    }
}

function previewEmbed(index) {
    const mediaPreview = document.getElementById(`media-preview-${index}`);
    const embedLink = document.getElementById(`media-link-${index}`).value;

    if (embedLink.includes("youtube.com") || embedLink.includes("youtu.be")) {
        mediaPreview.innerHTML = `<iframe width="300" height="200" src="${embedLink.replace("watch?v=", "embed/")}" frameborder="0" allowfullscreen></iframe>`;
    } else {
        mediaPreview.innerHTML = `<p class="text-muted">Masukkan link embed yang valid.</p>`;
    }
}

    </script>
@endsection
