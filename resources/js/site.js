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

/*
 * Tableau des programmes de la page Admission.
 *
 * Deux filtres qui se combinent : un cycle (toujours un seul) et un pôle
 * (« tous » ou un seul). Les lignes sont toutes dans la page ; on ne fait que
 * les masquer, sans rechargement.
 */
function initProgrammesAdmission() {
    const section = document.querySelector('[data-programmes]');

    if (! section) {
        return;
    }

    const ongletsCycle = Array.from(section.querySelectorAll('[data-cycle]'));
    const ongletsPole = Array.from(section.querySelectorAll('[data-pole]'));
    const lignes = Array.from(section.querySelectorAll('.adm-ligne'));
    const vide = section.querySelector('[data-programmes-vide]');

    if (ongletsCycle.length === 0 || lignes.length === 0) {
        return;
    }

    const choisi = (boutons) => boutons.find((b) => b.classList.contains('is-active')) || boutons[0];

    const appliquer = () => {
        const cycle = choisi(ongletsCycle).dataset.cycle;
        const pole = ongletsPole.length > 0 ? choisi(ongletsPole).dataset.pole : 'all';

        let visibles = 0;

        lignes.forEach((ligne) => {
            const sesCycles = (ligne.dataset.cycles || '').split(' ').filter(Boolean);
            const sonPole = ligne.dataset.pole || '';

            const garde = sesCycles.includes(cycle) && (pole === 'all' || sonPole === pole);

            ligne.hidden = ! garde;

            if (garde) {
                visibles += 1;
            }
        });

        if (vide) {
            vide.hidden = visibles > 0;
        }
    };

    const brancher = (boutons) => {
        boutons.forEach((bouton) => {
            bouton.addEventListener('click', () => {
                boutons.forEach((autre) => {
                    const actif = autre === bouton;

                    autre.classList.toggle('is-active', actif);
                    // La couleur seule ne dit pas lequel est retenu.
                    autre.setAttribute('aria-pressed', actif ? 'true' : 'false');
                });

                appliquer();
            });
        });
    };

    brancher(ongletsCycle);
    brancher(ongletsPole);

    appliquer();
}

document.addEventListener('DOMContentLoaded', initProgrammesAdmission);

/*
 * Vérification du formulaire de contact, avant l'envoi.
 *
 * Les règles reprennent mot pour mot celles de StoreContactMessageRequest. Le
 * serveur reste seul juge : ce script peut ne pas tourner, et un envoi forgé
 * ne passe pas par lui. Il évite seulement l'aller-retour, et surtout il dit
 * la contrainte avant la saisie plutôt qu'après.
 */
function initValidationContact() {
    const formulaire = document.querySelector('[data-contact]');

    if (! formulaire) {
        return;
    }

    const MINIMUM_MESSAGE = 20;

    const regles = {
        name: (valeur) => {
            if (valeur === '') return 'Le nom est obligatoire.';
            if (valeur.length > 120) return 'Le nom ne peut pas dépasser 120 caractères.';

            return '';
        },
        email: (valeur) => {
            if (valeur === '') return 'L’adresse e-mail est obligatoire.';
            // Vérification de forme seulement : seul le serveur tranche.
            if (! /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(valeur)) return 'Cette adresse e-mail n’est pas valide.';
            if (valeur.length > 180) return 'L’adresse e-mail ne peut pas dépasser 180 caractères.';

            return '';
        },
        phone: (valeur) => (valeur.length > 40 ? 'Le téléphone ne peut pas dépasser 40 caractères.' : ''),
        subject: (valeur) => (valeur.length > 180 ? 'Le sujet ne peut pas dépasser 180 caractères.' : ''),
        message: (valeur) => {
            if (valeur === '') return 'Le message est obligatoire.';
            if (valeur.length < MINIMUM_MESSAGE) return 'Merci de détailler votre demande en 20 caractères minimum.';
            if (valeur.length > 5000) return 'Le message ne peut pas dépasser 5000 caractères.';

            return '';
        },
    };

    const resume = formulaire.querySelector('[data-contact-resume]');
    const compteur = formulaire.querySelector('[data-contact-compteur]');

    const champDe = (nom) => formulaire.querySelector(`[name="${nom}"]`);
    const avisDe = (nom) => formulaire.querySelector(`[data-erreur="${nom}"]`);

    const afficher = (nom, faute) => {
        const champ = champDe(nom);
        const avis = avisDe(nom);

        if (! champ || ! avis) {
            return;
        }

        avis.textContent = faute;
        avis.hidden = faute === '';

        if (faute === '') {
            champ.removeAttribute('aria-invalid');
        } else {
            champ.setAttribute('aria-invalid', 'true');
        }
    };

    const verifier = (nom) => {
        const champ = champDe(nom);

        if (! champ) {
            return '';
        }

        const faute = regles[nom](champ.value.trim());

        afficher(nom, faute);

        return faute;
    };

    const majCompteur = () => {
        const champ = champDe('message');

        if (! compteur || ! champ) {
            return;
        }

        const reste = MINIMUM_MESSAGE - champ.value.trim().length;

        compteur.textContent = reste > 0
            ? `Encore ${reste} caractère${reste > 1 ? 's' : ''} pour atteindre le minimum.`
            : 'Longueur suffisante.';
    };

    Object.keys(regles).forEach((nom) => {
        const champ = champDe(nom);

        if (! champ) {
            return;
        }

        // Au premier passage on ne juge qu'à la sortie du champ : signaler une
        // adresse incomplète dès la première lettre serait du harcelement.
        champ.addEventListener('blur', () => verifier(nom));

        champ.addEventListener('input', () => {
            if (champ.hasAttribute('aria-invalid')) {
                verifier(nom);
            }

            if (nom === 'message') {
                majCompteur();
            }
        });
    });

    formulaire.addEventListener('submit', (evenement) => {
        const fautifs = Object.keys(regles).filter((nom) => verifier(nom) !== '');

        if (fautifs.length === 0) {
            if (resume) {
                resume.hidden = true;
            }

            return;
        }

        evenement.preventDefault();

        if (resume) {
            resume.textContent = fautifs.length === 1
                ? 'Un champ demande une correction avant l’envoi.'
                : `${fautifs.length} champs demandent une correction avant l’envoi.`;
            resume.hidden = false;
        }

        // Le premier champ fautif reprend la main, sinon la correction se
        // cherche à l'aveugle sur un formulaire long.
        champDe(fautifs[0])?.focus();
    });
}

