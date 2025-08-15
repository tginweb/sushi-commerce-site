import {computed, type ComputedRef, inject, type InjectionKey, isRef, provide, reactive, ref, type Ref} from "vue";
import type {MenuItem} from "@/gql/gen";
import extractActionsSchema, {ActionsGenerator} from "@/core/util/project/extractActionsSchema";

// Базовый интерфейс для маппинга вкладок и их режимов
export interface ViewModeMapping {
    [tab: string]: string;
}

// Тип для свойств вкладки
export interface TabOptions<Tab extends keyof TMap, TMap extends ViewModeMapping> {
    title: string;
    icon?: string;
    hidden?: boolean | ComputedRef<boolean>; // Динамическое скрытие
    order?: number;
    actions?: ActionsGenerator;
    views: {
        [Mode in TMap[Tab]]: ViewOptions<Tab, TMap, Mode>;
    };
    onLeave?: (context: {
        from: { tab: keyof TMap; mode: TMap[keyof TMap] };
        to: { tab: keyof TMap; mode: TMap[keyof TMap] };
    }) => boolean | Promise<boolean> | void;
    onEnter?: (context: {
        prev: { tab: keyof TMap; mode: TMap[keyof TMap] };
        current: { tab: keyof TMap; mode: TMap[keyof TMap] };
    }) => void;
}

// Тип для свойств режима просмотра
export interface ViewOptions<Tab extends keyof TMap, TMap extends ViewModeMapping, Mode extends TMap[Tab]> {
    title?: string;
    hidden?: boolean | ComputedRef<boolean>; // Динамическое скрытие
    backView?: TMap[Tab] | GenerateCombinedType<TMap, '.'>;
    backCallback?: () => void;
    actions?: ActionsGenerator;

    // События жизненного цикла
    onBeforeActivate?: (context: {
        from: { tab: keyof TMap; mode: TMap[keyof TMap] };
        to: { tab: keyof TMap; mode: TMap[keyof TMap] };
    }) => boolean | Promise<boolean> | void;

    onAfterActivate?: (context: {
        prev: { tab: keyof TMap; mode: TMap[keyof TMap] };
        current: { tab: keyof TMap; mode: TMap[keyof TMap] };
    }) => void;
}

// Общий тип для структуры вкладок
export type TabbedStructure<TMap extends ViewModeMapping> = {
    [Tab in keyof TMap]: TabOptions<Tab, TMap>;
};

// Тип для генерации комбинированных строк (profile.view)
export type GenerateCombinedType<T extends Record<string, string>, S extends string = '.'> = {
    [K in keyof T]: `${K & string}${S}${T[K] & string}`
}[keyof T];

// Типизированный контекст для provide/inject
export interface TabbedViewContext<TMap extends ViewModeMapping> {
    viewTab: Ref<keyof TMap>;
    viewMode: Ref<TMap[keyof TMap]>;
    viewName: ComputedRef<GenerateCombinedType<TMap, '.'>>;
    gotoView: (
        tab?: keyof TMap | null,
        mode?: TMap[keyof TMap] | null,
        skipMiddleware?: boolean
    ) => Promise<boolean>;
    currentTab: ComputedRef<TabOptions<keyof TMap, TMap> | undefined>;
    currentView: ComputedRef<ViewOptions<keyof TMap, TMap, any> | undefined>;
    onBack: ComputedRef<(() => void) | undefined>;
    viewTitle: ComputedRef<string | null>;
    viewActions: ComputedRef<Partial<MenuItem>[]>;
    tabs: ComputedRef<TabbedStructure<TMap>>;
    filteredTabs: ComputedRef<Array<{
        id: keyof TMap;
        title: string;
        icon?: string;
        hidden: boolean;
        order: number;
    }>>;
}

// Ключ для инъекции контекста
export function createTabbedViewContextKey<TMap extends ViewModeMapping>(
    name: string
): InjectionKey<TabbedViewContext<TMap>> {
    return Symbol(name) as InjectionKey<TabbedViewContext<TMap>>;
}

// Класс ошибки для отмены навигации
class NavigationAborted extends Error {
    constructor(message: string) {
        super(message);
        this.name = 'NavigationAborted';
    }
}

