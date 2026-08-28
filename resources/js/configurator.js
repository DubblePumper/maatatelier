const svgNamespace = 'http://www.w3.org/2000/svg';
const storageKey = 'maatatelier_configurator_v1';

const formatEuro = new Intl.NumberFormat(document.documentElement.lang || 'nl-BE', {
    style: 'currency',
    currency: 'EUR',
    maximumFractionDigits: 0,
});

const clamp = (value, minimum, maximum) => Math.max(minimum, Math.min(maximum, value));

const normalizeNumberInput = (input) => {
    const value = input.valueAsNumber;

    if (!Number.isFinite(value)) {
        return false;
    }

    const minimum = Number.parseFloat(input.min);
    const maximum = Number.parseFloat(input.max);
    const step = Number.parseFloat(input.step);
    let normalized = clamp(
        value,
        Number.isFinite(minimum) ? minimum : value,
        Number.isFinite(maximum) ? maximum : value,
    );

    if (Number.isFinite(step) && step > 0) {
        const stepBase = Number.isFinite(minimum) ? minimum : 0;

        normalized = stepBase + Math.round((normalized - stepBase) / step) * step;
        normalized = clamp(
            normalized,
            Number.isFinite(minimum) ? minimum : normalized,
            Number.isFinite(maximum) ? maximum : normalized,
        );
    }

    input.value = String(normalized);

    return true;
};

const svgElement = (name, attributes = {}) => {
    const element = document.createElementNS(svgNamespace, name);

    Object.entries(attributes).forEach(([attribute, value]) => {
        element.setAttribute(attribute, String(value));
    });

    return element;
};

const percentageAdjustment = (amountCents, adjustmentBasisPoints) => {
    const absoluteAdjustment = Math.trunc(
        (amountCents * Math.abs(adjustmentBasisPoints) + 5_000) / 10_000,
    );

    return adjustmentBasisPoints < 0 ? -absoluteAdjustment : absoluteAdjustment;
};

const dimensionAdjustmentBasisPoints = (dimension, value, rules) => {
    const settings = rules.dimensions[dimension];

    return Math.trunc(
        ((value - settings.standard) * settings.adjustment_basis_points_per_100_mm) / 100,
    );
};

const calculatePrice = (configuration, rules) => {
    const widthSettings = rules.dimensions.width_mm;
    const expectedModules = clamp(
        Math.trunc(
            (configuration.width_mm + Math.trunc(rules.modules.standard_width_mm / 2))
            / rules.modules.standard_width_mm,
        ),
        rules.modules.min,
        rules.modules.max,
    );
    const baseBenchmarkCents = Math.trunc(
        (rules.benchmark.standard_per_linear_metre_cents * configuration.width_mm) / widthSettings.standard,
    );
    const adjustmentBasisPoints = [
        rules.types[configuration.type].adjustment_basis_points,
        rules.fronts[configuration.front].adjustment_basis_points,
        rules.materials[configuration.material].adjustment_basis_points,
        rules.levels[configuration.level].adjustment_basis_points,
        dimensionAdjustmentBasisPoints('height_mm', configuration.height_mm, rules),
        dimensionAdjustmentBasisPoints('depth_mm', configuration.depth_mm, rules),
    ];
    const adjustmentsCents = adjustmentBasisPoints.reduce(
        (total, basisPoints) => total + percentageAdjustment(baseBenchmarkCents, basisPoints),
        0,
    );
    const extrasCents = (
        configuration.extras.laden * rules.extras.laden.unit_benchmark_cents
        + configuration.extras.roedes * rules.extras.roedes.unit_benchmark_cents
        + (configuration.extras.led
            ? Math.trunc(
                (rules.extras.led.benchmark_per_linear_metre_cents * configuration.width_mm)
                / widthSettings.standard,
            )
            : 0)
    );
    const modulesCents = (configuration.modules - expectedModules) * rules.modules.unit_benchmark_cents;
    const benchmarkPriceCents = Math.max(
        0,
        baseBenchmarkCents + adjustmentsCents + modulesCents + extrasCents,
    );
    const targetBasisPoints = 10_000 - rules.benchmark.discount_basis_points;
    const unroundedPriceCents = Math.trunc((benchmarkPriceCents * targetBasisPoints) / 10_000);
    const estimatedPriceCents = Math.trunc(
        unroundedPriceCents / rules.benchmark.rounding_increment_cents,
    ) * rules.benchmark.rounding_increment_cents;

    return {
        benchmarkPriceCents,
        estimatedPriceCents,
        savingsCents: benchmarkPriceCents - estimatedPriceCents,
    };
};

