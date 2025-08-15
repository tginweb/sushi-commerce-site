import {RouteLocationNormalized} from "vue-router";
import {AppRouteMeta} from "@/core/app/types";
import {merge} from "lodash-es";

export function collectMeta(to: RouteLocationNormalized): AppRouteMeta {
    const result = merge({}, ...to.matched.map(record => record.meta))
    return result
}
