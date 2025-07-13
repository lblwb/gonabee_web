class State {
    constructor(initial = {}) {
        this._state = { ...initial };
        this._subs = new Map();
    }

    set(key, value) {
        if (this._state[key] === value) return;
        this._state[key] = value;
        // Оптимизация: обновляем только те подписки, которые зависят от ключа
        (this._subs.get(key) || []).forEach(fn => fn(value));
        // Обновляем глобальные подписки
        (this._subs.get('*') || []).forEach(fn => fn(this._state));
    }

    get(key) {
        return this._state[key];
    }

    toggle(key) {
        this.set(key, !this._state[key]);
    }

    subscribe(key, callback) {
        if (!this._subs.has(key)) this._subs.set(key, []);
        this._subs.get(key).push(callback);
        // Если это подписка на конкретное состояние, сразу вызываем функцию с актуальным значением
        if (key !== '*') callback(this._state[key]);
    }
}

function bindState(state) {
    const parseExpr = (expr, ctx) => {
        try {
            // Безопасный способ вычисления выражений
            const fn = new Function(...Object.keys(ctx), `return (${expr});`);
            return fn(...Object.values(ctx));
        } catch (e) {
            console.error('Ошибка в выражении:', expr, e);
            return undefined;
        }
    };

    const makeRandomAttr = () => {
        return 'data-' + Math.random().toString(36).substr(2, 6);
    };

    // Обработка data-bind
    document.querySelectorAll('[data-bind]').forEach(el => {
        const [expr, prop = 'text'] = el.dataset.bind.split(':');
        const update = ctx => {
            const value = parseExpr(expr, ctx);
            if (prop === 'hidden') el.hidden = !value;
            else if (prop === 'text') el.textContent = value ?? '';
            else if (prop.startsWith('class.')) {
                const className = prop.split('.')[1];
                el.classList.toggle(className, !!value);
            } else if (prop.startsWith('attr.')) {
                const attrName = prop.split('.')[1];
                if (value != null && value !== false) el.setAttribute(attrName, value);
                else el.removeAttribute(attrName);
            } else if (prop.startsWith('style.')) {
                const styleName = prop.split('.')[1];
                el.style[styleName] = value || '';
            }
        };
        // Подписка только на нужные ключи состояния
        state.subscribe('*', update);
        update(state._state);

        const randomAttr = makeRandomAttr();
        el.setAttribute(randomAttr, '');
        el.removeAttribute('data-bind');
    });

    // Обработка data-if
    document.querySelectorAll('[data-if]').forEach(el => {
        const expr = el.dataset.if;
        const update = ctx => {
            const show = parseExpr(expr, ctx);
            el.style.display = show ? '' : 'none';
        };
        // Подписка только на нужные ключи состояния
        state.subscribe('*', update);
        update(state._state);

        const randomAttr = makeRandomAttr();
        el.setAttribute(randomAttr, '');
        el.removeAttribute('data-if');
    });

    // Обработка data-class
    document.querySelectorAll('[data-class]').forEach(el => {
        const [className, expr] = el.dataset.class.split(':');
        const update = ctx => {
            const active = parseExpr(expr, ctx);
            el.classList.toggle(className, !!active);
        };
        // Подписка только на нужные ключи состояния
        state.subscribe('*', update);
        update(state._state);

        const randomAttr = makeRandomAttr();
        el.setAttribute(randomAttr, '');
        el.removeAttribute('data-class');
    });

    // Обработка data-style
    document.querySelectorAll('[data-style]').forEach(el => {
        const [styleName, value, expr] = el.dataset.style.split(':');
        const update = ctx => {
            const active = parseExpr(expr, ctx);
            el.style[styleName] = active ? value : '';
        };
        // Подписка только на нужные ключи состояния
        state.subscribe('*', update);
        update(state._state);

        const randomAttr = makeRandomAttr();
        el.setAttribute(randomAttr, '');
        el.removeAttribute('data-style');
    });

    // Обработка @click:toggle и аналогичных
    document.body.addEventListener('click', event => {
        const el = event.target;
        if (el.hasAttribute('@click:toggle')) {
            const key = el.getAttribute('@click:toggle');
            state.toggle(key);
            el.removeAttribute('@click:toggle');
        }

        if (el.hasAttribute('@click:set')) {
            const key = el.getAttribute('@click:set');
            let value = key === 'true' ? true : (key === 'false' ? false : key);
            state.set(key, value);
            el.removeAttribute('@click:set');
        }

        if (el.hasAttribute('@click:unset')) {
            const key = el.getAttribute('@click:unset');
            state.set(key, false);
            el.removeAttribute('@click:unset');
        }
    });

    // Обработка событий через @event
    document.body.addEventListener('click', event => {
        const el = event.target;
        Array.from(el.attributes).forEach(attr => {
            if (attr.name.startsWith('@')) {
                const eventType = attr.name.slice(1);
                const expr = attr.value;
                el.addEventListener(eventType, e => {
                    parseExpr(expr, { ...state._state, $event: e });
                });
                el.removeAttribute(attr.name);
            }
        });
    });

    // Обработка data-model
    document.querySelectorAll('[data-model]').forEach(el => {
        const key = el.dataset.model;
        const inputHandler = () => {
            if (el.type === 'checkbox') {
                state.set(key, el.checked);
            } else {
                state.set(key, el.value);
            }
        };
        el.addEventListener('input', inputHandler);
        el.addEventListener('change', inputHandler);

        const updater = value => {
            if (el.type === 'checkbox') {
                el.checked = !!value;
            } else {
                el.value = value ?? '';
            }
        };
        state.subscribe(key, updater);
        updater(state.get(key));

        const randomAttr = makeRandomAttr();
        el.setAttribute(randomAttr, '');
        el.removeAttribute('data-model');
    });

    // Обработка data-each
    document.querySelectorAll('[data-each]').forEach(templateEl => {
        const [itemName, arrName] = templateEl.dataset.each.split(' in ');
        const parent = templateEl.parentNode;
        const placeholder = document.createComment('each placeholder');
        parent.insertBefore(placeholder, templateEl);
        templateEl.remove();

        const render = ctx => {
            const arr = parseExpr(arrName, ctx) || [];
            // Удаляем старые элементы
            while (placeholder.nextSibling && placeholder.nextSibling._eachItem) {
                parent.removeChild(placeholder.nextSibling);
            }
            // Рендерим новые элементы
            arr.forEach((item, idx) => {
                const clone = templateEl.cloneNode(true);
                clone._eachItem = true;
                parent.insertBefore(clone, placeholder.nextSibling);
                const itemCtx = { ...ctx, [itemName]: item, [`${itemName}_index`]: idx };
                bindState({ _state: itemCtx, subscribe: (_, cb) => cb(itemCtx) });
            });
        };

        state.subscribe('*', render);
        render(state._state);
    });
}
