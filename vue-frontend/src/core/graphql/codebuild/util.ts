import {gql as gqlNative} from "@apollo/client"

type TGraphqlInjectFragmentContext = {}

type TGraphqlInjectFragments = Record<string, any>
type TGraphqlInjectFragmentsFunc = () => TGraphqlInjectFragments

function flattenFragments(fragment: any): string {
    return Array.isArray(fragment) ? fragment.join("\n") : fragment
}

function getFragmentName(fragment: any) {
    //console.log('flattenFragments(fragment)', flattenFragments(fragment))
    const mt = flattenFragments(fragment).match(/fragment\s+(\w+)\s+on\s+(\w+)\s*\{/)
    return mt ? mt[1] : null
}

export type TCodebuildInjectOverrides = {}

export function fragment(doc: string | string[], fragments: TGraphqlInjectFragments | TGraphqlInjectFragmentsFunc = {}): any {
    return (overrides: TCodebuildInjectOverrides = {}, ctx: TGraphqlInjectFragmentContext = {}, parents = []) => {

        overrides = overrides || {}

        const _doc = flattenFragments(doc)

        const fragmentName = getFragmentName(_doc)

        const parentsWithCurrent = [...parents, fragmentName]

        const _fragments = typeof fragments === 'function' ? fragments() : fragments

        const fragmentsRes = []

        for (let [subFragmentName, subFragment] of Object.entries(_fragments) as [string, any][]) {
            subFragmentName = subFragmentName.replace(/Fragment/, '')
            if (!parentsWithCurrent.includes(subFragmentName)) {
                fragmentsRes.push(subFragment(overrides, ctx, parentsWithCurrent))
            }
        }

        let docResult = _doc

        for (const parent of parents) {
            const re = new RegExp('(\\w+)\\s*{[\\s\\n\\r\\t]*...' + parent + '[\\s\\n\\r\\t]*}', 'gi')
            docResult = docResult.replace(re, '')

        }

        docResult += "\n" + fragmentsRes.join("\n")

        if (!parents.length) {
            // console.log('READY', docResult)
        }

        if (!parents.length) {
            let documentNode = gqlNative(docResult)
            return documentNode
        } else {
            return docResult
        }
    }
}

export function jsonFragment(fragmentName: string, doc: () => any, fragments: TGraphqlInjectFragments | TGraphqlInjectFragmentsFunc = {}): any {
    return (overrides: TCodebuildInjectOverrides = {}, ctx: TGraphqlInjectFragmentContext = {}, parents = []) => {
        return doc() as any
    }
}

export function gql(doc: any) {
    return doc
}
