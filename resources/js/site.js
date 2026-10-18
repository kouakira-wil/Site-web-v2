/**
 * Public site behaviour that used to come from Bootstrap's JavaScript bundle.
 *
 * The template's own interactions (magic cursor, sliders, scroll animations)
 * still come from assets/js/plugin.js and assets/js/main.js. The only Bootstrap
 * component this template relied on was `collapse`, reimplemented here without
 * a framework.
 *
 * Panels animate through an inline pixel height, which always beats the
 * stylesheet, and the height is released to `auto` once the transition ends so
 * the panel keeps reflowing when its content or the viewport changes.
 */

const OPEN = 'is-open';
const DURATION = 350;

/**
 * Runs `done` on the next transitionend, or after a timeout.
 *
 * The fallback matters: if the transition never starts — reduced motion, a
 * hidden ancestor, a zero-height panel — transitionend never fires, and
 * without it the panel would stay stuck mid-animation.
 */
function onTransitionEnd(el, done) {
    let finished = false;

    const finish = () => {
        if (finished) {
            return;
        }
        finished = true;
        el.removeEventListener('transitionend', handler);
        done();
    };

    const handler = (event) => {
        if (event.target === el && event.propertyName === 'height') {
            finish();
        }
    };

    el.addEventListener('transitionend', handler);
    setTimeout(finish, DURATION + 80);
}

/**
 * Animates a panel to its open or closed state.
 *
 * The class is toggled up front and stays the single source of truth; the
 * inline height only exists for the duration of the transition, because
 * `height: auto` cannot be animated. Releasing it at the end hands the panel
 * back to the stylesheet.
 */
function setPanel(panel, open) {
    const from = panel.getBoundingClientRect().height;

    panel.style.height = `${from}px`;
    panel.classList.toggle(OPEN, open);

    const to = open ? panel.scrollHeight : 0;

    // Force a reflow so the browser registers the starting height. Doing this
    // synchronously rather than in requestAnimationFrame keeps the panel
    // working in background tabs, where rAF callbacks are throttled.
    void panel.offsetHeight;

    panel.style.height = `${to}px`;

    onTransitionEnd(panel, () => {
        panel.style.height = '';
    });
}

const openPanel = (panel) => setPanel(panel, true);
const closePanel = (panel) => setPanel(panel, false);

function setExpanded(trigger, expanded) {
    trigger.setAttribute('aria-expanded', String(expanded));
    // The template styles the closed state through `.collapsed`.
    trigger.classList.toggle('collapsed', !expanded);
}

function triggerFor(panel) {
    return document.querySelector(`[data-at-collapse-target="#${CSS.escape(panel.id)}"]`);
}

function closeSiblings(panel) {
    const parentSelector = panel.getAttribute('data-at-collapse-parent');
    const parent = parentSelector ? document.querySelector(parentSelector) : null;

    if (!parent) {
        return;
    }

    parent.querySelectorAll(`.at-collapse.${OPEN}`).forEach((sibling) => {
        if (sibling === panel) {
            return;
        }

        closePanel(sibling);

        const siblingTrigger = triggerFor(sibling);
        if (siblingTrigger) {
            setExpanded(siblingTrigger, false);
        }
    });
}

function initCollapse() {
    document.querySelectorAll('[data-at-collapse-target]').forEach((trigger) => {
        const selector = trigger.getAttribute('data-at-collapse-target');
        const panel = selector ? document.querySelector(selector) : null;

        if (!panel) {
            return;
        }

        setExpanded(trigger, panel.classList.contains(OPEN));

        trigger.addEventListener('click', (event) => {
            event.preventDefault();

            if (panel.classList.contains(OPEN)) {
                closePanel(panel);
                setExpanded(trigger, false);

                return;
            }

            closeSiblings(panel);
            openPanel(panel);
            setExpanded(trigger, true);
        });
    });
}

document.addEventListener('DOMContentLoaded', initCollapse);

/**
 * Défilement de la seconde ligne du titre du bandeau.
 *
 * Les variantes sont empilées dans une même cellule de grille : le bloc prend
 * la hauteur de la plus grande et ne saute pas d'une variante à l'autre.
 */
