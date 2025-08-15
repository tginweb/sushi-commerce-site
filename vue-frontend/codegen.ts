import type {CodegenConfig} from '@graphql-codegen/cli';

const config: CodegenConfig = {
    overwrite: true,

    schema: "http://dev.sushi-new.prod.loc/api-new/graphql/",
//  documents: "src/gql/query/*.ts",
    generates: {
        /*
        "src/gql/gen-types/": {
            preset: "client",
            presetConfig: {
                fragmentMasking: {unmaskFunctionName: "getFragmentData"}
            },
            documentTransforms: []
        },
         */
        "./graphql.schema.graphql": {
            plugins: ['schema-ast'],
            config: {
                includeIntrospectionTypes: false
            }
        }
    }
};

export default config;