const selectedValue = (form, name, fallback = '') => {
    const checked = form.querySelector(`[name="${name}"]:checked`);

    if (checked) {
        return checked.value;
    }

    const field = form.querySelector(`[name="${name}"]`);

    if (field?.matches('input[type="radio"], input[type="checkbox"]')) {
        return fallback;
    }

    return field?.value ?? fallback;
};

const integerValue = (form, name, fallback = 0) => {
    const value = Number.parseInt(form.querySelector(`[name="${name}"]`)?.value, 10);

    return Number.isFinite(value) ? value : fallback;
};

const readConfiguration = (form, rules) => {
    const type = selectedValue(form, 'project_type', rules.defaults.type);
    const typeIsSupported = Object.hasOwn(rules.types, type);

    return {
        type,
        typeIsSupported,
        width_mm: clamp(
            integerValue(form, 'width_mm', rules.dimensions.width_mm.default),
            rules.dimensions.width_mm.min,
            rules.dimensions.width_mm.max,
        ),
        height_mm: clamp(
            integerValue(form, 'height_mm', rules.dimensions.height_mm.default),
            rules.dimensions.height_mm.min,
            rules.dimensions.height_mm.max,
        ),
        depth_mm: clamp(
            integerValue(form, 'depth_mm', rules.dimensions.depth_mm.default),
            rules.dimensions.depth_mm.min,
            rules.dimensions.depth_mm.max,
        ),
        modules: clamp(
            integerValue(form, 'layout_columns', rules.modules.default),
            rules.modules.min,
            rules.modules.max,
        ),
        front: selectedValue(form, 'front_style', rules.defaults.front),
        material: selectedValue(form, 'finish', rules.defaults.material),
        level: selectedValue(form, 'interior_level', rules.defaults.level),
        extras: {
            laden: clamp(integerValue(form, 'drawer_count', rules.extras.laden.default), rules.extras.laden.min, rules.extras.laden.max),
            roedes: clamp(integerValue(form, 'rail_count', rules.extras.roedes.default), rules.extras.roedes.min, rules.extras.roedes.max),
            led: form.querySelector('[name="led_lighting"]:checked')?.value === '1',
        },
    };
};

