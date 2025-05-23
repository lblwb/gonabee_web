(function(g) {
    "use strict";

    // Конфигурация
    const c = {
        DEBOUNCE_DEFAULT_MS: 300,
        ID_PREFIX: "vibe-",
        BATCH_UPDATE_MS: 16,
        FETCH_CACHE_TTL: 6e4,
        DIRECTIVE_PREFIX: "vibe",
        EVENT_PREFIX: "@",
        TRANSITION_DURATION: 300,
        COMPONENT_BASE_PATH: "./components/",
        LOG_LEVEL: "debug" // Для диагностики
    };

    // Логирование
    const l = {
        log: (e, t, ...n) => {
            if (c.LOG_LEVEL !== "off") {
                const o = new Date().toISOString();
                console[e](`[SparkVibe ${o}][${e.toUpperCase()}] ${t}`, ...n);
            }
        },
        error: (e, ...t) => l.log("error", e, ...t),
        warn: (e, ...t) => l.log("warn", e, ...t),
        info: (e, ...t) => l.log("info", e, ...t),
        debug: (e, ...t) => l.log("debug", e, ...t)
    };

    // Хранилища
    const i = new Map(); // Подписчики
    const u = new Map(); // Кэш выражений
    const p = new Map(); // Кэш fetch
    const m = new Map(); // Компоненты
    const h = new Set(); // Пути для обновления
    const refMap = new Map(); // Хранилище data-ref -> { element, directive, value }

    // Генерация уникального ref
    function generateRef() {
        const chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        let randomStr = "";
        for (let i = 0; i < 10; i++) {
            randomStr += chars[Math.floor(Math.random() * chars.length)];
        }
        return `${c.ID_PREFIX}${randomStr}`;
    }

    // Уведомление подписчиков
    function notifySubscribers(path, value) {
        const subscribers = i.get(path);
        if (subscribers && subscribers.length > 0) {
            l.debug(`Notifying ${subscribers.length} subscribers for path ${path} with value:`, value);
            subscribers.forEach(subscriber => {
                try {
                    // console.log("sub", subscriber)
                    subscriber(value);
                } catch (err) {
                    l.error(`Subscriber error for path ${path}:`, err);
                }
            });
        } else {
            l.debug(`No subscribers found for path ${path}`);
        }
        // Уведомляем подписчиков для родительских путей
        const parts = path.split(".");
        for (let i = parts.length - 1; i > 0; i--) {
            const parentPath = parts.slice(0, i).join(".");
            const parentValue = f(g.states, parentPath);
            const parentSubscribers = i.get(parentPath);
            if (parentSubscribers && parentSubscribers.length > 0) {
                l.debug(`Notifying ${parentSubscribers.length} subscribers for parent path ${parentPath} with value:`, parentValue);
                parentSubscribers.forEach(subscriber => {
                    try {
                        subscriber(parentValue);
                    } catch (err) {
                        l.error(`Subscriber error for parent path ${parentPath}:`, err);
                    }
                });
            }
        }
    }

    // Реактивный Proxy
    function E(e, t = "") {
        return new Proxy(e, {
            set: (n, o, s) => {
                const path = `${t ? `${t}.` : ""}${o}`;
                n[o] = typeof s === "object" && s !== null ? E(s, path) : s;
                l.info(`Reactive update in store: ${path} =`, s);
                h.add(path);
                u.delete(path); // Очистка кэша
                l.debug(`Cleared cache for path: ${path}`);
                // Немедленное уведомление подписчиков
                const value = f(g.states, path);
                notifySubscribers(path, value);
                return true;
            },
            get: (n, o) => n[o]
        });
    }

    // Получение значения по пути
    function f(e, t) {
        try {
            if (!e || !t) {
                l.warn(`Invalid store or path: store=${e}, path=${t}`);
                return undefined;
            }
            const parts = t.split(".");
            if (!e[parts[0]]) {
                l.warn(`Store ${parts[0]} not found for path ${t}`);
                return undefined;
            }
            return parts.reduce((n, o) => (n && n[o] !== undefined ? n[o] : undefined), e);
        } catch (n) {
            l.error(`Error getting value by path ${t}:`, n);
            return undefined;
        }
    }

    // Установка значения по пути
    function v(e, t, n) {
        try {
            if (!e || !t) {
                l.error(`Invalid store or path: store=${e}, path=${t}`);
                return;
            }
            const o = t.split("."),
                s = o.pop();
            let r = e;
            for (const a of o) {
                if (!r[a]) {
                    r[a] = {};
                    l.debug(`Created intermediate store path: ${a}`);
                }
                r = r[a];
            }
            r[s] = n;
            l.info(`Set value by path: ${t} =`, n);
            h.add(t);
            u.delete(t); // Очистка кэша
            l.debug(`Cleared cache for path: ${t}`);
            // Немедленное уведомление подписчиков
            const value = f(g.states, t);
            notifySubscribers(t, value);
        } catch (o) {
            l.error(`Error setting value by path ${t}:`, o);
        }
    }

    // Подписка на изменения
    function S(e, t) {
        if (typeof e !== "string" || !e) {
            l.error(`Invalid path for subscription: ${e}`);
            return;
        }
        if (!i.has(e)) {
            i.set(e, []);
            l.debug(`Initialized subscriber array for path: ${e}`);
        }
        i.get(e).push(t);
        l.debug(`Subscribed to path: ${e}, total subscribers:`, i.get(e).length);
        // Инициализация пути в сторе, если он отсутствует
        if (f(g.states, e) === undefined) {
            l.warn(`Path ${e} not found in store, initializing with default value: false`);
            v(g.states, e, false); // Устанавливаем значение по умолчанию
        }
        // Вызываем обработчик с текущим значением
        const currentValue = f(g.states, e);
        l.debug(`Initial call for subscriber on path ${e}, value =`, currentValue);
        try {
            t(currentValue);
        } catch (err) {
            l.error(`Initial subscriber error for path ${e}:`, err);
        }
    }

    // Парсинг зависимостей
    function P(e) {
        const t = new Set();
        (e.match(/[a-zA-Z0-9_]+(?:\.[a-zA-Z0-9_]+)+/g) || []).forEach(n => {
            t.add(n);
        });
        if (!t.has(e)) t.add(e);
        l.debug(`Parsed dependencies for ${e}:`, Array.from(t));
        return { deps: t, evaluate: n => _(n, e) };
    }

    // Вычисление выражения
    function _(e, t) {
        try {
            const n = new Function("states", `with(states){return ${t};}`)(e);
            l.debug(`Evaluated expression ${t}:`, n);
            return n;
        } catch (n) {
            l.error(`Error evaluating expression ${t}:`, n);
            return undefined;
        }
    }

    // Подписка на выражение
    function C(e, t, ref) {
        const { deps } = P(e);
        l.debug(`Subscribing to expression ${e} with dependencies:`, Array.from(deps), `for ref:`, ref);
        deps.forEach(n => {
            S(n, () => {
                const o = _(g.states, e);
                l.debug(`Expression ${e} updated to:`, o, `for ref:`, ref);
                t(o);
            });
        });
        l.debug(`Subscribed to expression: ${e} for ref:`, ref);
        // Проверка текущего значения при подписке
        const initialValue = _(g.states, e);
        l.debug(`Initial evaluation of ${e}:`, initialValue, `for ref:`, ref);
        t(initialValue);
    }

    // Регистрация стора
    function k(e, t) {
        g.states || (g.states = E({}));
        if (g.states[e]) {
            l.warn(`Store ${e} already exists, merging data`);
            Object.assign(g.states[e], E(t, e));
        } else {
            g.states[e] = E(t, e);
        }

        l.info(`Registered store: ${e}`);
    }

    // Обновление списка в цикле
    function A(e, t, n, o, s) {
        const r = Array.from(e.children).filter(a => a.__vibeKey),
            w = new Map(),
            T = document.createDocumentFragment();
        n.forEach((a, I) => {
            const D = a.id || I;
            let x = r.find(L => L.__vibeKey === D);
            if (!x) {
                x = t.cloneNode(true);
                x.__vibeKey = D;
                O(x, a, I, o, s);
            }
            w.set(D, x);
            T.appendChild(x);
        });
        e.innerHTML = "";
        e.appendChild(T);
        l.debug(`Updated list for ${o}, items: ${n.length}`);
    }

    // Привязка данных в цикле
    function O(e, t, n, o, s) {
        const r = e.querySelectorAll(`[${c.DIRECTIVE_PREFIX}-bind]`);
        r.forEach(a => {
            let w = a.getAttribute(`${c.DIRECTIVE_PREFIX}-bind`);
            try {
                w = w
                    .replace(`{{${o}}}`, JSON.stringify(t))
                    .replace(`{{${s}}}`, n)
                    .replace(/{{([^}]+)}}/g, (T, x) => f(t, x.trim()) || "");
                a.textContent = eval(w);
                l.debug(`Bound data for expression: ${w}`);
            } catch (I) {
                l.error(`Error binding data for expression ${w}:`, I);
            }
        });
    }

    // Анимация перехода
    function M(e, t, n) {
        l.debug(`M called for ref ${e.dataset.ref}, show =`, t);
        const o = e.dataset.originalDisplay || (getComputedStyle(e).display !== "none" ? getComputedStyle(e).display : "block");
        e.dataset.originalDisplay = o;
        e.style.display = t ? o : "none";
        l.debug(`Set display to ${e.style.display} for ref ${e.dataset.ref}`);
        n();
    }

    // Директивы
    const R = {
        if: (e, t) => {
            const ref = e.dataset.ref || generateRef();
            e.dataset.ref = ref;
            refMap.set(ref, { element: e, directive: "if", value: t });
            l.debug(`Registered ref ${ref} for vibe-if with value ${t}`);
            const n = document.createComment(`${c.DIRECTIVE_PREFIX}-if`);
            e.parentNode.replaceChild(n, e);
            const o = s => {
                l.debug(`vibe-if updating for ${t}, value =`, s, `ref =`, ref);
                if (s && !e.parentNode) M(e, true, () => n.parentNode.insertBefore(e, n));
                else if (!s && e.parentNode) M(e, false, () => e.parentNode.removeChild(e));
            };
            const r = _(g.states, t);
            if (r) e.style.display = e.dataset.originalDisplay || (getComputedStyle(e).display !== "none" ? getComputedStyle(e).display : "block");
            o(r);
            C(t, o, ref);
            l.debug(`vibe-if initialized with ${t}: ${r} for ref ${ref}`);
        },
        show: (e, t) => {
            const ref = e.dataset.ref || generateRef();
            e.dataset.ref = ref;

            // g.invokeSubscribers(e);

            refMap.set(ref, { element: e, directive: "show", value: t });
            l.debug(`Registered ref ${ref} for vibe-show with value ${t}`);
            const n = o => {
                l.debug(`vibe-show updating for ${t}, value =`, o, `ref =`, ref);
                const el = refMap.get(ref)?.element;
                if (!el) {
                    l.error(`Element with ref ${ref} not found in refMap`);
                    return;
                }
                M(el, !!o, () => {}); // Приведение к boolean
            };
            const s = _(g.states, t);
            l.debug(`vibe-show initialized with ${t}: ${s} for ref ${ref}`);


            M(e, !!s, () => {});
            C(t, n, ref);
        },
        model: (e, t) => {
            const ref = e.dataset.ref || generateRef();
            e.dataset.ref = ref;
            refMap.set(ref, { element: e, directive: "model", value: t });
            l.debug(`Registered ref ${ref} for vibe-model with value ${t}`);
            const n = f(g.states, t);
            e.value = n || "";
            S(t, s => {
                if (e.value !== s) e.value = s;
            });
            e.addEventListener("input", () => {
                v(g.states, t, e.value);
                l.debug(`vibe-model updated: ${t} = ${e.value} for ref ${ref}`);
            });
            l.debug(`vibe-model initialized for ${t} for ref ${ref}`);
        },
        for: (e, t) => {
            const ref = e.dataset.ref || generateRef();
            e.dataset.ref = ref;
            refMap.set(ref, { element: e, directive: "for", value: t });
            l.debug(`Registered ref ${ref} for vibe-for with value ${t}`);
            const [n, o] = t.split(" in "),
                [s, r] = n.replace(/[()]/g, "").split(",").map(a => a.trim()),
                w = e.parentNode,
                T = e.cloneNode(true);
            e.remove();
            function x() {
                const L = f(g.states, o) || [];
                A(w, T, L, s, r);
            }
            S(o, x);
            x();
            l.debug(`vibe-for initialized for ${o} for ref ${ref}`);
        },
        value: (e, t) => {
            const ref = e.dataset.ref || generateRef();
            e.dataset.ref = ref;
            refMap.set(ref, { element: e, directive: "value", value: t });
            l.debug(`Registered ref ${ref} for vibe-value with value ${t}`);
            const n = o => (e.value = o || ""),
                s = f(g.states, t);
            n(s);
            S(t, n);
            l.debug(`vibe-value initialized for ${t} for ref ${ref}`);
        },
        bind: (e, t) => {
            const ref = e.dataset.ref || generateRef();
            e.dataset.ref = ref;
            refMap.set(ref, { element: e, directive: "bind", value: t });
            l.debug(`Registered ref ${ref} for vibe-bind with value ${t}`);
            const n = o => (e.textContent = o || ""),
                s = f(g.states, t);
            n(s);
            S(t, n);
            l.debug(`vibe-bind initialized for ${t} for ref ${ref}`);
        },
        class: (e, t) => {
            const ref = e.dataset.ref || generateRef();
            e.dataset.ref = ref;
            refMap.set(ref, { element: e, directive: "class", value: t });
            l.debug(`Registered ref ${ref} for vibe-class with value ${t}`);
            const [n, o] = t.split(":"),
                s = r => e.classList.toggle(n, r),
                w = f(g.states, o);
            s(w);
            S(o, s);
            l.debug(`vibe-class initialized for ${n}: ${o} for ref ${ref}`);
        },
        fetch: (e, t) => {
            const ref = e.dataset.ref || generateRef();
            e.dataset.ref = ref;
            refMap.set(ref, { element: e, directive: "fetch", value: t });
            l.debug(`Registered ref ${ref} for vibe-fetch with value ${t}`);
            const [n, o] = t.split(" to ");
            const s = async () => {
                try {
                    const r = p.get(n);
                    if (r && Date.now() - r.timestamp < c.FETCH_CACHE_TTL) {
                        v(g.states, o, r.data);
                        l.debug(`Fetched from cache: ${n}`);
                        return;
                    }
                    const a = await fetch(n);
                    if (!a.ok) throw new Error(`HTTP error: ${a.status}`);
                    const w = await a.json();
                    p.set(n, { data: w, timestamp: Date.now() });
                    v(g.states, o, w);
                    l.info(`Fetched data from ${n} to ${o}`);
                } catch (r) {
                    l.error(`Error fetching data from ${n}:`, r);
                }
            };
            s();
            e.addEventListener("click", s);
            l.debug(`vibe-fetch initialized for ${n} for ref ${ref}`);
        },
        once: (e, t) => {
            const ref = e.dataset.ref || generateRef();
            e.dataset.ref = ref;
            refMap.set(ref, { element: e, directive: "once", value: t });
            l.debug(`Registered ref ${ref} for vibe-once with value ${t}`);
            e.textContent = f(g.states, t) || "";
            l.debug(`vibe-once applied for ${t} for ref ${ref}`);
        },
        lazy: (e, t) => {
            const ref = e.dataset.ref || generateRef();
            e.dataset.ref = ref;
            refMap.set(ref, { element: e, directive: "lazy", value: t });
            l.debug(`Registered ref ${ref} for vibe-lazy with value ${t}`);
            const n = new IntersectionObserver(o => {
                o.forEach(s => {
                    if (s.isIntersecting) {
                        e.src = f(g.states, t) || "";
                        n.unobserve(e);
                        l.debug(`vibe-lazy loaded for ${t} for ref ${ref}`);
                    }
                });
            });
            n.observe(e);
            l.debug(`vibe-lazy initialized for ${t} for ref ${ref}`);
        },
        debounce: (e, t) => {
            const ref = e.dataset.ref || generateRef();
            e.dataset.ref = ref;
            refMap.set(ref, { element: e, directive: "debounce", value: t });
            l.debug(`Registered ref ${ref} for vibe-debounce with value ${t}`);
            const [n, o] = t.split(":"),
                s = parseInt(o, 10) || c.DEBOUNCE_DEFAULT_MS;
            let r;
            const w = f(g.states, n);
            e.value = w || "";
            S(n, T => {
                if (e.value !== T) e.value = T;
            });
            e.addEventListener("input", () => {
                clearTimeout(r);
                r = setTimeout(() => {
                    v(g.states, n, e.value);
                    l.debug(`vibe-debounce updated: ${n} = ${e.value} for ref ${ref}`);
                }, s);
            });
            l.debug(`vibe-debounce initialized for ${n}, delay: ${s}ms for ref ${ref}`);
        },
        on: (e, t) => {
            const ref = e.dataset.ref || generateRef();
            e.dataset.ref = ref;
            refMap.set(ref, { element: e, directive: "on", value: t });
            l.debug(`Registered ref ${ref} for vibe-on with value ${t}`);
            const [n, o] = t.split(":");
            e.addEventListener(n, s => {
                try {
                    l.debug(`Attempting to execute ${o} for event ${n}`);
                    const r = _(g.states, o);
                    if (typeof r === "function") {
                        r.call(f(g.states, o.split(".").slice(0, -1).join(".")), s);
                        l.info(`Event ${n} triggered: ${o} for ref ${ref}`);
                    } else if (typeof r !== "undefined") {
                        l.info(`Expression ${o} evaluated to:`, r);
                    } else {
                        l.error(`Handler for ${o} is not a function or valid expression for ref ${ref}`);
                    }
                } catch (r) {
                    l.error(`Error executing handler for ${o}:`, r);
                }
            });
            l.debug(`vibe-on initialized for ${n}: ${o} for ref ${ref}`);
        },
        component: (e, t) => {
            const ref = e.dataset.ref || generateRef();
            e.dataset.ref = ref;
            refMap.set(ref, { element: e, directive: "component", value: t });
            l.debug(`Registered ref ${ref} for vibe-component with value ${t}`);
            const n = m.get(t);
            if (!n) {
                l.warn(`Component ${t} not found`);
                return;
            }
            const o = document.createElement("div");
            o.innerHTML = n;
            e.parentNode.replaceChild(o, e);
            initBindings(o);
            l.info(`Component ${t} rendered for ref ${ref}`);
        },
        transition: (e, t) => {
            const ref = e.dataset.ref || generateRef();
            e.dataset.ref = ref;
            refMap.set(ref, { element: e, directive: "transition", value: t });
            l.debug(`Registered ref ${ref} for vibe-transition with value ${t}`);
            const n = o => {
                l.debug(`vibe-transition updating for ${t}, value =`, o, `ref =`, ref);
                M(e, o, () => {});
            };
            const s = _(g.states, t);
            l.debug(`vibe-transition initialized with ${t}: ${s} for ref ${ref}`);
            M(e, s, () => {});
            C(t, n, ref);
        }
    };

    // Компоненты
    const B = {};

    function F(e, t) {
        m.has(e) || m.set(e, t);
        B[e] = { template: t };
        l.info(`Registered component: ${e}`);
    }

    async function N(e, t) {
        if (m.has(e)) {
            B[e] = { template: m.get(e) };
            l.debug(`Component ${e} loaded from cache`);
            return;
        }
        try {
            const n = await fetch(`${c.COMPONENT_BASE_PATH}${t}`);
            if (!n.ok) throw new Error(`HTTP error: ${n.status}`);
            const o = await n.text();
            m.set(e, o);
            B[e] = { template: o };
            l.info(`Registered file component: ${e} from ${t}`);
        } catch (n) {
            l.error(`Error loading component ${e} from ${t}:`, n);
        }
    }

    function U(e, t) {
        R[e] = t;
        l.info(`Registered custom directive: ${e}`);
    }

    // Инициализация биндингов
    async function initBindings(e = document) {
        if (!e || !e.querySelectorAll) {
            l.error("Invalid context for initBindings:", e);
            return;
        }
        l.debug(`Starting initBindings on context:`, e);
        const promises = [];

        // Обработка директив
        Object.entries(R).forEach(([n, o]) => {
            const selector = `[${c.DIRECTIVE_PREFIX}-${n}]`;
            const s = e.querySelectorAll(selector);
            if (s.length === 0) l.debug(`No elements found for directive ${n} with selector ${selector}`);
            s.forEach(r => {
                promises.push(
                    new Promise(resolve => {
                        try {
                            const a = r.getAttribute(`${c.DIRECTIVE_PREFIX}-${n}`);
                            if (!a) {
                                l.warn(`Empty directive value for ${n}`);
                                resolve();
                                return;
                            }
                            r.dataset.ref = r.dataset.ref || generateRef();
                            l.debug(`Processing directive ${n} with value ${a} for ref ${r.dataset.ref}`);
                            o(r, a);
                            r.removeAttribute(`${c.DIRECTIVE_PREFIX}-${n}`);
                            l.debug(`Processed directive: ${c.DIRECTIVE_PREFIX}-${n} with value ${a} for ref ${r.dataset.ref}`);
                        } catch (a) {
                            l.error(`Error processing directive ${n}:`, a);
                        }
                        resolve();
                    })
                );
            });
        });

        // Обработка событий
        const allElements = e.querySelectorAll("*");
        const eventElements = Array.from(allElements).filter(el => {
            return Array.from(el.attributes).some(attr => attr.name.startsWith(c.EVENT_PREFIX));
        });
        if (eventElements.length === 0) l.debug(`No elements found for event directives`);
        eventElements.forEach(o => {
            promises.push(
                new Promise(resolve => {
                    try {
                        Array.from(o.attributes).forEach(s => {
                            if (s.name.startsWith(c.EVENT_PREFIX)) {
                                const r = s.name.slice(c.EVENT_PREFIX.length),
                                    a = s.value;
                                o.dataset.ref = o.dataset.ref || generateRef();
                                refMap.set(o.dataset.ref, { element: o, directive: "on", value: `${r}:${a}` });
                                l.debug(`Registered ref ${o.dataset.ref} for event ${r} with value ${a}`);
                                R.on(o, `${r}:${a}`);
                                o.removeAttribute(s.name);
                                l.debug(`Processed event directive: ${s.name} with value ${a} for ref ${o.dataset.ref}`);
                            }
                        });
                    } catch (s) {
                        l.error(`Error processing event directive:`, s);
                    }
                    resolve();
                })
            );
        });

        await Promise.all(promises);
        l.info("Bindings initialized");
    }

    // Безопасный вызов подписчиков вручную
    function invokeSubscribers(path) {
        const subscribers = i.get(path);
        if (!subscribers || subscribers.length === 0) {
            l.error(`No subscribers found for path: ${path}`);
            return false;
        }
        const currentValue = f(g.states, path);
        l.debug(`Invoking ${subscribers.length} subscribers for path ${path} with value:`, currentValue);
        subscribers.forEach(subscriber => {
            try {
                subscriber(currentValue);
            } catch (err) {
                l.error(`Subscriber error for path ${path}:`, err);
            }
        });
        return true;
    }

    // API фреймворка
    const SparkVibe = {
        configure: e => {
            Object.assign(c, e);
            l.info("Configuration updated:", e);
        },
        registerStore: k,
        registerComponent: F,
        registerFileComponent: N,
        registerDirective: U,
        setValueByPath: (t, n) => v(g.states, t, n),
        getValueByPath: (t) => f(g.states, t),
        invokeSubscribers: invokeSubscribers,
        initBindings: initBindings,
        init: () => {
            if (document.readyState === "complete" || document.readyState === "interactive") {
                l.info("DOM already loaded, initializing bindings immediately");
                initBindings();
            } else {
                document.addEventListener("DOMContentLoaded", () => {
                    l.info("DOMContentLoaded fired, initializing bindings");
                    initBindings();
                });
            }
        }
    };

    g.SparkVibe = SparkVibe;
    g.SparkVibe._refMap = refMap; // Для диагностики
    g.SparkVibe._subscribers = i; // Для диагностики
    l.info("SparkVibe framework initialized");
})(window);