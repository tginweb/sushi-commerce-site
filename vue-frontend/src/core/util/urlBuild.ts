import {ParsedPathExtended} from "@/core/util/urlParse";
import isEmptyObject from "@/core/util/isEmptyObject";

export function urlBuild(info: ParsedPathExtended) {
    const search = info.search ? info.search : (!isEmptyObject(info.query) ? (new URLSearchParams(info.query)).toString() : '')
    if (info.host) {
        return info.protocol + '://' + info.host + info.pathname + (search ? '?' + search : '')
    } else {
        return info.pathname + (search ? '?' + search : '')
    }
}

