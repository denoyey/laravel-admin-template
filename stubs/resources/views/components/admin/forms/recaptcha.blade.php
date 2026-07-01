@if (env('RECAPTCHA_SITE_KEY'))
    <div class="mb-4">
        <div class="h-[59px] w-[228px]">
            <div class="g-recaptcha transform scale-[0.75] origin-top-left"
                data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
        </div>
    </div>
@endif
