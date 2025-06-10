<div style="display: flex; justify-content: center;">
    <div style="display: flex; flex-direction: column; gap: 1.5rem; width: 28.5rem; align-items: center;">
        <img style="height: 2.75rem; width: 4.5rem;" src="{{ $message->embed(public_path('images/logo.png')) }}"
            alt="logo">
        <h1 style="font-weight: bold; font-size: 2.5rem; text-align: center;">
            Verify your email address to get started
        </h1>
        <p style="color: #000;">
            Hi {{ $name }}, <br><br>
            You're almost there! To complete our sign up, please verify your email address.
        </p>
        <a style="padding: 0.625rem 3.25rem; font-weight: 600; color: #fff; background-color: #4B69FD; border-radius: 0.625rem; text-decoration:none;"
            href="{{ $url }}">Verify now</a>
    </div>
</div>
