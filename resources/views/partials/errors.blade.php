@if (!empty($success) && $success->any())
    <div class="bg-green-100 border-l-4 border-green-500 text-green-900 p-5 shadow">
        <ul>
            @foreach ($success->all() as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (!empty($errors) && $errors->any())
    <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-5 shadow">
        <ul>
            @foreach ($errors->all() as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
@endif
