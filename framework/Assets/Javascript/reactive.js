"use strict";
(function (window) {
    const version = '1.0';
    const state = {};
    const scope = {};

    function initState(key, value) {
        state[key] = value;
    }

    function setState(key, value) {
        state[key] = value;
        updateBindings(key);
    }

    function getValue(input) {
        let value = input.value;

        if (input.type === 'number') {
            value = Number(value);
        }

        return value;
    }

    function buildSafeEvaluator(expr) {
        const props = Array.from(new Set(
            expr.match(/[a-zA-Z_][a-zA-Z0-9_]*/g) || []
        ));
        const blacklist = ['true', 'false', 'null', 'Math', 'return', 'if', 'else', 'for', 'let', 'const', 'var'];
        const vars = props.filter(p => !blacklist.includes(p) && isNaN(p));
        const fn = new Function(...vars, `return ${expr}`);

        return { fn, vars };
    }

    function bindData(el, key) {
        el.value = state[key] || '';
        el.addEventListener('input', (e) => {
            const value = getValue(el);
            setState(key, value);
            const onChange = el.getAttribute('r-onchange');

            if (onChange) {
                const fn = new Function('state', 'scope', 'setState', onChange);
                fn(state, scope, setState);
            }
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

    function bindShow(el, expr) {
        const { fn, vars } = buildSafeEvaluator(expr);
        el._r_show_eval = () => {
            const args = vars.map(v => state[v]);
            return fn(...args);
        };
    }

    function showElement(el) {
        const value = el._r_show_eval();
        el.style.display = value ? '' : 'none';
    }

    function bindClass(el, binding) {
        const classMap = binding.split(',').map(c => {
            const [cls, cond] = c.split(':').map(s => s.trim());
            return { cls, cond };
        });
        el._r_class_bindings = classMap;

        classMap.forEach(({ cls, cond }) => {
            if (state[cond]) {
                el.classList.add(cls);
            }
            else {
                el.classList.remove(cls);
            }
        });
    }

    function bindClick(el, code) {
        el.addEventListener('click', () => {
            (new Function('state', 'scope', 'setState', code))(state, scope, setState);
        });
    }

    function bindAttr(el, binding, item, itemName) {
        const attrPairs = binding.split(',').map(p => p.trim());
        attrPairs.forEach(pair => {
            const [attr, expr] = pair.split(':').map(s => s.trim());
            if (item && expr.startsWith(itemName + '.')) {
                const localExpr = expr.slice(itemName.length + 1);
                const { fn, vars } = buildSafeEvaluator(localExpr);
                const evalFn = () => {
                    const args = vars.map(v => item[v]);
                    return fn(...args);
                };
                el.setAttribute(attr, evalFn());
                if (!el._r_attr_binds) el._r_attr_binds = [];
                el._r_attr_binds.push({ attr, evalFn, item });
            } else {
                el.setAttribute(attr, state[expr] || '');
                if (!el._r_attr_binds) el._r_attr_binds = [];
                el._r_attr_binds.push({ attr, key: expr });
            }
        });
    }

    const forRegistry = [];

    function bindFor(el, key, itemName) {
        const parent = el.parentNode;
        const placeholder = document.createComment(`r-for="${itemName} in ${key}"`);
        parent.insertBefore(placeholder, el);
        parent.removeChild(el);
        el._r_for_template = el;
        el._r_for_key = key;
        el._r_for_item = itemName;
        el._r_for_placeholder = placeholder;

        if (!forRegistry.includes(el)) {
            forRegistry.push(el);
        }

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
        if (!Array.isArray(list)) {
            el._r_for_instances = [];
            return;
        }

        el._r_for_instances = list.map(item => {
            const clone = el._r_for_template.cloneNode(true);

            clone.querySelectorAll('[r-text]').forEach(span => {
                const expr = span.getAttribute('r-text');
                if (expr.startsWith(itemName + '.')) {
                    const localExpr = expr.slice(itemName.length + 1);
                    const { fn, vars } = buildSafeEvaluator(localExpr);
                    span._r_for_eval = (item) => {
                        const args = vars.map(v => item[v]);
                        return fn(...args);
                    };
                    span.textContent = span._r_for_eval(item);
                    span._r_for_item = item;
                }
            });

            clone.querySelectorAll('[r-click]').forEach(btn => {
                const code = btn.getAttribute('r-click');
                btn.addEventListener('click', () => {
                    const fn = new Function('state', 'scope', itemName, 'setState', code);
                    fn(state, scope, item, setState);
                });
            });

            clone.querySelectorAll('[r-model]').forEach(input => {
                const model = input.getAttribute('r-model');
                if (model.startsWith(itemName + '.')) {
                    const prop = model.split('.')[1];
                    input.value = item[prop];
                    input.addEventListener('input', e => {
                        let value = e.target.value;
                        if (input.type === 'number') value = Number(value);
                        item[prop] = value;

                        const onChange = input.getAttribute('r-onchange');
                        if (onChange) {
                            const fn = new Function('state', 'scope', itemName, 'setState', onChange);
                            fn(state, scope, item, setState);
                        }

                        updateItemBindings();
                    });
                }
            });

            clone.querySelectorAll('[r-attr]').forEach(el => {
                const binding = el.getAttribute('r-attr');
                bindAttr(el, binding, item, itemName);
            });

            parent.insertBefore(clone, placeholder);
            return clone;
        });
    }

    function updateBindings(key) {
        document.querySelectorAll(`[r-text="${key}"]`).forEach(el => {
            el.textContent = state[key];
        });

        document.querySelectorAll('[r-text]').forEach(span => {
            if (span._r_for_eval && span._r_for_item) {
                span.textContent = span._r_for_eval(span._r_for_item);
            }
        });

        document.querySelectorAll('[r-show]').forEach(el => {
            const expr = el.getAttribute('r-show');
            if (!el._r_show_eval) {
                const { fn, vars } = buildSafeEvaluator(expr);
                el._r_show_eval = () => {
                    const args = vars.map(v => state[v]);
                    return fn(...args);
                };
            }
            const value = el._r_show_eval();
            el.style.display = value ? '' : 'none';
        });

        document.querySelectorAll('[r-if]').forEach(el => {
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

        document.querySelectorAll('[r-class]').forEach(el => {
            if (!el._r_class_bindings) return;
            el._r_class_bindings.forEach(({ cls, cond }) => {
                if (cond !== key) return;
                if (state[cond]) el.classList.add(cls);
                else el.classList.remove(cls);
            });
        });

        document.querySelectorAll('[r-attr]').forEach(el => {
            if (el._r_attr_binds) {
                el._r_attr_binds.forEach(bind => {
                    if (bind.evalFn && bind.item) {
                        el.setAttribute(bind.attr, bind.evalFn());
                    } else if (bind.key === key) {
                        el.setAttribute(bind.attr, state[bind.key] || '');
                    }
                });
            }
        });

        // document.querySelectorAll('[r-for]').forEach(el => {
        //     if (!el._r_for_key) {
        //         const exp = el.getAttribute('r-for');
        //         const match = exp.match(/^(\w+)\s+in\s+(\w+)$/);
        //         if (!match) return console.error('Sintaxis inválida en r-for:', exp);
        //         const [, itemName, k] = match;
        //         bindFor(el, k, itemName);
        //     }
        //     if (el._r_for_key === key) renderFor(el);
        // });

        forRegistry.forEach(el => {
            if (el._r_for_key === key) renderFor(el);
        });
    }

    function updateItemBindings() {
        document.querySelectorAll('[r-text]').forEach(span => {
            if (span._r_for_eval && span._r_for_item) {
                span.textContent = span._r_for_eval(span._r_for_item);
            }
        });

        document.querySelectorAll('[r-attr]').forEach(el => {
            if (el._r_attr_binds) {
                el._r_attr_binds.forEach(bind => {
                    if (bind.evalFn && bind.item) {
                        el.setAttribute(bind.attr, bind.evalFn());
                    } else if (bind.key) {
                        el.setAttribute(bind.attr, state[bind.key] || '');
                    }
                });
            }
        });
    }

    function initReactive(config = {}) {
        // Si tiene config.state, aplicar valores iniciales con setState
        if (config.state) {
            Object.keys(config.state).forEach(key => {
                initState(key, config.state[key]);
            });
        }

        document.querySelectorAll('[r-model]').forEach(el => {
            const key = el.getAttribute('r-model');
            if (!(key in state)) state[key] = el.value || '';
            bindData(el, key);
        });

        document.querySelectorAll('[r-text]').forEach(el => {
            const key = el.getAttribute('r-text');
            if (!(key in state)) state[key] = '';
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
            const expr = el.getAttribute('r-show');
            bindShow(el, expr);
        });

        document.querySelectorAll('[r-class]').forEach(el => {
            const binding = el.getAttribute('r-class');
            bindClass(el, binding);
        });

        document.querySelectorAll('[r-attr]').forEach(el => {
            const binding = el.getAttribute('r-attr');
            bindAttr(el, binding);
        });

        document.querySelectorAll('[r-for]').forEach(el => {
            if (!el._r_for_key) {
                const exp = el.getAttribute('r-for');
                const match = exp.match(/^(\w+)\s+in\s+(\w+)$/);
                if (!match) return console.error('Sintaxis inválida en r-for:', exp);
                const [, itemName, key] = match;
                bindFor(el, key, itemName);
            }
        });

        // Si tiene config.scope, extender el scope
        if (config.scope) {
            Object.assign(scope, config.scope);
        }
    }

    //document.addEventListener('DOMContentLoaded', initReactive);

    window.reactive = {
        version,
        state,
        scope,
        setState,
        init: initReactive,
        updateItemBindings
    };
})(window);
