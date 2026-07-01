@if (config('services.recaptcha.site_key'))
    <div class="mb-4">
        <div class="h-[59px] w-[228px]">
            <div class="g-recaptcha transform scale-[0.75] origin-top-left" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
        </div>
    </div>
@endif
