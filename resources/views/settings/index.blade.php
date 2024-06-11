<x-app-layout>
    <link rel="stylesheet" href="{{ asset('cropperjs-1.5.13/dist/cropper.min.css') }}">

    <div class="flex flex-col gap-4">
        <h1 class="text-2xl font-bold">Pengaturan</h1>
        <form action="#" class="flex flex-col gap-3">
            <div class="flex flex-col gap-2">
                <label class="font-bold" for="">Foto Profil</label>
                <div class="custom-file mb-2">
                    <input type="file" class="custom-file-input" id="image" accept="image/*" name="image"
                        onchange="previewImg(this)" required>
                </div>
                <b class="d-block mb-1">Preview</b>
                <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('images/profile.png') }}"
                    alt="preview" id="preview" class="w-100 d-none mt-3" style="height: 15rem;object-fit: contain;">
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="update_image" id="update_image" class="form-checkbox">
                <label for="update_image">Perbarui foto profil?</label>
            </div>
            <div class="flex flex-col gap-2">
                <label class="font-bold" for="email">Email*</label>
                <input type="text" name="email" id="email" class="form-input rounded-md px-3 py-2"
                    value="{{ Auth::user()->email }}" required>
                @error('email')
                    <small class="text-red-500">{{ $message }}</small>
                @enderror
            </div>
            <div class="flex flex-col gap-2">
                <label class="font-bold" for="password">Password</label>
                <input type="password" name="password" id="password" class="form-input rounded-md px-3 py-2">
                <small>*Isi password jika ingin menggantinya, kosongkan jika tidak ingin menggantinya</small>
                @error('password')
                    <small class="text-red-500">{{ $message }}</small>
                @enderror
            </div>
            <div>
                <button type="submit" id="saveSetting"
                    class="btn bg-blue-500 text-white hover:bg-blue-600">Save</button>
            </div>
        </form>
    </div>

    <script src="{{ asset('adminlte-3.2.0/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('cropperjs-1.5.13/dist/cropper.min.js') }}"></script>
    <script>
        // cropper
        const uploadBtn = document.getElementById('saveSetting');
        let cropper = new Cropper(document.getElementById('preview'), {
            aspectRatio: 1,
            viewMode: 2
        })

        function previewImg(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();

                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result
                    document.getElementById('preview').classList.remove('d-none')
                    cropper.replace(e.target.result)
                }

                reader.readAsDataURL(input.files[0])
            }
        }

        function uploadCropped() {
            cropper.getCroppedCanvas().toBlob((blob) => {
                const formData = new FormData();

                formData.append('image', blob);
                formData.append('update_image', document.querySelector('#update_image').checked ? 1 : 0);
                formData.append('email', document.querySelector('#email').value);
                formData.append('password', document.querySelector('#password').value);
                formData.append('_method', 'PATCH');

                $.ajax('{{ route('settings.update') }}', {
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success(e) {
                        // swal({
                        //     title: "Uploaded !",
                        //     icon: "success",
                        //     text: null
                        // })
                        alert(`Sukses ${e.message}`);
                        location.reload();
                        // setTimeout(() => {
                        // }, 1000);
                    },
                    error(e) {
                        console.log(e);
                        // swal({
                        //     title: "Uploade failed !",
                        //     icon: "error",
                        //     text: null
                        // })
                        alert(`Upload gagal: ${e.responseJSON.message}`);
                    },
                });
            });
        }

        uploadBtn.addEventListener('click', (e) => {
            e.preventDefault()
            // uploadBtn.disabled = true
            uploadCropped()
        })
    </script>
</x-app-layout>
