import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['step', 'field'];
    static values = {
        currentStep: { type: Number, default: 1 },
        linear: { type: Boolean, default: true },
    };

    connect() {
        this.refreshVisibility();
    }

    goNext(event) {
        event.preventDefault();
        if (this.linearValue && !this.canAdvance()) {
            return;
        }
        if (this.currentStepValue < this.stepTargets.length) {
            this.currentStepValue += 1;
            this.refreshVisibility();
        }
    }

    goPrevious(event) {
        event.preventDefault();
        if (this.currentStepValue > 1) {
            this.currentStepValue -= 1;
            this.refreshVisibility();
        }
    }

    goToStep(event) {
        event.preventDefault();
        const step = Number.parseInt(event.currentTarget.dataset.step ?? '1', 10);
        if (!this.linearValue || step <= this.currentStepValue) {
            this.currentStepValue = step;
            this.refreshVisibility();
        }
    }

    canAdvance() {
        const fields = this.fieldTargets.filter(
            (field) => Number.parseInt(field.dataset.wizardStep ?? '0', 10) === this.currentStepValue,
        );

        return fields.every((field) => field.checkValidity());
    }

    refreshVisibility() {
        this.stepTargets.forEach((stepEl, index) => {
            stepEl.hidden = index + 1 !== this.currentStepValue;
        });

        this.fieldTargets.forEach((field) => {
            const step = Number.parseInt(field.dataset.wizardStep ?? '0', 10);
            const wrapper = field.closest('[data-wizard-field]');
            if (wrapper instanceof HTMLElement) {
                wrapper.hidden = step !== this.currentStepValue;
            }
        });

        this.element.dataset.formUiWizardCurrentStepValue = String(this.currentStepValue);
    }
}
