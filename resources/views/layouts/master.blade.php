<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {!! $page->metaTags() !!}
</head>
<body class="max-w-md mx-auto p-4 font-sans text-darkest bg-lightest-grey">
    <a href="#main" class="clip">skip to content</a>

    <header role="banner">
        <a href="<?= $site->url() ?>" title="<?= $site->title()->html() ?>">
            <img src="{{ url('assets/images/logo.svg') }}" alt="<?= $site->title()->html() ?>">
        </a>

        @include('partials.menu')
    </header>

    <main id="main" class="content" role="main">
        <h1>@yield('title')</h1>

        @yield('content')
    </main>

    <footer role="contentinfo">
        {{ $site->copyright()->kirbytext() }}
    </footer>

    {!! snippet('google-analytics', null, true) !!}
</body>
</html>
