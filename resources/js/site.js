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
