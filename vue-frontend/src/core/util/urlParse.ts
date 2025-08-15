import parseUrl from "parse-url";
import isEmptyObject from "@/core/util/isEmptyObject";
import {ParsedPath} from "parse-path";
import {urlBuild} from "@/core/util/urlBuild";

const fakeHostUrl = 'http://www.test.ru'

export type ParsedPathExtended = ParsedPath & {
    specialParams: Record<string, string>
    path: string
}

export function urlParse(str: string, specialParamKeys: string[] = []) {

    let _str = str

    let isFakeHost = false

    if (!str.match(/^http/)) {
        if (str[0] !== '/') {
            _str = '/' + str
        } else {
            _str = str
        }
        _str = fakeHostUrl + _str
        isFakeHost = true
    }

    const res: ParsedPathExtended = {
        ...parseUrl(_str),
        specialParams: {},
        path: '',
    }

    if (isFakeHost) {
        res.host = ''
        res.resource = ''
        res.href = res.href.replace(fakeHostUrl, '')
    }

    res.specialParams = specialParamKeys.reduce<Record<string, any>>((map, paramName) => {
        if (res.query[paramName]) {
            map[paramName] = res.query[paramName]
            delete res.query[paramName]
        }
        return map
    }, {})

    if (!isEmptyObject(res.specialParams)) {
        const paramsWithoutSpecial = new URLSearchParams(res.query)
        res.search = paramsWithoutSpecial.toString()
        res.href = urlBuild(res)
    }

    res.path = res.pathname + (res.search ? '?' + res.search : '')

    return res
}

