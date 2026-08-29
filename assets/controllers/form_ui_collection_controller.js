import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['list', 'prototype', 'addButton'];
    static values = {
        prototypeName: String,
        allowAdd: { type: Boolean, default: true },
        allowDelete: { type: Boolean, default: true },
    };

    addRow(event) {
        event.preventDefault();
        if (!this.allowAddValue || !this.hasPrototypeTarget) {
            return;
        }

        const index = this.listTarget.children.length;
        const html = this.prototypeTarget.innerHTML.replace(
            new RegExp(this.prototypeNameValue, 'g'),
            String(index),
        );
        this.listTarget.insertAdjacentHTML('beforeend', html);
        this.syncAddButton();
    }

    removeRow(event) {
        event.preventDefault();
        if (!this.allowDeleteValue) {
            return;
        }

        const row = event.target.closest('[data-collection-row-id]');
        if (row instanceof HTMLElement) {
            row.remove();
            this.syncAddButton();
        }
    }

    syncAddButton() {
        if (!this.hasAddButtonTarget) {
            return;
        }

        this.addButtonTarget.hidden = !this.allowAddValue;
    }
}
