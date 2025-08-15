
export function handleHotkeys(handler: (hotkey: string) => void) {

    const isInputElement = (element: Element): boolean => {
        const tagName = element.tagName.toLowerCase();
        const editable = (element as HTMLElement).isContentEditable;

        return editable ||
            tagName === 'input' ||
            tagName === 'textarea' ||
            tagName === 'select';
    };

    const handleKeyDown = (event: KeyboardEvent) => {
        const activeElement = document.activeElement;

        if (activeElement && isInputElement(activeElement)) {
            return;
        }

        const comboParts = [
            event.ctrlKey ? 'Ctrl' : null,
            event.shiftKey ? 'Shift' : null,
            event.altKey ? 'Alt' : null,
            event.metaKey ? 'Meta' : null,
            event.key.length > 1 ? event.key : event.key.toUpperCase(),
        ].filter(Boolean) as string[];

        const combo = comboParts.join('+');
        handler(combo)
    };

    window.addEventListener('keydown', handleKeyDown);
}
