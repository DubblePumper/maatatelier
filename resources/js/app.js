const wizard = document.querySelector('[data-quote-wizard]');

if (wizard) {
    const panels = [...wizard.querySelectorAll('[data-wizard-step]')];
    const nextButton = wizard.querySelector('[data-wizard-next]');
    const backButton = wizard.querySelector('[data-wizard-back]');
    const submitButton = wizard.querySelector('[data-wizard-submit]');
    const progressBar = wizard.querySelector('[data-progress-bar]');
    const progressLabel = wizard.querySelector('[data-progress-label]');
    const progressClasses = ['w-1/5', 'w-2/5', 'w-3/5', 'w-4/5', 'w-full'];
    const panelWithError = panels.findIndex((panel) => panel.querySelector('.form-error'));
    let currentStep = panelWithError >= 0
        ? panelWithError
        : window.location.hash === '#kast-ontwerper' ? 1 : 0;

    const humanize = (value) => value.replaceAll('-', ' ').replace(/^./, (character) => character.toUpperCase());

    const selectedLabel = (name) => {
        const field = wizard.querySelector(`[name="${name}"]:checked`) ?? wizard.querySelector(`[name="${name}"]`);

        if (!field?.value) {
            return 'Nog niet gekozen';
        }

        if (field instanceof HTMLSelectElement) {
            return field.selectedOptions[0]?.textContent.trim() ?? humanize(field.value);
        }

        return field.closest('label')?.textContent.trim() ?? humanize(field.value);
    };

    const updateSummary = () => {
        ['project_type', 'style', 'budget', 'finish'].forEach((name) => {
            const target = wizard.querySelector(`[data-summary="${name}"]`);

            if (target) {
                target.textContent = selectedLabel(name);
            }
        });

        const fileInput = wizard.querySelector('#attachments');
        const count = fileInput?.files?.length ?? 0;
        const fileSummary = count === 0 ? 'Geen bestanden' : `${count} bestand${count === 1 ? '' : 'en'}`;

        wizard.querySelector('[data-summary="attachments"]').textContent = fileSummary;
    };

    const updateClosetDesigner = () => {
        const preview = wizard.querySelector('[data-cabinet-preview]');
        const description = wizard.querySelector('[data-cabinet-description]');
        const columnsField = wizard.querySelector('#layout_columns');
        const finishField = wizard.querySelector('#finish');

        if (!preview || !description || !columnsField || !finishField) {
            return;
        }

        const columns = Math.max(1, Math.min(6, Number.parseInt(columnsField.value, 10) || 3));
        const finishLabel = finishField.selectedOptions[0]?.textContent.trim() ?? 'Licht eiken';

        preview.dataset.finish = finishField.value;
        preview.querySelectorAll('[data-cabinet-module]').forEach((module, index) => {
            module.classList.toggle('hidden', index >= columns);
        });
        description.textContent = `Kast met ${columns} ${columns === 1 ? 'module' : 'modules'} in ${finishLabel.toLowerCase()}.`;
    };

    const initializeFileUpload = () => {
        const fileInput = wizard.querySelector('#attachments');
        const uploadZone = wizard.querySelector('[data-upload-zone]');
        const previews = wizard.querySelector('[data-file-previews]');
        const fileSummary = wizard.querySelector('[data-file-summary]');

        if (!fileInput || !uploadZone || !previews || !fileSummary) {
            return;
        }

        const maximumFiles = 5;
        const maximumBytes = 15 * 1000 * 1000;
        const allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
        let previewUrls = [];

        const formatFileSize = (bytes) => {
            if (bytes < 1024 * 1024) {
                return `${Math.max(1, Math.round(bytes / 1024))} KB`;
            }

            return `${(bytes / (1024 * 1024)).toFixed(1).replace('.', ',')} MB`;
        };

        const clearPreviewUrls = () => {
            previewUrls.forEach((url) => URL.revokeObjectURL(url));
            previewUrls = [];
        };

        const setFiles = (files) => {
            if (typeof DataTransfer === 'undefined') {
                return false;
            }

            const transfer = new DataTransfer();
            files.forEach((file) => transfer.items.add(file));
            fileInput.files = transfer.files;

            return true;
        };

        const renderPreviews = () => {
            clearPreviewUrls();
            previews.replaceChildren();

            const files = [...fileInput.files];
            previews.classList.toggle('hidden', files.length === 0);
            previews.classList.toggle('grid', files.length > 0);

            files.forEach((file, index) => {
                const card = document.createElement('article');
                const media = document.createElement('div');
                const details = document.createElement('div');
                const fileName = document.createElement('p');
                const fileSize = document.createElement('p');

                card.className = 'upload-preview';
                media.className = 'grid aspect-[4/3] place-items-center bg-sand';
                details.className = 'p-3 pr-10';
                fileName.className = 'truncate text-xs font-semibold';
                fileName.textContent = file.name;
                fileName.title = file.name;
                fileSize.className = 'mt-1 text-[0.68rem] text-anthracite/70';
                fileSize.textContent = formatFileSize(file.size);

                if (file.type.startsWith('image/')) {
                    const image = document.createElement('img');
                    const previewUrl = URL.createObjectURL(file);

                    previewUrls.push(previewUrl);
                    image.src = previewUrl;
                    image.alt = `Voorbeeld van ${file.name}`;
                    image.className = 'size-full object-cover';
                    media.append(image);
                } else {
                    const label = document.createElement('span');

                    label.className = 'font-brand text-sm font-semibold text-olive';
                    label.textContent = 'PDF';
                    media.append(label);
                }

                details.append(fileName, fileSize);
                card.append(media, details);

                if (typeof DataTransfer !== 'undefined') {
                    const removeButton = document.createElement('button');

                    removeButton.type = 'button';
                    removeButton.className = 'absolute bottom-3 right-3 grid size-10 place-items-center rounded-full border border-olive bg-ivory text-sm font-semibold text-anthracite hover:border-anthracite';
                    removeButton.setAttribute('aria-label', `${file.name} verwijderen`);
                    removeButton.textContent = '×';
                    removeButton.addEventListener('click', () => {
                        const remainingFiles = [...fileInput.files].filter((_, fileIndex) => fileIndex !== index);

                        if (setFiles(remainingFiles)) {
                            fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    });
                    card.append(removeButton);
                }

                previews.append(card);
            });

            fileSummary.textContent = files.length === 0
                ? fileSummary.dataset.emptyText
                : `${files.length} van maximaal ${maximumFiles} bestanden gekozen.`;
        };

        const acceptFiles = (files) => {
            const validFiles = files.filter((file) => {
                const extension = file.name.split('.').pop()?.toLowerCase();

                return extension
                    && allowedExtensions.includes(extension)
                    && file.size <= maximumBytes;
            });
            const selectedFiles = validFiles.slice(0, maximumFiles);

            if (!setFiles(selectedFiles)) {
                return;
            }

            fileInput.dispatchEvent(new Event('change', { bubbles: true }));

            if (validFiles.length !== files.length || validFiles.length > maximumFiles) {
                fileSummary.textContent = 'Niet alle bestanden zijn toegevoegd. Gebruik maximaal 5 JPG-, PNG-, WebP- of PDF-bestanden van maximaal 15 MB.';
            }
        };

        fileInput.addEventListener('change', () => {
            const files = [...fileInput.files];
            const hasInvalidFile = files.some((file) => {
                const extension = file.name.split('.').pop()?.toLowerCase();

                return !extension
                    || !allowedExtensions.includes(extension)
                    || file.size > maximumBytes;
            });

            if (files.length > maximumFiles || hasInvalidFile) {
                acceptFiles(files);

                return;
            }

            renderPreviews();
        });
        ['dragenter', 'dragover'].forEach((eventName) => {
            uploadZone.addEventListener(eventName, (event) => {
                event.preventDefault();
                uploadZone.classList.add('is-dragging');
            });
        });
        ['dragleave', 'drop'].forEach((eventName) => {
            uploadZone.addEventListener(eventName, (event) => {
                event.preventDefault();
                uploadZone.classList.remove('is-dragging');
            });
        });
        uploadZone.addEventListener('drop', (event) => {
            acceptFiles([...event.dataTransfer.files]);
        });

        renderPreviews();
    };

    const showStep = (step, shouldFocus = false) => {
        currentStep = Math.max(0, Math.min(step, panels.length - 1));

        panels.forEach((panel, index) => {
            panel.hidden = index !== currentStep;
        });

        progressBar.classList.remove(...progressClasses);
        progressBar.classList.add(progressClasses[currentStep]);
        progressLabel.textContent = `Stap ${currentStep + 1} van ${panels.length}`;
        backButton.classList.toggle('hidden', currentStep === 0);
        nextButton.classList.toggle('hidden', currentStep === panels.length - 1);
        submitButton.classList.toggle('hidden', currentStep !== panels.length - 1);

        if (shouldFocus) {
            const legend = panels[currentStep].querySelector('legend');

            if (legend) {
                legend.tabIndex = -1;
                legend.focus({ preventScroll: true });
            }

            panels[currentStep].scrollIntoView({
                behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth',
                block: 'start',
            });
        }

        updateSummary();
    };

    const currentPanelIsValid = () => {
        const invalidField = panels[currentStep].querySelector(':invalid');

        if (!invalidField) {
            return true;
        }

        invalidField.reportValidity();
        invalidField.focus();

        return false;
    };

    nextButton.addEventListener('click', () => {
        if (currentPanelIsValid()) {
            showStep(currentStep + 1, true);
        }
    });

    backButton.addEventListener('click', () => showStep(currentStep - 1, true));
    wizard.addEventListener('change', () => {
        updateSummary();
        updateClosetDesigner();
    });
    wizard.addEventListener('input', updateClosetDesigner);
    wizard.addEventListener('submit', () => {
        submitButton.disabled = true;
        submitButton.textContent = 'Aanvraag versturen…';
    });

    document.querySelector('[data-form-errors]')?.focus();
    initializeFileUpload();
    updateClosetDesigner();
    showStep(currentStep);
}
