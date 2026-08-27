(() => {
    const bootstrapScript = document.currentScript;
    const measurementId = bootstrapScript?.dataset.measurementId;

    if (!measurementId || !/^G-[A-Z0-9]+$/.test(measurementId)) {
        return;
    }

    window.dataLayer = window.dataLayer || [];
    window.gtag = window.gtag || function gtag() {
        window.dataLayer.push(arguments);
    };

    const deniedConsent = {
        ad_storage: 'denied',
        ad_user_data: 'denied',
        ad_personalization: 'denied',
        analytics_storage: 'denied',
        functionality_storage: 'denied',
        personalization_storage: 'denied',
        security_storage: 'denied',
    };

    window.gtag('consent', 'default', {
        ...deniedConsent,
        wait_for_update: 500,
    });

    try {
        if (window.localStorage.getItem('maatatelier_consent_v1') === 'analytics-granted') {
            window.gtag('consent', 'update', {
                ...deniedConsent,
                analytics_storage: 'granted',
            });
        }
    } catch {
        // De standaard blijft geweigerd wanneer lokale opslag niet beschikbaar is.
    }

    window.gtag('set', 'ads_data_redaction', true);
    window.gtag('js', new Date());
    window.gtag('config', measurementId, {
        allow_ad_personalization_signals: false,
        allow_google_signals: false,
        cookie_expires: 15_552_000,
        cookie_update: false,
    });
})();