const renderPreview = (preview, configuration) => {
    const drawing = preview.querySelector('[data-configurator-drawing]');

    if (!drawing) {
        return;
    }

    drawing.replaceChildren();

    const maximumWidth = 620;
    const maximumHeight = 390;
    const scale = Math.min(maximumWidth / configuration.width_mm, maximumHeight / configuration.height_mm);
    const cabinetWidth = configuration.width_mm * scale;
    const cabinetHeight = configuration.height_mm * scale;
    const x = (800 - cabinetWidth) / 2 - 8;
    const y = 510 - cabinetHeight;
    const depthOffset = 12 + Math.round(((configuration.depth_mm - 250) / 550) * 34);
    const materialFill = {
        ivoor: '#f7f5f2',
        zand: '#e7ded1',
        olijfbrons: '#6f6a4d',
        'licht-eiken': 'url(#oak-grain)',
        'naturel-eiken': '#b8aa98',
    }[configuration.material] ?? 'url(#oak-grain)';
    const darkFront = configuration.material === 'olijfbrons';
    const detailColour = darkFront ? '#f7f5f2' : '#222222';
    const moduleWidth = cabinetWidth / configuration.modules;

    drawing.append(
        svgElement('path', {
            d: `M ${x + cabinetWidth} ${y} L ${x + cabinetWidth + depthOffset} ${y - depthOffset} L ${x + cabinetWidth + depthOffset} ${y + cabinetHeight - depthOffset} L ${x + cabinetWidth} ${y + cabinetHeight} Z`,
            fill: configuration.material === 'olijfbrons' ? '#59563e' : '#b8aa98',
            stroke: '#6f6a4d',
            'stroke-width': 2,
        }),
        svgElement('rect', {
            x,
            y,
            width: cabinetWidth,
            height: cabinetHeight,
            rx: 3,
            fill: materialFill,
            stroke: '#6f6a4d',
            'stroke-width': 4,
        }),
    );

    for (let moduleIndex = 0; moduleIndex < configuration.modules; moduleIndex += 1) {
        const moduleX = x + moduleIndex * moduleWidth;
        const innerX = moduleX + 5;
        const innerY = y + 5;
        const innerWidth = moduleWidth - 10;
        const innerHeight = cabinetHeight - 10;

        if (configuration.front === 'open') {
            drawing.append(svgElement('rect', {
                x: innerX,
                y: innerY,
                width: innerWidth,
                height: innerHeight,
                fill: '#f7f5f2',
                stroke: '#222222',
                'stroke-opacity': 0.28,
                'stroke-width': 1.5,
            }));

            const shelfCount = configuration.level === 'premium' ? 4 : configuration.level === 'comfort' ? 3 : 2;

            for (let shelfIndex = 1; shelfIndex <= shelfCount; shelfIndex += 1) {
                const shelfY = innerY + (innerHeight / (shelfCount + 1)) * shelfIndex;

                drawing.append(svgElement('path', {
                    d: `M ${innerX + 4} ${shelfY} H ${innerX + innerWidth - 4}`,
                    stroke: '#222222',
                    'stroke-opacity': 0.25,
                    'stroke-width': 1.5,
                }));
            }
        } else {
            drawing.append(svgElement('rect', {
                x: innerX,
                y: innerY,
                width: innerWidth,
                height: innerHeight,
                rx: 1,
                fill: materialFill,
                stroke: '#222222',
                'stroke-opacity': 0.28,
                'stroke-width': configuration.front === 'schuifdeuren' ? 2.5 : 1.5,
            }));

            if (configuration.front === 'draaideuren') {
                drawing.append(svgElement('line', {
                    x1: innerX + innerWidth - 11,
                    y1: innerY + innerHeight * 0.43,
                    x2: innerX + innerWidth - 11,
                    y2: innerY + innerHeight * 0.57,
                    stroke: detailColour,
                    'stroke-width': 2,
                    'stroke-linecap': 'round',
                    opacity: 0.72,
                }));
            }
        }

        if (moduleIndex > 0) {
            drawing.append(svgElement('line', {
                x1: moduleX,
                y1: y + 4,
                x2: moduleX,
                y2: y + cabinetHeight - 4,
                stroke: detailColour,
                'stroke-opacity': 0.3,
                'stroke-width': 1.5,
            }));
        }
    }

    const drawerHeight = Math.min(26, cabinetHeight / 7);

    for (let drawerIndex = 0; drawerIndex < configuration.extras.laden; drawerIndex += 1) {
        const moduleIndex = drawerIndex % configuration.modules;
        const rowIndex = Math.floor(drawerIndex / configuration.modules);
        const drawerY = y + cabinetHeight - 10 - drawerHeight * (rowIndex + 1);

        if (drawerY <= y + 10) {
            break;
        }

        drawing.append(svgElement('rect', {
            x: x + moduleIndex * moduleWidth + 8,
            y: drawerY,
            width: moduleWidth - 16,
            height: drawerHeight - 3,
            rx: 1,
            fill: darkFront ? '#59563e' : '#e7ded1',
            stroke: detailColour,
            'stroke-opacity': 0.35,
        }));
    }

    for (let railIndex = 0; railIndex < Math.min(configuration.extras.roedes, configuration.modules); railIndex += 1) {
        const railX = x + railIndex * moduleWidth + 14;
        const railY = y + Math.min(62, cabinetHeight * 0.24);

        drawing.append(svgElement('line', {
            x1: railX,
            y1: railY,
            x2: railX + moduleWidth - 28,
            y2: railY,
            stroke: detailColour,
            'stroke-width': 3,
            'stroke-linecap': 'round',
            opacity: configuration.front === 'open' ? 0.55 : 0.18,
        }));
    }

    if (configuration.extras.led) {
        drawing.append(svgElement('line', {
            x1: x + 14,
            y1: y + 13,
            x2: x + cabinetWidth - 14,
            y2: y + 13,
            stroke: '#f7f5f2',
            'stroke-width': 7,
            'stroke-linecap': 'round',
            opacity: 0.9,
        }));
        drawing.append(svgElement('line', {
            x1: x + 14,
            y1: y + 13,
            x2: x + cabinetWidth - 14,
            y2: y + 13,
            stroke: '#d8b58a',
            'stroke-width': 2,
            'stroke-linecap': 'round',
        }));
    }
};

const applyTypeDefaults = (form, rules, type) => {
    const defaults = rules.types[type]?.defaults;

    if (!defaults) {
        return;
    }

    Object.entries(defaults).forEach(([name, value]) => {
        const input = form.querySelector(`[name="${name}"]`);
        const range = form.querySelector(`[data-range-for="${name}"]`);

        if (input) {
            input.value = String(value);
        }

        if (range) {
            range.value = String(value);
        }
    });
};

