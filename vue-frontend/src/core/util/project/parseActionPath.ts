import {MenuItem} from "@/gql/gen";
import {urlParse} from "@/core/util/urlParse";

export interface ActionInfoBase {

}

export interface ActionInfoLocation extends ActionInfoBase {
    type: 'location'
    blank?: boolean
    path: string
}

export interface ActionInfoRouter extends ActionInfoBase {
    type: 'router'
    path: string
    name?: string
    replace?: boolean
    params: any
}

export interface ActionInfoStore extends ActionInfoBase {
    type: 'store'
    store: string
    path: string
    params?: any
}

export interface ActionInfoJs extends ActionInfoBase {
    type: 'js'
    code: string
}

export type ActionInfo = ActionInfoLocation | ActionInfoRouter | ActionInfoStore | ActionInfoJs

export function parseActionPath(path: string | null | undefined, menuItemParams: Partial<MenuItem> = {}) {

    if (!path)
        return null

    let action: ActionInfo | null = null

    let protocol: string | undefined = ''
    let pathUrl: string | undefined = ''

    if (path.match('://')) {
        const parts = path.split('://')
        protocol = parts[0]
        pathUrl = parts[1]
    } else {
        if (path.charAt(0) === '/') {
            protocol = 'to'
            pathUrl = path.substring(1)
        } else if (path.match(/^[a-z]+\:[^/]/)) {
            protocol = 'special'
            pathUrl = path
        }
    }


    if (protocol && pathUrl) {

        switch (protocol) {
            case 'to': {
                action = {
                    type: 'router',
                    path: '/' + pathUrl,
                    params: {}
                }
                break
            }
            case 'js': {
                action = {
                    type: 'js',
                    code: pathUrl,
                }
                break
            }
            case 'router': {
                const urlParsed = urlParse(pathUrl, ['_blank', '_replace'])
                if (!!urlParsed.specialParams._blank) {
                    action = {
                        type: 'location',
                        blank: true,
                        path: urlParsed.href
                    }
                } else {
                    action = {
                        type: 'router',
                        replace: !!urlParsed.specialParams._replace,
                        path: urlParsed.path,
                        params: {}
                    }
                }
                break
            }
            case 'store': {
                const urlParsed = urlParse(pathUrl)
                const [, store, storePath] = urlParsed.pathname.split('/')
                action = {
                    type: 'store',
                    store: store || '',
                    path: storePath || '',
                    params: urlParsed.query
                }
                break
            }
            case 'special':
                action = {
                    type: 'location',
                    blank: true,
                    path: pathUrl
                }
                break
            default: {
                const urlParsed = urlParse(path, ['_blank', '_replace'])
                action = {
                    type: 'location',
                    blank: !!urlParsed.specialParams._blank || !!menuItemParams.blank,
                    path: urlParsed.href
                }
                break
            }
        }
    }

    return action
}
