import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['input', 'progress', 'status'];
    static values = {
        xhrUpload: { type: Boolean, default: false },
        maxSize: Number,
    };

    connect() {
        this.progressValue = 0;
        this.statusValue = 'idle';
    }

    select(event) {
        const file = event.target.files?.[0];
        if (!file) {
            return;
        }

        if (this.maxSizeValue && file.size > this.maxSizeValue) {
            this.setStatus('error', 'File exceeds maximum size.');
            return;
        }

        if (this.xhrUploadValue) {
            this.simulateUpload();
        }
    }

    simulateUpload() {
        this.setStatus('uploading');
        let progress = 0;
        const timer = window.setInterval(() => {
            progress += 10;
            this.setProgress(progress);
            this.dispatch('form-ui:upload:progress', { detail: { progress } });

            if (progress >= 100) {
                window.clearInterval(timer);
                this.setStatus('success');
                this.dispatch('form-ui:upload:complete', { detail: { progress: 100 } });
            }
        }, 120);
    }

    cancel() {
        this.setProgress(0);
        this.setStatus('idle');
    }

    setProgress(value) {
        this.progressValue = value;
        if (this.hasProgressTarget) {
            this.progressTarget.textContent = `${value}%`;
            this.progressTarget.setAttribute('aria-valuenow', String(value));
        }
    }

    setStatus(status, errorMessage = null) {
        this.statusValue = status;
        if (this.hasStatusTarget) {
            this.statusTarget.textContent = errorMessage ?? status;
        }

        if (status === 'error') {
            this.dispatch('form-ui:upload:error', { detail: { message: errorMessage } });
        }
    }
}
