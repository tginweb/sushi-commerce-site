export function openUrl(url: string, blank: boolean | null = false) {
    if (blank) {
        const win = window.open(url, '_blank')
        if (win)
            win.focus()
    } else {
        window.location.replace(url)
    }
}
