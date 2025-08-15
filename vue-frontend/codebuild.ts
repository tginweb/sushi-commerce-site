import {TCodebuildConfigOperationContext, TCodebuildFileConfig} from "./src/core/graphql/codebuild/types";

const config: TCodebuildFileConfig = {
    schema: "graphql.schema.graphql",

    createDirs: true,

    outputDir: 'src/gql/gen-builder',
    generateLib: '@core/graphql/codebuild/util',
    typesPath: 'src/gql/gen/schema',

    generateFragments: false,
    generateQueries: false,

    genql: {
        dir: 'src/gql/gen',
        runtimePath: '@/core/graphql/genql/runtime',
        builderPath: '@/core/graphql/genql/builder',
        customFragmentsDir: './src/gql/fragments',
        generateFragments: true,
        generateQueries: true,
        generateMutation: true,
        argsTypeExtract: true
    },

    types: {
        catalog_product_recordset: {
            entityType: 'ProductElement'
        },
        catalog_product_list: {
            entityType: 'ProductElement'
        },
    },
    // @ts-ignore
    overrides: {},
    operationTemplates: [
        {
            condition: {
                type: 'query',
                name: ['/Recordset/'],
                entityType: true
            },
            filedir: (ctx: TCodebuildConfigOperationContext) => ctx.config.genql.dir + '/hooks',
            filename: (ctx: TCodebuildConfigOperationContext) => ctx.nameCamel + 'EntityQuery.ts',
            template: (ctx: TCodebuildConfigOperationContext) => {

                const entityType = ctx.typeConfig.entityType

                const typeName = ctx.name
                const argsType = 'Query' + ctx.nameTyped + 'Args'

                return `           

import {${entityType}, ${Object.keys(ctx.deps).join(', ')}, ${argsType}} from "${ctx.config.typesPath}";

import Query from '../query/${ctx.nameCamel}Query';

import {useEntityRecordsetQueryGenerator} from "@/core/graphql/hooks/useEntityRecordsetQuery";

export default useEntityRecordsetQueryGenerator<${entityType}, {
    ${typeName}: ${ctx.resultType}
},  ${argsType}>({
    queryBuilder: Query,
    queryAlias: '${typeName}'
})
      `
            }
        }
    ]
};

export default config;
