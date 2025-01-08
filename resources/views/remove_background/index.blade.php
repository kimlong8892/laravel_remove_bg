<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-700">

<div class="h-screen w-screen flex items-center justify-center">
    <div class="border bg-white rounded shadow-sm dark:bg-gray-600 w-full max-w-4xl">
        <div class="flex justify-center gap-4 p-4">
            <div>
                <img id="before" src="" alt="Before Image" class="border rounded">
            </div>
            <div>
                <img id="after" src="" alt="After Image" class="border rounded">
                <a  href="#"
                    id="after-download"
                    class="block w-full text-sm text-white bg-blue-600 hover:bg-blue-700 font-semibold py-2 px-4 rounded-lg mx-auto mt-4">
                    Download image
                </a>
            </div>
        </div>

        <div class="p-4">
            <div class="loading-container w-full flex"></div>
            <div class="photo-container grid grid-cols-2 gap-4 max-w-3xl mx-auto"></div>
            <input type="file" id="file"
                   accept="image/*"
                   class="block w-full text-sm text-slate-500 dark:text-slate-800
                  file:mr-4 file:py-2 file:px-4
                  file:rounded-full file:border-0
                  file:text-sm file:font-semibold
                  file:bg-violet-50 file:text-violet-700
                  hover:file:bg-violet-100
                  hover:file:cursor-pointer
                  mx-auto
                  bg-gray-200 p-3 dark:bg-gray-500
           "/>
            <button id="btn-remove-background"
                    type="button"
                    class="block w-full text-sm text-white bg-blue-600 hover:bg-blue-700 font-semibold py-2 px-4 rounded-lg mx-auto mt-4">
                Remove Background
            </button>
        </div>

    </div>
</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.0/dist/browser-image-compression.js"></script>
<script src="https://unpkg.com/axios@1.2.2/dist/axios.min.js"></script>
<script src="https://cdn.tailwindcss.com"></script>
<script src="{{ asset('lib/jquery.min.js') }}"></script>
<script src="{{ asset('lib/loadingoverlay.min.js') }}"></script>

<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#before, #after, #after-download').hide();


        $('#file').change(function () {
            const fileInputPreview = this.files;

            if (fileInputPreview && fileInputPreview.length > 0) {
                const file = fileInputPreview[0];
                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#before').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);

                $('#before').show();
            }
        });

        $('#btn-remove-background').click(function () {
            const fileInput = $('#file')[0];
            const formData = new FormData();

            if (fileInput.files.length === 0) {
                return;
            }

            formData.append('image', fileInput.files[0]);
            $.LoadingOverlay('show');

            $.ajax({
                url: '{{ route("remove_background.post") }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.hasOwnProperty('image_removed_background')) {
                        let imageName = response.image_removed_background.split('/');
                        imageName = imageName[imageName.length - 1];

                        $('#after').attr('src', response.image_removed_background);
                        $('#after-download').attr('href', response.image_removed_background);
                        $('#after-download').attr('download', imageName);
                        $('#after, #after-download').show();
                        $.LoadingOverlay('hide');
                    }
                },
                error: function (error) {
                    console.log(error);
                    $.LoadingOverlay('hide');
                },
            });
        });
    });
</script>
</html>
