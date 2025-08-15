import {MutationGenqlSelection, QueryGenqlSelection} from "@/gql/gen";
import {fragments, JsonFragmentName} from "@/gql/gen/fragments";

export class GenqlBuilder {

    jsonFragments: Record<string, any> = {}

    constructor() {
        this.registerJsonFragments(fragments)
    }

    registerJsonFragments(data: any) {
        this.jsonFragments = {
            ...this.jsonFragments,
            ...data
        }
    }

    getFragment(name: string) {
        return this.jsonFragments[name]
    }

    prepare(query: Record<string, any>, args: any = {}, parentFragments: any = {}) {

        const result: any = {}

        const _args = {...args} || {}

        for (let [fieldName, fieldValue] of Object.entries(query)) {

            let fieldResult = fieldValue

            if (!['__args', '__fragment'].includes(fieldName)) {

                if (fieldValue === true) {
                    if (_args[fieldName]) {
                        fieldValue = {
                            __args: _args[fieldName]
                        }
                        delete _args[fieldName]
                    }
                    fieldResult = fieldValue
                } else if (fieldValue === false) {
                    continue;
                } else if (typeof fieldValue === 'object') {

                    const fragmentName = fieldValue.__fragment

                    const _parentFragments = {...parentFragments}

                    if (fragmentName) {
                        if (!_parentFragments[fragmentName]) {
                            _parentFragments[fragmentName] = fragmentName
                            const fragment = this.getFragment(fragmentName)
                            fieldValue = {
                                ...fieldValue,
                                ...fragment
                            }
                        } else {
                            if (Object.keys(query).filter(field => !field.match('__')).length) {
                                continue;
                            } else {
                                return false
                            }
                        }
                    }

                    fieldResult = this.prepare(fieldValue, _args[fieldName] || {}, _parentFragments)

                    delete _args[fieldName]
                    delete fieldResult.__fragment

                    if (!fieldResult)
                        continue;
                }
            }

            //@ts-ignore
            result[fieldName] = fieldResult
        }

        if (Object.keys(_args).length) {
            const queryArgs = result._args || {}
            const totalArgs = {
                ...queryArgs,
                ..._args
            }
            result.__args = totalArgs
        }

        return result
    }

    build(query: JsonFragmentName | QueryGenqlSelection | MutationGenqlSelection | string, args: any = {}): any {

        let _queryName: string
        let _query: QueryGenqlSelection
        let _args = args

        if (typeof query === 'string') {
            _queryName = query
            _query = {[_queryName]: this.getFragment(query)}
        } else if (typeof query === 'object') {
            _queryName = Object.keys(query)[0] || ''
            _query = query
        } else {
            return null
        }

        _args = !args[_queryName] ? {[_queryName]: args} : args

        return this.prepare(_query, _args)
    }

}

export const genqlBuilder = new GenqlBuilder()

export const genqlBuild = (query: JsonFragmentName | QueryGenqlSelection | string, args: any = {}) => genqlBuilder.build(query, args)
