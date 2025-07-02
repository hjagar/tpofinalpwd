(function (window) {
    const state = {};
    const scope = {};

    function setState(key, value) {
        state[key] = value;
        updateBindings(key);
    }

    function bindData(el, key) {
        el.value = state[key] || '';
        el.addEventListener('input', (e) => {
            setState(key, e.target.value);
        });
    }

    function bindText(el, key) {
        const value = state[key];
        el.textContent = (value === undefined || value === null) ? '' : value;
    }

    function bindIf(el, key) {
        const placeholder = document.createComment(`r-if="${key}"`);
        if (!state[key]) {
            el.parentNode.replaceChild(placeholder, el);
            el._r_if_placeholder = placeholder;
        }
        el._r_if_key = key;
    }

    function bindShow(el, key) {
        el.style.display = state[key] ? '' : 'none';
        el._r_show_key = key;
    }

    function bindClass(el, binding) {
        const classMap = binding.split(',').map(c => {
            const [cls, cond] = c.split(':').map(s => s.trim());
            return { cls, cond };
        });
        el._r_class_bindings = classMap;

        classMap.forEach(({ cls, cond }) => {
            if (state[cond]) el.classList.add(cls);
            else el.classList.remove(cls);
        });
    }

    function bindClick(el, code) {
        el.addEventListener('click', () => {
            (new Function('state', 'scope', 'setState', code))(state, scope, setState);
        });
    }

    function bindFor(el, key, itemName) {
        const parent = el.parentNode;
        const placeholder = document.createComment(`r-for="${itemName} in ${key}"`);
        parent.insertBefore(placeholder, el);
        parent.removeChild(el);

        el._r_for_template = el;
        el._r_for_key = key;
        el._r_for_item = itemName;
        el._r_for_placeholder = placeholder;

        renderFor(el);
    }

    function renderFor(el) {
        const key = el._r_for_key;
        const itemName = el._r_for_item;
        const placeholder = el._r_for_placeholder;
        const parent = placeholder.parentNode;

        if (el._r_for_instances) {
            el._r_for_instances.forEach(node => parent.removeChild(node));
        }

        const list = state[key];
        if (!Array.isArray(list)) return;

        el._r_for_instances = list.map(item => {
            const clone = el._r_for_template.cloneNode(true);
            clone.innerHTML = clone.innerHTML.replace(/\{\{\s*(\w+)\s*\}\}/g, (_, v) => {
                return v === itemName ? item : '';
            });
            parent.insertBefore(clone, placeholder);
            return clone;
        });
    }

    function updateBindings(key) {
        document.querySelectorAll(`[r-text="${key}"]`).forEach(el => {
            el.textContent = state[key];
        });

        document.querySelectorAll(`[r-model="${key}"]`).forEach(el => {
            if (el.value !== state[key]) el.value = state[key];
        });

        document.querySelectorAll(`[r-if="${key}"]`).forEach(el => {
            const show = !!state[key];
            if (!show && !el._r_if_placeholder) {
                const placeholder = document.createComment(`r-if="${key}"`);
                el.parentNode.replaceChild(placeholder, el);
                el._r_if_placeholder = placeholder;
            } else if (show && el._r_if_placeholder) {
                el._r_if_placeholder.parentNode.replaceChild(el, el._r_if_placeholder);
                delete el._r_if_placeholder;
            }
        });

        document.querySelectorAll(`[r-show="${key}"]`).forEach(el => {
            el.style.display = state[key] ? '' : 'none';
        });

        document.querySelectorAll('[r-class]').forEach(el => {
            if (!el._r_class_bindings) return;
            el._r_class_bindings.forEach(({ cls, cond }) => {
                if (cond !== key) return;
                if (state[cond]) el.classList.add(cls);
                else el.classList.remove(cls);
            });
        });

        document.querySelectorAll('[r-for]').forEach(el => {
            if (el._r_for_key === key) renderFor(el);
        });
    }

    function initReactive(config = {}) {
        // Escanea el DOM y aplica bindings
        document.querySelectorAll('[r-model]').forEach(el => {
            const key = el.getAttribute('r-model');
            if (!(key in state)) state[key] = el.value || '';
            bindData(el, key);
        });

        document.querySelectorAll('[r-text]').forEach(el => {
            const key = el.getAttribute('r-text');
            bindText(el, key);
        });

        document.querySelectorAll('[r-click]').forEach(el => {
            const code = el.getAttribute('r-click');
            bindClick(el, code);
        });

        document.querySelectorAll('[r-if]').forEach(el => {
            const key = el.getAttribute('r-if');
            bindIf(el, key);
        });

        document.querySelectorAll('[r-show]').forEach(el => {
            const key = el.getAttribute('r-show');
            bindShow(el, key);
        });

        document.querySelectorAll('[r-class]').forEach(el => {
            const binding = el.getAttribute('r-class');
            bindClass(el, binding);
        });

        document.querySelectorAll('[r-for]').forEach(el => {
            const exp = el.getAttribute('r-for');
            const match = exp.match(/^(\w+)\s+in\s+(\w+)$/);
            if (!match) return console.error('Sintaxis inválida en r-for:', exp);
            const [, itemName, key] = match;
            bindFor(el, key, itemName);
        });

        // Si tiene config.state, aplicar valores iniciales con setState
        if (config.state) {
            Object.keys(config.state).forEach(key => {
                setState(key, config.state[key]);
            });
        }

        // Si tiene config.scope, extender el scope
        if (config.scope) {
            Object.assign(scope, config.scope);
        }
    }

    window.reactive = {
        state,
        scope,
        setState,
        init: initReactive
    };
})(window);