document.addEventListener('DOMContentLoaded', initValidationContact);

/*
 * Groupes de cycles de la page Filières.
 *
 * Le filtre par pôle du gabarit masque les fiches une à une. Sans ce
 * complément, un cycle dont toutes les fiches sont masquées garderait son
 * titre et son compte au-dessus du vide.
 */
function initGroupesFilieres() {
    const groupes = Array.from(document.querySelectorAll('[data-groupe-cycle]'));

    if (groupes.length === 0) {
        return;
    }

    const vide = document.querySelector('[data-filieres-vide]');
    const filtres = document.querySelector('.filter-portfolio');

    const recompter = () => {
        let visiblesEnTout = 0;

        groupes.forEach((groupe) => {
            const fiches = Array.from(groupe.querySelectorAll('.mg-portfolio-item'));

            // Le script du gabarit joue sur l'affichage, pas sur une classe
            // qu'on pourrait lire : on mesure donc la boîte elle-même.
            const visibles = fiches.filter((fiche) => fiche.offsetParent !== null);

            groupe.hidden = visibles.length === 0;
            visiblesEnTout += visibles.length;

            const compte = groupe.querySelector('[data-groupe-compte]');

            if (compte) {
                compte.textContent = `${visibles.length} filière${visibles.length > 1 ? 's' : ''}`;
            }
        });

        if (vide) {
            vide.hidden = visiblesEnTout > 0;
        }
    };

    if (filtres) {
        filtres.addEventListener('click', (evenement) => {
            if (evenement.target.closest('.filter-btn')) {
                // Après le gestionnaire du gabarit, qui vient de masquer les fiches.
                window.setTimeout(recompter, 50);
            }
        });
    }

    recompter();
}

document.addEventListener('DOMContentLoaded', initGroupesFilieres);

/*
 * Bouton « copier le lien » d'une actualité.
 *
 * Instagram et TikTok n'offrent aucune adresse de partage web : copier le
 * lien est ce qui s'en approche le plus. Le bouton reste utile même si le
 * presse-papiers est refusé — il le dit alors au lieu de ne rien faire.
 */
function initCopierLien() {
    const boutons = document.querySelectorAll('[data-copier-lien]');

    boutons.forEach((bouton) => {
        const etat = bouton.querySelector('[data-copier-etat]');

        const annoncer = (message) => {
            if (etat) {
                etat.textContent = message;
                // Vidé à nouveau : sinon un second clic n'annonce plus rien,
                // le texte n'ayant pas changé.
                window.setTimeout(() => { etat.textContent = ''; }, 4000);
            }

            bouton.classList.add('est-copie');
            window.setTimeout(() => bouton.classList.remove('est-copie'), 2000);
        };

        bouton.addEventListener('click', async () => {
            const lien = bouton.dataset.copierLien;

            try {
                await navigator.clipboard.writeText(lien);
                annoncer('Lien copié.');
            } catch (erreur) {
                annoncer('Copie impossible : ' + lien);
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', initCopierLien);
