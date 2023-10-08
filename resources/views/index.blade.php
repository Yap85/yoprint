<!doctype html>
<html>
<head>
    <title>CSV Upload</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</head>
<body>
    <style>
        .container{
            height: 100%;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .file-drop-area {
            position: relative;
            display: flex;
            align-items: center;
            width: 350px;
            max-width: 100%;
            padding: 25px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0,0,0,.1);
            transition: .3s;
        }
        .file-drop-area.is-active {
            background-color: #1a1a1a;
        }

        .fake-btn {
            flex-shrink: 0;
            background-color: #9699b3;
            border-radius: 3px;
            padding: 8px 15px;
            margin-right: 10px;
            font-size: 12px;
            text-transform: uppercase;
        }

        .file-msg {
            color: #9699b3;
            font-size: 16px;
            font-weight: 300;
            line-height: 1.4;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .item-delete {
            display: none;
            position: absolute;
            right: 10px;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .item-delete:before {
            content: "";
            position: absolute;
            left: 0;
            transition: .3s;
            top: 0;
            z-index: 1;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg fill='%23bac1cb' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 438.5 438.5'%3e%3cpath d='M417.7 75.7A8.9 8.9 0 00411 73H323l-20-47.7c-2.8-7-8-13-15.4-18S272.5 0 264.9 0h-91.3C166 0 158.5 2.5 151 7.4c-7.4 5-12.5 11-15.4 18l-20 47.7H27.4a9 9 0 00-6.6 2.6 9 9 0 00-2.5 6.5v18.3c0 2.7.8 4.8 2.5 6.6a8.9 8.9 0 006.6 2.5h27.4v271.8c0 15.8 4.5 29.3 13.4 40.4a40.2 40.2 0 0032.3 16.7H338c12.6 0 23.4-5.7 32.3-17.2a64.8 64.8 0 0013.4-41V109.6h27.4c2.7 0 4.9-.8 6.6-2.5a8.9 8.9 0 002.6-6.6V82.2a9 9 0 00-2.6-6.5zm-248.4-36a8 8 0 014.9-3.2h90.5a8 8 0 014.8 3.2L283.2 73H155.3l14-33.4zm177.9 340.6a32.4 32.4 0 01-6.2 19.3c-1.4 1.6-2.4 2.4-3 2.4H100.5c-.6 0-1.6-.8-3-2.4a32.5 32.5 0 01-6.1-19.3V109.6h255.8v270.7z'/%3e%3cpath d='M137 347.2h18.3c2.7 0 4.9-.9 6.6-2.6a9 9 0 002.5-6.6V173.6a9 9 0 00-2.5-6.6 8.9 8.9 0 00-6.6-2.6H137c-2.6 0-4.8.9-6.5 2.6a8.9 8.9 0 00-2.6 6.6V338c0 2.7.9 4.9 2.6 6.6a8.9 8.9 0 006.5 2.6zM210.1 347.2h18.3a8.9 8.9 0 009.1-9.1V173.5c0-2.7-.8-4.9-2.5-6.6a8.9 8.9 0 00-6.6-2.6h-18.3a8.9 8.9 0 00-9.1 9.1V338a8.9 8.9 0 009.1 9.1zM283.2 347.2h18.3c2.7 0 4.8-.9 6.6-2.6a8.9 8.9 0 002.5-6.6V173.6c0-2.7-.8-4.9-2.5-6.6a8.9 8.9 0 00-6.6-2.6h-18.3a9 9 0 00-6.6 2.6 8.9 8.9 0 00-2.5 6.6V338a9 9 0 002.5 6.6 9 9 0 006.6 2.6z'/%3e%3c/svg%3e");
        }

        .item-delete:after {
            content: "";
            position: absolute;
            opacity: 0;
            left: 50%;
            top: 50%;
            width: 100%;
            height: 100%;
            transform: translate(-50%, -50%) scale(0);
            background-color: #f3dbff;
            border-radius: 50%;
            transition: .3s;
        }

        .item-delete:hover:after {
            transform: translate(-50%, -50%) scale(2.2);
            opacity: 1;
        }

        .item-delete:hover:before {
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg fill='%234f555f' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 438.5 438.5'%3e%3cpath d='M417.7 75.7A8.9 8.9 0 00411 73H323l-20-47.7c-2.8-7-8-13-15.4-18S272.5 0 264.9 0h-91.3C166 0 158.5 2.5 151 7.4c-7.4 5-12.5 11-15.4 18l-20 47.7H27.4a9 9 0 00-6.6 2.6 9 9 0 00-2.5 6.5v18.3c0 2.7.8 4.8 2.5 6.6a8.9 8.9 0 006.6 2.5h27.4v271.8c0 15.8 4.5 29.3 13.4 40.4a40.2 40.2 0 0032.3 16.7H338c12.6 0 23.4-5.7 32.3-17.2a64.8 64.8 0 0013.4-41V109.6h27.4c2.7 0 4.9-.8 6.6-2.5a8.9 8.9 0 002.6-6.6V82.2a9 9 0 00-2.6-6.5zm-248.4-36a8 8 0 014.9-3.2h90.5a8 8 0 014.8 3.2L283.2 73H155.3l14-33.4zm177.9 340.6a32.4 32.4 0 01-6.2 19.3c-1.4 1.6-2.4 2.4-3 2.4H100.5c-.6 0-1.6-.8-3-2.4a32.5 32.5 0 01-6.1-19.3V109.6h255.8v270.7z'/%3e%3cpath d='M137 347.2h18.3c2.7 0 4.9-.9 6.6-2.6a9 9 0 002.5-6.6V173.6a9 9 0 00-2.5-6.6 8.9 8.9 0 00-6.6-2.6H137c-2.6 0-4.8.9-6.5 2.6a8.9 8.9 0 00-2.6 6.6V338c0 2.7.9 4.9 2.6 6.6a8.9 8.9 0 006.5 2.6zM210.1 347.2h18.3a8.9 8.9 0 009.1-9.1V173.5c0-2.7-.8-4.9-2.5-6.6a8.9 8.9 0 00-6.6-2.6h-18.3a8.9 8.9 0 00-9.1 9.1V338a8.9 8.9 0 009.1 9.1zM283.2 347.2h18.3c2.7 0 4.8-.9 6.6-2.6a8.9 8.9 0 002.5-6.6V173.6c0-2.7-.8-4.9-2.5-6.6a8.9 8.9 0 00-6.6-2.6h-18.3a9 9 0 00-6.6 2.6 8.9 8.9 0 00-2.5 6.6V338a9 9 0 002.5 6.6 9 9 0 006.6 2.6z'/%3e%3c/svg%3e");
        }

        .file-input {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 100%;
            cursor: pointer;
            opacity: 0;
        }
        .file-input:focus {
            outline: none;
        }
    </style>

    @if(session('status'))
        <div>{{ session('status') }}</div>
    @endif

    <form action="/upload" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="upload">Upload File:</label>
                <div class="container">
                    <div class="file-drop-area">
                        <span class="fake-btn">Choose files</span>
                        <span class="file-msg">or drop files here</span>
                        <input type="file" name="csvfile" id="upload" class="file-input" multiple>
                        <div class="item-delete"></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </div>
            
    </form>


    <h2>Recent Uploads</h2>
    <table style="width: 100%; display: table; table-layout: fixed;" class="table table-striped table-bordered text-center table-hover table-responsive">
        <thead>
            <tr>
                <th>Filename</th>
                <th>Uploaded At</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($uploads as $upload)
                <tr>
                    <td>{{ $upload->filename }}</td>
                    <td>{{ $upload->uploaded_at }}</td>
                    <td>{{ $upload->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        const $fileInput = $('.file-input');
        const $droparea = $('.file-drop-area');
        const $delete = $('.item-delete');

        $fileInput.on('dragenter focus click', function () {
            $droparea.addClass('is-active');
        });

        $fileInput.on('dragleave blur drop', function () {
            $droparea.removeClass('is-active');
        });

        $fileInput.on('change', function () {
            let filesCount = $(this)[0].files.length;
            let $textContainer = $(this).prev();

            if (filesCount === 1) {
                let fileName = $(this).val().split('\\').pop();
                $textContainer.text(fileName);
                $('.item-delete').css('display', 'inline-block');
            } else if (filesCount === 0) {
                $textContainer.text('or drop files here');
                $('.item-delete').css('display', 'none');
            } else {
                $textContainer.text(filesCount + ' files selected');
                $('.item-delete').css('display', 'inline-block');
            }
        });

        $delete.on('click', function () {
            $('.file-input').val(null);
            $('.file-msg').text('or drop files here');
            $('.item-delete').css('display', 'none');
        });
    </script>
</body>
</html>
