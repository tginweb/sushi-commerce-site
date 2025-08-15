export type TCodebuildCmdArgs = {
    config: string
}

export type TCodebuildMatchPattern  = string[] | string | boolean

export type TCodebuildConfigCondition = {
    order?: 'allow deny' | 'deny allow'
    allow?: TCodebuildMatchPattern
    deny?: TCodebuildMatchPattern
}

export type TCodebuildConfigType = TCodebuildConfigCondition & {
    entityType?: string
    nested?: Record<string, TCodebuildConfigType>
    tag?: string[] | string
    args?: any
}

export type TCodebuildConfigTemplateCondition = {
    type?: string
    tags?: string[] | string
    name?: string[] | string
    entityType?: boolean
}

export type TCodebuildConfigOperationSummaryContext = {
    type: string
    config: TCodebuildConfig
    typeConfig: TCodebuildConfigType
    items: any
}

export type TCodebuildConfigOperationContext = TCodebuildResultOperation & {
    type: string
    config: TCodebuildConfig
    typeConfig: TCodebuildConfigType
}

export type TCodebuildConfigOperationTemplate = {
    condition: TCodebuildConfigTemplateCondition,
    filename: (ctx: TCodebuildConfigOperationContext) => string
    filedir?: (ctx: TCodebuildConfigOperationContext) => string
    template: (ctx: TCodebuildConfigOperationContext) => string
    onSummary?: (ctx: TCodebuildConfigOperationContext) => string
}

export type TCodebuildFileConfig = TCodebuildConfig

export type TCodebuildConfig = {
    schema: string
    outputDir: string
    fragmentsDir?: string
    queriesDir?: string
    generateLib?: string
    typesPath?: string
    types: Record<string, TCodebuildConfigType>
    overrides: Record<string, any>
    operationTemplates: TCodebuildConfigOperationTemplate[]
    createDirs?: boolean
    generateFragments?: boolean
    generateQueries?: boolean

    genql: {
        dir?: string
        runtimePath?: string
        builderPath?: string
        customFragmentsDir?: string
        generateFragments?: boolean
        generateQueries?: boolean
        generateMutation?: boolean
        argsTypeExtract?: boolean
    }
}

export type TCodebuildFieldInfo = {
    name: string
    kind?: 'SCALAR' | 'OBJECT'
    type?: 'String' | 'Number' | 'Boolean' | string
    notNull?: boolean
    isList?: boolean
    args?: TCodebuildFieldInfo[]
    fields?: TCodebuildFieldInfo[]
}

export type TCodebuildOutputFile = {
    imports: string[]
    nestedFragments: Record<string, string>
    entityType: string
    filedir?: string
    filename: string
    content: string
}

export type TCodebuildSchema = {
    typesByName: Record<string, TCodebuildFieldInfo>,
    types: TCodebuildFieldInfo[],
    typesByTag: [],
    query: TCodebuildFieldInfo[],
    mutation: TCodebuildFieldInfo[]
}

export type TCodebuildResultOperation = {
    name: string
    nameTyped: string
    nameCamel: string
    variables: any
    fields: string[]
    resultType: string | null
    content: string
    deps: Record<string, any>
}

export type TCodebuildResultMutation = TCodebuildResultOperation & {
    type: 'mutation'
}

export type TCodebuildResultQuery = TCodebuildResultOperation & {
    type: 'query'
}

export type TCodebuildResultJsonFragment = {
    name: string
    fields: string[]
    import?: string
    content?: string
    deps: string[]
}

export type TCodebuildResultFragment = {
    name: string
    fields: string[]
    import?: string
    content: string
    deps: string[]
}

export type TCodebuildResultGenqlQuery = {
    name: string
    content: string
}

export type TCodebuildResult = {
    query: TCodebuildResultQuery[]
    mutation: TCodebuildResultMutation[]
    fragment: TCodebuildResultFragment[]
    genqlQuery: TCodebuildResultGenqlQuery[]
    genqlFragment: TCodebuildResultJsonFragment[]
}
