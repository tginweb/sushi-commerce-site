import { Directive, DirectiveBinding, nextTick, VNode } from 'vue';

interface TextareaSelectConfig {
    minHeight?: number;
    rows?: number;
    resize?: 'both' | 'horizontal' | 'vertical' | 'none';
}

interface TextareaSelectElement extends HTMLElement {
    _textareaSelect?: {
        textarea: HTMLTextAreaElement | null;
        originalInput: HTMLInputElement | null;
        observer: MutationObserver | null;
        cleanup: () => void;
    };
}

const getOriginalInput = (el: HTMLElement): HTMLInputElement | null => {
    return el.querySelector('.q-field__input, .q-select__input');
};

const createTextarea = (config: TextareaSelectConfig): HTMLTextAreaElement => {
    const textarea = document.createElement('textarea');
    textarea.className = 'q-textarea-select';
    textarea.style.minHeight = `${config.minHeight ?? 42}px`;
    textarea.style.resize = config.resize ?? 'vertical';
    textarea.rows = config.rows ?? 3;
    textarea.style.boxSizing = 'border-box';
    textarea.style.width = '100%';
    return textarea;
};

const syncAttributes = (src: HTMLElement, dest: HTMLElement) => {
    const attributes = [
        'placeholder', 'disabled', 'readonly', 'tabindex', 'id',
        'autocomplete', 'autocapitalize', 'autocorrect', 'spellcheck',
        'aria-label', 'aria-labelledby', 'aria-describedby'
    ];

    attributes.forEach(attr => {
        const value = src.getAttribute(attr);
        if (value !== null) {
            dest.setAttribute(attr, value);
        } else {
            dest.removeAttribute(attr);
        }
    });
};

const TextareaSelectDirective: Directive = {
    mounted(el: TextareaSelectElement, binding: DirectiveBinding<TextareaSelectConfig>, vnode: VNode) {
        console.log('CCCCC')
        const config = binding.value ?? {};
        let originalInput = getOriginalInput(el);
        let textarea: HTMLTextAreaElement | null = null;
        let observer: MutationObserver | null = null;

        if (!originalInput) {
            console.warn('[v-textarea-select] Original input not found');
            return;
        }

        // Создаем и вставляем textarea
        const setupTextarea = () => {
            textarea = createTextarea(config);
            syncAttributes(originalInput!, textarea);
            textarea.value = originalInput!.value;

            // Позиционируем textarea
            textarea.style.position = 'absolute';
            textarea.style.top = '0';
            textarea.style.left = '0';
            textarea.style.height = '100%';

            // Скрываем оригинальный input
            originalInput!.style.opacity = '0';
            originalInput!.style.position = 'absolute';
            originalInput!.style.zIndex = '-1';
            originalInput!.parentElement!.style.position = 'relative';

            originalInput!.parentElement!.appendChild(textarea);
        };

        // Обработчики событий
        const handleTextareaInput = () => {
            if (textarea && originalInput) {
                originalInput.value = textarea.value;
                const event = new Event('input', { bubbles: true });
                originalInput.dispatchEvent(event);
            }
        };

        const handleTextareaKeydown = (e: KeyboardEvent) => {
            if (!originalInput) return;

            // Shift+Enter - перенос строки
            if (e.shiftKey && e.key === 'Enter') return;

            // Эмулируем событие на оригинальном input
            const cloneEvent = new KeyboardEvent(e.type, {
                key: e.key,
                code: e.code,
                shiftKey: e.shiftKey,
                ctrlKey: e.ctrlKey,
                altKey: e.altKey,
                metaKey: e.metaKey,
                bubbles: true,
                cancelable: true
            });

            originalInput.dispatchEvent(cloneEvent);

            // Блокируем Enter для предотвращения отправки форм
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
            }
        };

        const handleTextareaFocus = () => {
            if (originalInput) {
                originalInput.focus();
            }
        };

        const handleTextareaBlur = () => {
            if (originalInput) {
                originalInput.blur();
            }
        };

        // Наблюдатель за изменениями атрибутов
        const setupObserver = () => {
            observer = new MutationObserver((mutations) => {
                console.log('dd')
                for (const mutation of mutations) {
                    if (mutation.type === 'attributes' && textarea) {
                        syncAttributes(originalInput!, textarea);
                    }
                }
            });

            if (originalInput) {
                observer.observe(originalInput, {
                    attributes: true,
                    attributeFilter: [
                        'placeholder', 'disabled', 'readonly', 'value',
                        'aria-label', 'aria-labelledby', 'aria-describedby'
                    ]
                });
            }
        };

        // Функция очистки
        const cleanup = () => {
            if (textarea) {
                textarea.removeEventListener('input', handleTextareaInput);
                textarea.removeEventListener('keydown', handleTextareaKeydown);
                textarea.removeEventListener('focus', handleTextareaFocus);
                textarea.removeEventListener('blur', handleTextareaBlur);
                textarea.remove();
            }

            if (originalInput) {
                originalInput.style.opacity = '';
                originalInput.style.position = '';
                originalInput.style.zIndex = '';
                if (originalInput.parentElement) {
                    originalInput.parentElement.style.position = '';
                }
            }

            if (observer) {
                observer.disconnect();
            }
        };

        // Инициализация
        setupTextarea();
        setupObserver();

        // Добавляем обработчики
        if (textarea) {
            textarea.addEventListener('input', handleTextareaInput);
            textarea.addEventListener('keydown', handleTextareaKeydown);
            textarea.addEventListener('focus', handleTextareaFocus);
            textarea.addEventListener('blur', handleTextareaBlur);
        }

        // Сохраняем ссылки для последующей очистки
        el._textareaSelect = {
            textarea,
            originalInput,
            observer,
            cleanup
        };
    },

    updated(el: TextareaSelectElement, binding: DirectiveBinding<TextareaSelectConfig>) {
        const instance = el._textareaSelect;
        if (!instance || !instance.textarea || !instance.originalInput) return;

        // Синхронизация значения
        if (instance.textarea.value !== instance.originalInput.value) {
            instance.textarea.value = instance.originalInput.value;
        }

        // Синхронизация атрибутов
        syncAttributes(instance.originalInput, instance.textarea);

        // Обновление конфигурации
        const config = binding.value ?? {};
        if (config.minHeight) {
            instance.textarea.style.minHeight = `${config.minHeight}px`;
        }
        if (config.resize) {
            instance.textarea.style.resize = config.resize;
        }
        if (config.rows) {
            instance.textarea.rows = config.rows;
        }
    },

    beforeUnmount(el: TextareaSelectElement) {
        const instance = el._textareaSelect;
        if (instance) {
            instance.cleanup();
            delete el._textareaSelect;
        }
    }
};

export default TextareaSelectDirective;
