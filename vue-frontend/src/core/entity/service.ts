import entityTypesRaw from "@/backend-data/entity-types";
import {arrayIndex} from "@/core/util/arrayIndex";

export class EntityService {


    constructor() {

        const routerUrlsMap: EntityTypeInfoUrlVars = {
            SITE_DIR: '/base',
            ELEMENT_ID: ':elementId',
            SECTION_ID: ':sectionId',
            SECTION_CODE: ':sectionCode',
            SECTIONS_PATH: (info) => {
                if (info?.sectionPaths)
                    return info.sectionPaths.join('|')
                return ''
            },
        }

        const routerUrlParse = (url: string, info?: EntityTypeInfoCompiled) => {
            let haveEmptyVar = false
            const res = url.replaceAll(/#([\w\_]+)#/, (mt) => {
                const mask = routerUrlsMap[mt[1] as keyof typeof routerUrlsMap]
                const val = (typeof mask === 'function' ? mask(info) : mask) || ''
                if (!val) {
                    haveEmptyVar = true
                }
                return val
            })
            return !haveEmptyVar ? res : null
        }

        const compileEntityType = (info: EntityTypeInfo): EntityTypeInfoCompiled => {
            return {
                ...info,
                router: info.urls ? Object.fromEntries(Object.entries(info.urls).map(pair => [pair[0], routerUrlParse(pair[1])])) : {}
            }
        }

        const entityTypesList: EntityTypeInfoCompiled[] = (entityTypesRaw as EntityTypeInfo[]).map(compileEntityType)
        const entityTypesByRole = arrayIndex(entityTypesList, 'role')
        const entityTypes = arrayIndex(entityTypesList, 'type')

        return {
            entityTypesList,
            entityTypes,
            entityTypesByRole
        };
    }
}

export const entityService = new EntityService()

export type EntityTypeInfoUrlVars = Record<string, string | ((info?: EntityTypeInfoCompiled) => string)>

export type EntityTypeInfo = {
    type: string,
    role: string,
    urls?: {
        index?: string,
        view?: string,
        section?: string
    },
    sectionPaths?: string[]
}

export type EntityTypeInfoCompiled = EntityTypeInfo & {
    router: {
        index?: string,
        view?: string,
        section?: string
    }
}
