export function scrollTo(
    element: HTMLElement | null,
    offset: number = 0,
    options: ScrollToOptions = {},
    container: HTMLElement | null | string | boolean = null,
): void {
    if (!element) return;

    const defaultOptions: ScrollToOptions = {behavior: "smooth"};
    const scrollOptions: ScrollToOptions = {...defaultOptions, ...options};

    let containerElement: HTMLElement | null = null

    if (container) {
        if (container instanceof HTMLElement) {
            containerElement = container
        } else if (typeof container === 'string') {
            containerElement = element.closest(container)
        } else if (typeof container === 'boolean') {
            containerElement = element.closest('.scroll')
        }
    }

    if (containerElement) {
        const elementPosition = element.offsetTop - containerElement.offsetTop;
        containerElement.scrollTo({
            top: elementPosition - offset,
            ...scrollOptions,
        });
    } else {
        const elementPosition = element.getBoundingClientRect().top + window.scrollY;
        window.scrollTo({
            top: elementPosition - offset,
            ...scrollOptions,
        });
    }
}
