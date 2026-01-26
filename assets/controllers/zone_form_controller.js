import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['spaceType', 'kc', 'ru', 'seuilBas', 'seuilHaut'];

    static spaceTypeData = {
        'gazon_ornemental': { kc: 0.85, ru: 25, seuilBas: 10, seuilHaut: 21 },
        'pelouse_sportive': { kc: 0.95, ru: 45, seuilBas: 18, seuilHaut: 38 },
        'massif_fleurs_annuelles': { kc: 1.00, ru: 40, seuilBas: 16, seuilHaut: 34 },
        'massif_vivaces': { kc: 0.75, ru: 70, seuilBas: 28, seuilHaut: 60 },
        'massif_arbustif': { kc: 0.65, ru: 90, seuilBas: 36, seuilHaut: 77 },
        'haie_persistante': { kc: 0.60, ru: 110, seuilBas: 44, seuilHaut: 94 },
        'prairie_fleurie': { kc: 0.55, ru: 80, seuilBas: 32, seuilHaut: 68 },
        'arbres_alignement': { kc: 0.70, ru: 160, seuilBas: 64, seuilHaut: 136 },
    };

    connect() {
        this.spaceTypeTarget.addEventListener('change', this.updateFields.bind(this));

        if (this.spaceTypeTarget.value) {
            this.updateFields();
        }
    }

    updateFields() {
        const selectedType = this.spaceTypeTarget.value;
        const data = this.constructor.spaceTypeData[selectedType];

        if (data) {
            this.kcTarget.value = data.kc;
            this.ruTarget.value = data.ru;
            this.seuilBasTarget.value = data.seuilBas;
            this.seuilHautTarget.value = data.seuilHaut;
        } else {
            this.kcTarget.value = '';
            this.ruTarget.value = '';
            this.seuilBasTarget.value = '';
            this.seuilHautTarget.value = '';
        }
    }
}