const restoreSavedConfiguration = (form, rules) => {
    if (form.dataset.hasErrors === 'true') {
        return;
    }

    try {
        const stored = JSON.parse(window.localStorage.getItem(storageKey));

        if (!stored || stored.pricingVersion !== rules.pricing_version) {
            return;
        }

        Object.entries(stored.fields ?? {}).forEach(([name, value]) => {
            const candidates = [...form.querySelectorAll(`[name="${name}"]`)];

            if (candidates.length === 0) {
                return;
            }

            if (candidates.some((candidate) => candidate.type === 'radio')) {
                const radio = candidates.find((candidate) => candidate.value === String(value));

                if (radio) {
                    radio.checked = true;
                }

                return;
            }

            const checkbox = candidates.find((candidate) => candidate.type === 'checkbox');

            if (checkbox) {
                checkbox.checked = value === true || value === 1 || value === '1';

                return;
            }

            const input = candidates.find((candidate) => candidate.type !== 'hidden') ?? candidates[0];

            input.value = String(value);
            const range = form.querySelector(`[data-range-for="${name}"]`);

            if (range) {
                range.value = String(value);
            }
        });
    } catch {
        // Een onleesbaar lokaal ontwerp wordt genegeerd; persoonsgegevens worden nooit opgeslagen.
    }
};

const saveConfiguration = (configuration, rules) => {
    try {
        window.localStorage.setItem(storageKey, JSON.stringify({
            pricingVersion: rules.pricing_version,
            fields: {
                project_type: configuration.type,
                width_mm: configuration.width_mm,
                height_mm: configuration.height_mm,
                depth_mm: configuration.depth_mm,
                layout_columns: configuration.modules,
                front_style: configuration.front,
                finish: configuration.material,
                interior_level: configuration.level,
                drawer_count: configuration.extras.laden,
                rail_count: configuration.extras.roedes,
                led_lighting: configuration.extras.led,
            },
        }));
    } catch {
        // De configurator blijft werken wanneer lokale opslag niet beschikbaar is.
    }
};

