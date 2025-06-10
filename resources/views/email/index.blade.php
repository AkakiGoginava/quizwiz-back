<div style="display: flex; justify-content: center;">
    <div style="display: flex; flex-direction: column; gap: 1.5rem; width: 28.5rem; align-items: center;">
        <img style="height: 2.75rem; width: 4.5rem;" src="{{ $message->embed(public_path('images/logo.png')) }}"
            alt="logo">
        <h1 style="font-weight: bold; font-size: 2.5rem; text-align: center;">
            {{ $title }}
        </h1>
        <p style="color: #000;">
            {{ $content }}
        </p>
        <a style="padding: 0.625rem 3.25rem; font-weight: 600; color: #fff; background-color: #4B69FD; border-radius: 0.625rem; text-decoration:none;"
            href="{{ $url }}">{{ $linkName }}</a>
    </div>
</div>
