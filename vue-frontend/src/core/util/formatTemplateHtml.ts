export default function formatTemplateHtml(html: string) {
    if (html && (typeof html === 'string')) {
        html = html.replace(/\{\{/g, '<')
        html = html.replace(/\}\}/g, '>')
    } else {
        html = ''
    }
    return '<div>' + html + '</div>'
}