export function useTabbedViews<TMap extends ViewModeMapping>(params: {
    viewTabInitial: keyof TMap;
    viewModeInitial: TMap[keyof TMap];
    tabs: TabbedStructure<TMap> | ComputedRef<TabbedStructure<TMap>>;
    contextKey?: InjectionKey<TabbedViewContext<TMap>>;
    middleware?: Array<
        (
            from: { tab: keyof TMap; mode: TMap[keyof TMap] },
            to: { tab: keyof TMap; mode: TMap[keyof TMap] },
            next: (confirm?: boolean) => void
        ) => void
    >;
}) {
    // Инициализация состояния
    const viewTab = ref<keyof TMap>(params.viewTabInitial) as Ref<keyof TMap>;
    const viewMode = ref<TMap[keyof TMap]>(params.viewModeInitial) as Ref<TMap[keyof TMap]>;

    // Хранилище последних активных режимов для каждого таба
    const lastViewModes = reactive<Partial<Record<keyof TMap, TMap[keyof TMap]>>>({
        [params.viewTabInitial]: params.viewModeInitial
    } as Partial<Record<keyof TMap, TMap[keyof TMap]>>);

    // Middleware
    const middlewareStack = ref(params.middleware || []);

    // Динамическая структура вкладок
    const tabsSource = computed(() =>
        isRef(params.tabs) ? params.tabs.value : params.tabs
    );


    // Фильтрация вкладок и режимов
    const tabs = computed(() => {
        const result = {} as TabbedStructure<TMap>;
        const source = tabsSource.value;

        for (const tabKey in source) {
            const tab = source[tabKey as keyof TMap];

            // Проверяем скрыта ли вкладка
            const isTabHidden = isRef(tab.hidden)
                ? tab.hidden.value
                : tab.hidden ?? false;

            if (isTabHidden) continue;

            // Фильтруем режимы просмотра
            const filteredViews = {} as any;
            for (const modeKey in tab.views) {
                const view = tab.views[modeKey as keyof typeof tab.views];

                // Проверяем скрыт ли режим
                const isViewHidden = isRef(view.hidden)
                    ? view.hidden.value
                    : view.hidden ?? false;

                if (!isViewHidden) {
                    filteredViews[modeKey] = view;
                }
            }

            // Добавляем вкладку только если есть доступные режимы
            if (Object.keys(filteredViews).length > 0) {
                result[tabKey as keyof TMap] = {
                    ...tab,
                    views: filteredViews
                };
            }
        }

        return result;
    });

    // Отфильтрованные вкладки для навигации
    const filteredTabs = computed(() => {
        return Object.entries(tabs.value)
            .map(([id, tab]) => ({
                id: id as keyof TMap,
                title: tab.title,
                icon: tab.icon,
                hidden: false, // Уже отфильтрованы скрытые
                order: tab.order ?? 0
            }))
            .sort((a, b) => a.order - b.order);
    });

    // Имя текущего вида (profile.view)
    const viewName = computed(() =>
        //`${viewTab.value}.${viewMode.value}` as GenerateCombinedType<TMap, '.'>
        `${String(viewTab.value)}.${String(viewMode.value)}` as GenerateCombinedType<TMap, '.'>
    );

    // Получение текущей вкладки
    const currentTab = computed(() => {
        const opt = tabs.value[viewTab.value]
        if (!opt)
            return undefined;
        return {
            ...opt,
            actionsSchema: extractActionsSchema(opt.actions)
        }
    });

    // Получение текущего режима
    const currentView = computed(() => {
        const tab = currentTab.value;
        if (!tab) return undefined;

        const viewConfig = tab.views[viewMode.value as keyof typeof tab.views];
        if (!viewConfig) return undefined;

        return {
            ...viewConfig,
            actionsSchema: extractActionsSchema(viewConfig.actions)
        };
    });

    // Внутренняя функция перехода
    const _internalGotoView = async (
        options: { tab?: keyof TMap; mode?: TMap[keyof TMap] },
        skipMiddleware = false
    ): Promise<boolean> => {
        if (!options.tab && !options.mode) return false;

        const from = {tab: viewTab.value, mode: viewMode.value};
        const to = {
            tab: options.tab ?? viewTab.value,
            mode: options.mode ?? viewMode.value
        };

        // Если переход в тот же вид - выходим
        if (from.tab === to.tab && from.mode === to.mode) return false;

        // Получаем конфигурации видов
        const fromTab = tabs.value[from.tab];
        const currentView = fromTab?.views[from.mode as keyof typeof fromTab.views];

        const toTab = tabs.value[to.tab];
        const targetView = toTab?.views[to.mode as keyof typeof toTab.views];

        // Проверяем доступность целевого вида
        if (!targetView) {
            console.error(`View ${String(to.tab)}.${to.mode} is not available`);
            return false;
        }

        if (fromTab.onLeave) {
            try {
                const allowLeave = await fromTab.onLeave({from, to});
                if (allowLeave === false) {
                    throw new NavigationAborted('Transition cancelled by current tab');
                }
            } catch (error) {
                if (error instanceof NavigationAborted) {
                    console.warn(error.message);
                    return false;
                }
                throw error;
            }
        }

        if (toTab.onEnter) {
            toTab.onEnter({
                prev: from,
                current: to
            });
        }

        // 1. Вызываем onBeforeActivate для текущего вида
        if (currentView?.onBeforeActivate) {
            try {
                const allowLeave = await currentView.onBeforeActivate({from, to});
                if (allowLeave === false) {
                    throw new NavigationAborted('Transition cancelled by current view');
                }
            } catch (error) {
                if (error instanceof NavigationAborted) {
                    console.warn(error.message);
                    return false;
                }
                throw error;
            }
        }

        // 2. Вызываем onBeforeActivate для целевого вида
        if (targetView.onBeforeActivate) {
            try {
                const allowEnter = await targetView.onBeforeActivate({from, to});
                if (allowEnter === false) {
                    throw new NavigationAborted('Transition cancelled by target view');
                }
            } catch (error) {
                if (error instanceof NavigationAborted) {
                    console.warn(error.message);
                    return false;
                }
                throw error;
            }
        }

        // 3. Выполняем middleware (если не пропущены)
        if (!skipMiddleware && middlewareStack.value.length > 0) {
            const middlewareResult = await new Promise<boolean>((resolve) => {
                let index = 0;
                const next = (confirm = true) => {
                    if (!confirm) {
                        resolve(false);
                        return;
                    }

                    index++;
                    if (index < middlewareStack.value.length) {
                        const mw = middlewareStack.value[index];
                        if (mw) mw(from, to, next);
                    } else {
                        resolve(true);
                    }
                };

                const firstMw = middlewareStack.value[0];
                if (firstMw) firstMw(from, to, next);
            });

            if (!middlewareResult) return false;
        }

        // Обновляем состояние
        viewTab.value = to.tab;
        viewMode.value = to.mode as TMap[typeof to.tab];

        // Обновляем последний активный режим для таба
        //@ts-ignore
        lastViewModes[to.tab] = to.mode;

        // 4. Вызываем onAfterActivate для предыдущего вида
        if (currentView?.onAfterActivate) {
            try {
                currentView.onAfterActivate({
                    prev: from,
                    current: to
                });
            } catch (error) {
                console.error('Error in current view onAfterActivate', error);
            }
        }

        // 5. Вызываем onAfterActivate для нового вида
        if (targetView.onAfterActivate) {
            try {
                targetView.onAfterActivate({
                    prev: from,
                    current: to
                });
            } catch (error) {
                console.error('Error in target view onAfterActivate', error);
            }
        }

        return true;
    };

    // Публичная функция перехода
    const gotoView = (
        tab?: keyof TMap | null,
        mode?: TMap[keyof TMap] | null,
        skipMiddleware = false
    ): Promise<boolean> => {
        // Определяем параметры перехода
        let transitionOptions: {
            tab?: keyof TMap;
            mode?: TMap[keyof TMap];
        } = {};

        // Случай 1: Указаны и tab, и mode
        if (tab !== undefined && tab !== null && mode !== undefined && mode !== null) {
            transitionOptions = {tab, mode};
        }
        // Случай 2: Указан только tab
        else if (tab !== undefined && tab !== null) {
            const tabConfig = tabs.value[tab];
            if (!tabConfig) {
                console.error(`Tab ${String(tab)} not found`);
                return Promise.resolve(false);
            }

            // Используем последний активный режим для таба или режим по умолчанию
            //@ts-ignore
            const targetMode = lastViewModes[tab]
                ?? (Object.keys(tabConfig.views).find(m => m === 'list') as TMap[keyof TMap] | undefined)
                ?? (Object.keys(tabConfig.views)[0] as TMap[keyof TMap] | undefined);

            if (!targetMode) {
                console.error(`No available views for tab ${String(tab)}`);
                return Promise.resolve(false);
            }

            transitionOptions = {tab, mode: targetMode};
        }
        // Случай 3: Указан только mode
        else if (mode !== undefined && mode !== null) {
            transitionOptions = {mode};
        }

        // Выполняем переход если есть параметры
        if (Object.keys(transitionOptions).length > 0) {
            return _internalGotoView(transitionOptions, skipMiddleware);
        }

        return Promise.resolve(false);
    };

    const viewTitle = computed(() => currentView.value?.title || currentTab.value?.title || '')
    const viewActions = computed(() => {
        return [
            ...(currentTab.value?.actionsSchema?.actions || []),
            ...(currentView.value?.actionsSchema?.actions || []),
        ]
    })

    // Функция для обработки кнопки "Назад"
    const onBack = computed(() => {
        const current = currentView.value;
        if (!current || !(current.backView || current.backCallback)) {
            return undefined;
        }

        return () => {
            // Обработка перехода по backView
            if (current.backView) {
                if (typeof current.backView === 'string' && current.backView.includes('.')) {
                    // Полный путь (profile.list)
                    const [tab, mode] = current.backView.split('.') as [keyof TMap, TMap[keyof TMap]];
                    gotoView(tab, mode);
                } else {
                    // Режим в текущей вкладке
                    gotoView(null, current.backView as TMap[keyof TMap]);
                }
            }

            // Пользовательский колбэк
            current.backCallback?.();
        };
    });

    // Создание контекста
    const contextValue: TabbedViewContext<TMap> = {
        viewTab,
        viewMode,
        viewName,
        gotoView,
        currentTab,
        currentView,
        onBack,
        tabs,
        filteredTabs,
        viewActions,
        viewTitle
    };

    // Provide контекста если нужно
    if (params.contextKey) {
        provide(params.contextKey, contextValue);
    }

    return contextValue;
}

// Хук для использования контекста в дочерних компонентах
export function useTabbedViewContext<TMap extends ViewModeMapping>(
    contextKey: InjectionKey<TabbedViewContext<TMap>>
): TabbedViewContext<TMap> {
    const context = inject(contextKey);

    if (!context) {
        throw new Error(
            `useTabbedViewContext must be used within a component that provides ${contextKey.description}`
        );
    }

    return context;
}