export const initializeFurnitureConfigurator = (form) => {
    let translations = {};

    try {
        translations = JSON.parse(form.dataset.configuratorTranslations ?? '{}');
    } catch {
        translations = {};
    }

    const translate = (key, replacements = {}) => {
        let message = translations[key] ?? key;

        Object.entries(replacements).forEach(([name, value]) => {
            message = message.replaceAll(`:${name}`, String(value));
        });

        return message;
    };
    if (!form?.matches('[data-furniture-configurator]')) {
        return;
    }

    let rules;

    try {
        rules = JSON.parse(form.dataset.configuratorRules);
    } catch {
        return;
    }

    const preview = form.querySelector('[data-configurator-preview]');
    const description = form.querySelector('[data-configurator-description]');
    const priceTargets = [...form.querySelectorAll('[data-configurator-price], [data-configurator-price-mobile]')];
    const priceTotal = form.querySelector('[data-price-total]');
    const benchmarkPrice = form.querySelector('[data-benchmark-price]');
    const priceDiscount = form.querySelector('[data-price-discount]');
    const priceStatusTargets = [...form.querySelectorAll('[data-price-status], [data-price-status-mobile]')];
    const priceDetails = form.querySelector('[data-price-details]');
    const announcement = form.querySelector('[data-configurator-announcement]');
    const configurationSummary = form.querySelector('[data-summary="configuration"]');
    const configuredField = form.querySelector('[name="configured"]');
    const liveConfiguratorPanel = form.querySelector('[data-live-configurator-panel]');
    const personalProjectPanel = form.querySelector('[data-personal-project-panel]');
    const configurationFieldSelector = [
        '[name="project_type"]',
        '[name="width_mm"]',
        '[name="height_mm"]',
        '[name="depth_mm"]',
        '[name="layout_columns"]',
        '[name="front_style"]',
        '[name="finish"]',
        '[name="interior_level"]',
        '[name="drawer_count"]',
        '[name="rail_count"]',
        '[name="led_lighting"]',
        '[data-range-for]',
    ].join(', ');
    let descriptionTimeout;
    let announcementTimeout;
    let renderFrame;

    restoreSavedConfiguration(form, rules);

    if (form.dataset.hasErrors !== 'true') {
        form.querySelectorAll('[data-measurement-control] input[type="number"], .counter-number').forEach((input) => {
            normalizeNumberInput(input);

            const range = form.querySelector(`[data-range-for="${input.name}"]`);

            if (range) {
                range.value = input.value;
            }
        });
    }

    const updateOptionPrices = (configuration, currentPrice) => {
        form.querySelectorAll('[data-material-price]').forEach((target) => {
            const material = target.dataset.materialPrice;
            const alternative = calculatePrice({ ...configuration, material }, rules);
            const difference = alternative.estimatedPriceCents - currentPrice.estimatedPriceCents;

            target.textContent = material === configuration.material
                ? translate('chosen')
                : difference === 0
                    ? translate('same_price')
                    : `${difference > 0 ? '+' : '−'} ${formatEuro.format(Math.abs(difference) / 100)}`;
        });

        const ledPrice = form.querySelector('[data-option-price="led_lighting"]');

        if (ledPrice) {
            const withoutLed = calculatePrice({
                ...configuration,
                extras: { ...configuration.extras, led: false },
            }, rules);
            const withLed = calculatePrice({
                ...configuration,
                extras: { ...configuration.extras, led: true },
            }, rules);
            const difference = withLed.estimatedPriceCents - withoutLed.estimatedPriceCents;

            ledPrice.textContent = `+ ${formatEuro.format(difference / 100)}`;
        }
    };

    const clearOptionPrices = () => {
        form.querySelectorAll('[data-material-price], [data-option-price]').forEach((target) => {
            target.textContent = translate('price_after_design');
        });
    };

    const setLiveConfiguratorAvailability = (isAvailable) => {
        if (liveConfiguratorPanel) {
            liveConfiguratorPanel.hidden = !isAvailable;
            liveConfiguratorPanel.querySelectorAll('button, input, select, textarea').forEach((control) => {
                control.disabled = !isAvailable;
            });
        }

        if (personalProjectPanel) {
            personalProjectPanel.hidden = isAvailable;
        }

        if (configuredField) {
            configuredField.value = isAvailable ? '1' : '0';
        }
    };

    const updateControlStates = () => {
        form.querySelectorAll('[data-measurement-control]').forEach((control) => {
            const number = control.querySelector('input[type="number"]');

            if (!number) {
                return;
            }

            const value = Number.parseFloat(number.value);
            const minimum = Number.parseFloat(number.min);
            const maximum = Number.parseFloat(number.max);

            control.querySelector('[data-step-down]')?.toggleAttribute(
                'disabled',
                Number.isFinite(value) && Number.isFinite(minimum) && value <= minimum,
            );
            control.querySelector('[data-step-up]')?.toggleAttribute(
                'disabled',
                Number.isFinite(value) && Number.isFinite(maximum) && value >= maximum,
            );
        });

        form.querySelectorAll('[data-counter-down], [data-counter-up]').forEach((button) => {
            const inputName = button.dataset.counterDown ?? button.dataset.counterUp;
            const input = form.querySelector(`[name="${inputName}"]`);

            if (!input) {
                return;
            }

            const value = Number.parseFloat(input.value);
            const boundary = Number.parseFloat(
                button.hasAttribute('data-counter-down') ? input.min : input.max,
            );
            const hasReachedBoundary = Number.isFinite(value)
                && Number.isFinite(boundary)
                && (button.hasAttribute('data-counter-down') ? value <= boundary : value >= boundary);

            button.toggleAttribute('disabled', hasReachedBoundary);
        });
    };

    const render = () => {
        const configuration = readConfiguration(form, rules);
        const typeLabel = rules.types[configuration.type]?.label ?? translate('other_custom');
        const frontLabel = rules.fronts[configuration.front]?.label ?? configuration.front;
        const materialLabel = rules.materials[configuration.material]?.label ?? configuration.material;
        const levelLabel = rules.levels[configuration.level]?.label ?? configuration.level;
        const summary = configuration.typeIsSupported
            ? `${typeLabel}, ${configuration.width_mm} × ${configuration.height_mm} × ${configuration.depth_mm} mm, ${configuration.modules} ${translate(configuration.modules === 1 ? 'module' : 'modules')}, ${frontLabel.toLowerCase()}, ${materialLabel.toLowerCase()} ${translate('and')} ${levelLabel.toLowerCase()} ${translate('interior')}.`
            : translate('personal_description', { type: typeLabel });

        setLiveConfiguratorAvailability(configuration.typeIsSupported);

        if (preview && configuration.typeIsSupported) {
            renderPreview(preview, configuration);
        }

        if (configurationSummary) {
            configurationSummary.textContent = configuration.typeIsSupported
                ? `${configuration.width_mm} × ${configuration.height_mm} × ${configuration.depth_mm} mm · ${materialLabel}`
                : translate('personal_summary');
        }

        if (configuration.typeIsSupported) {
            updateControlStates();
        }

        window.clearTimeout(descriptionTimeout);
        descriptionTimeout = window.setTimeout(() => {
            if (description && configuration.typeIsSupported) {
                description.textContent = summary;
            }
        }, 180);

        window.clearTimeout(announcementTimeout);

        if (!configuration.typeIsSupported) {
            priceTargets.forEach((target) => {
                target.textContent = translate('personal_price');
            });
            priceStatusTargets.forEach((target) => {
                target.textContent = translate('personal_status');
            });

            if (priceDetails) {
                priceDetails.hidden = true;
            }

            clearOptionPrices();
            saveConfiguration(configuration, rules);

            announcementTimeout = window.setTimeout(() => {
                if (announcement) {
                    announcement.textContent = translate('personal_announcement', { summary });
                }
            }, 320);

            return;
        }

        const calculated = calculatePrice(configuration, rules);

        priceTargets.forEach((target) => {
            target.textContent = formatEuro.format(calculated.estimatedPriceCents / 100);
        });

        if (priceTotal) {
            priceTotal.textContent = formatEuro.format(calculated.estimatedPriceCents / 100);
        }

        if (benchmarkPrice) {
            benchmarkPrice.textContent = formatEuro.format(calculated.benchmarkPriceCents / 100);
        }

        if (priceDiscount) {
            priceDiscount.textContent = `− ${formatEuro.format(calculated.savingsCents / 100)}`;
        }

        priceStatusTargets.forEach((target) => {
            target.textContent = translate('live_status');
        });

        if (priceDetails) {
            priceDetails.hidden = false;
        }

        updateOptionPrices(configuration, calculated);
        saveConfiguration(configuration, rules);

        announcementTimeout = window.setTimeout(() => {
            if (announcement) {
                announcement.textContent = translate('live_announcement', {
                    summary,
                    price: formatEuro.format(calculated.estimatedPriceCents / 100),
                });
            }
        }, 320);
    };

    const scheduleRender = () => {
        window.cancelAnimationFrame(renderFrame);
        renderFrame = window.requestAnimationFrame(render);
    };

    form.querySelectorAll('[data-measurement-control]').forEach((control) => {
        const number = control.querySelector('input[type="number"]');
        const range = control.querySelector('input[type="range"]');

        control.querySelector('[data-step-down]')?.addEventListener('click', () => {
            number.stepDown();
            range.value = number.value;
            number.dispatchEvent(new Event('input', { bubbles: true }));
        });
        control.querySelector('[data-step-up]')?.addEventListener('click', () => {
            number.stepUp();
            range.value = number.value;
            number.dispatchEvent(new Event('input', { bubbles: true }));
        });
        range?.addEventListener('input', () => {
            number.value = range.value;
        });
        number?.addEventListener('input', () => {
            if (number.value !== '') {
                range.value = number.value;
            }
        });
        number?.addEventListener('change', () => {
            if (normalizeNumberInput(number)) {
                range.value = number.value;
            }
        });
    });

    form.querySelectorAll('[data-counter-down], [data-counter-up]').forEach((button) => {
        button.addEventListener('click', () => {
            const inputName = button.dataset.counterDown ?? button.dataset.counterUp;
            const input = form.querySelector(`[name="${inputName}"]`);

            if (!input) {
                return;
            }

            if (button.hasAttribute('data-counter-down')) {
                input.stepDown();
            } else {
                input.stepUp();
            }

            input.dispatchEvent(new Event('input', { bubbles: true }));
        });
    });

    form.querySelectorAll('.counter-number').forEach((input) => {
        input.addEventListener('change', () => {
            normalizeNumberInput(input);
        });
    });

    form.addEventListener('change', (event) => {
        if (!event.target.matches?.(configurationFieldSelector)) {
            return;
        }

        if (event.target.matches('[name="project_type"]')) {
            applyTypeDefaults(form, rules, event.target.value);
        }

        scheduleRender();
    });
    form.addEventListener('input', (event) => {
        if (event.target.matches?.(configurationFieldSelector)) {
            scheduleRender();
        }
    });

    render();
};