function initHeroRotator() {
    const rotators = document.querySelectorAll('[data-hero-rotator]');

    if (rotators.length === 0) {
        return;
    }

    // Mouvement réduit : on laisse la première variante affichée, sans rotation.
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    const INTERVALLE = 3200;

    rotators.forEach((rotator) => {
        const items = Array.from(rotator.querySelectorAll('.hero-rotator__item'));

        if (items.length < 2) {
            return;
        }

        let index = 0;
        let timer = null;

        const avancer = () => {
            items[index].classList.remove('is-active');
            index = (index + 1) % items.length;
            items[index].classList.add('is-active');
        };

        const demarrer = () => {
            if (timer === null) {
                timer = window.setInterval(avancer, INTERVALLE);
            }
        };

        const arreter = () => {
            window.clearInterval(timer);
            timer = null;
        };

        // Inutile de faire tourner une animation dans un onglet masqué.
        document.addEventListener('visibilitychange', () => {
            document.hidden ? arreter() : demarrer();
        });

        demarrer();
    });
}

document.addEventListener('DOMContentLoaded', initHeroRotator);

/**
 * Accessibilité des filtres par rubrique.
 *
 * `initPortfolioFilter()` du gabarit bascule la classe `active` mais laisse
 * `aria-pressed` figé : un lecteur d'écran annoncerait toujours « Toutes »
 * comme sélectionné. On reflète l'état réel après chaque clic.
 */
function initFiltreAccessible() {
    const groupes = document.querySelectorAll('.filter-portfolio');

    groupes.forEach((groupe) => {
        const boutons = Array.from(groupe.querySelectorAll('.filter-btn'));

        if (boutons.length === 0) {
            return;
        }

        const refleter = () => {
            boutons.forEach((bouton) => {
                bouton.setAttribute('aria-pressed', bouton.classList.contains('active') ? 'true' : 'false');
            });

            // Aucun bouton actif : le gabarit a rétabli « tout afficher ».
            if (! boutons.some((b) => b.classList.contains('active'))) {
                boutons[0].setAttribute('aria-pressed', 'true');
            }
        };

        groupe.addEventListener('click', (evenement) => {
            if (evenement.target.closest('.filter-btn')) {
                // Après le gestionnaire du gabarit, qui vient de changer les classes.
                window.setTimeout(refleter, 0);
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', initFiltreAccessible);

/*
 * Préchargeur.
 *
 * Le gabarit Orisa portait les styles de `.page-loader` et `.bar` mais laissait
 * l'animation en commentaire dans main.js, sans marquage pour la nourrir. Elle
 * est reprise ici, au lieu de toucher au fichier du gabarit.
 *
 * Les volets se rétractent vers le haut en cascade, puis le voile est retiré du
 * DOM. Un voile seulement transparent continuerait d'intercepter les clics.
 */
function initPrechargeur() {
    const voile = document.querySelector('[data-prechargeur]');

    if (! voile) {
        return;
    }

    // Une fois parti, il ne revient pas : le filet de sécurité en ligne peut
    // l'avoir devancé, et GSAP peut manquer si le bundle du gabarit a échoué.
    const effacer = () => voile.remove();

    const sobre = window.matchMedia('(prefers-reduced-motion: reduce)');

    if (sobre.matches || typeof window.gsap === 'undefined') {
        effacer();

        return;
    }

    let parti = false;

    const partir = () => {
        // `load` et le délai de secours peuvent se déclencher tous les deux :
        // la seconde animation jouerait sur un voile déjà retiré.
        if (parti) {
            return;
        }

        parti = true;

        const volets = voile.querySelectorAll('.bar');
        const logo = voile.querySelector('.page-loader-logo');

        const sortie = window.gsap.timeline({ onComplete: effacer });

        if (logo) {
            sortie.to(logo, { opacity: 0, scale: 1.2, duration: 0.4, ease: 'power2.in' });
        }

        sortie.to(volets, {
            height: 0,
            duration: 0.9,
            ease: 'power4.inOut',
            stagger: { amount: 0.4 },
        }, logo ? '-=0.2' : 0);
    };

    /*
     * `load` attend les images : sur une page lourde, l'attente serait longue.
     * On part au plus tôt des deux, le filet en ligne couvrant le reste.
     */
    if (document.readyState === 'complete') {
        partir();
    } else {
        window.addEventListener('load', partir, { once: true });
        window.setTimeout(partir, 2500);
    }
}

document.addEventListener('DOMContentLoaded', initPrechargeur);
